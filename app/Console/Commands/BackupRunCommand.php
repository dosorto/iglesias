<?php

namespace App\Console\Commands;

use App\Models\Iglesias;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;
use ZipArchive;

class BackupRunCommand extends Command
{
    protected $signature = 'backup:run {--keep= : Override retention days}';

    protected $description = 'Create a backup for the configured church tenant and local files';

    public function handle(): int
    {
        $workingPath = null;

        try {
            $timestamp = now()->format('Ymd_His');
            $tenant = $this->resolveTenantBackupTarget();
            $appName = Str::slug((string) config('app.name', 'iglesias'), '-');
            $tenantSlug = $tenant['slug'];
            $rootPath = $this->resolveBackupRootPath();
            $workingPath = $rootPath . '/tmp_' . $timestamp;
            $zipPath = $rootPath . '/' . $appName . '_' . $tenantSlug . '_backup_' . $timestamp . '.zip';

            File::ensureDirectoryExists($rootPath);
            File::ensureDirectoryExists($workingPath . '/databases');
            File::ensureDirectoryExists($workingPath . '/files');
            File::ensureDirectoryExists($workingPath . '/metadata');

            $this->info('Creating backup...');

            $manifest = [
                'created_at' => now()->toIso8601String(),
                'app_name' => config('app.name'),
                'app_env' => config('app.env'),
                'central_connection' => config('tenancy.central_connection', config('database.default')),
                'tenant_connection' => config('tenancy.tenant_connection', 'tenant'),
                'tenant' => [
                    'id' => $tenant['id'],
                    'slug' => $tenant['slug'],
                    'name' => $tenant['name'],
                    'database' => $tenant['database'],
                    'host' => $tenant['host'],
                    'port' => $tenant['port'],
                    'username' => $tenant['username'],
                    'subdomain' => $tenant['subdomain'],
                    'source' => $tenant['source'],
                ],
                'databases' => [],
                'files' => [],
            ];

            $manifest['databases'][] = $this->dumpConnection(
                $tenant['connection'],
                'tenant_' . $tenant['id'] . '_' . $tenant['slug'],
                $workingPath . '/databases'
            );

            $filesPath = $workingPath . '/files';
            if ((bool) config('backup.include_env', true)) {
                $this->copyIfExists(base_path('.env'), $filesPath . '/.env', $manifest['files']);
            }
            if ((bool) config('backup.include_files', true)) {
                $this->copyDirectoryIfExists(storage_path('app/public'), $filesPath . '/storage_app_public', $manifest['files']);
                $this->copyDirectoryIfExists(storage_path('app/private'), $filesPath . '/storage_app_private', $manifest['files']);
            }

            $this->writeTenantMetadata($tenant, $workingPath . '/metadata');

            File::put(
                $workingPath . '/manifest.json',
                json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );

            $this->createZipFromDirectory($workingPath, $zipPath);
            File::deleteDirectory($workingPath);

            $this->cleanupOldBackups($rootPath);

            $this->info('Backup created: ' . $zipPath);

            return self::SUCCESS;
        } catch (Throwable $e) {
            if (is_string($workingPath) && $workingPath !== '') {
                File::deleteDirectory($workingPath);
            }
            $this->error('Backup failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function resolveBackupRootPath(): string
    {
        $configured = trim((string) config('backup.directory', 'backups'));

        if ($configured === '') {
            return storage_path('app/backups');
        }

        if ($this->isAbsolutePath($configured)) {
            return $configured;
        }

        return storage_path('app/' . trim($configured, '/\\'));
    }

    /**
     * @return array{id:int,connection:string,slug:string,name:string,database:string,host:string,port:string,username:string,subdomain:?string,source:string,password:?string}
     */
    private function resolveTenantBackupTarget(): array
    {
        $centralConnection = config('tenancy.central_connection', config('database.default'));
        $tenantConnection = (string) config('tenancy.tenant_connection', 'tenant');
        $centralConfig = config("database.connections.{$centralConnection}");

        $configuredDatabase = trim((string) config('backup.tenant_db_database', ''));
        if ($configuredDatabase !== '') {
            $centralConfig = is_array($centralConfig) ? $centralConfig : [];
            $configuredHost = trim((string) config('backup.tenant_db_host', ''));
            $configuredPort = trim((string) config('backup.tenant_db_port', ''));
            $configuredUsername = trim((string) config('backup.tenant_db_username', ''));
            $configuredPassword = config('backup.tenant_db_password');
            $configuredName = trim((string) config('backup.tenant_name', '')) ?: 'IGLESIA LOCAL';
            $configuredId = (int) (config('backup.iglesia_id') ?: 1);
            $configuredSubdomain = trim((string) config('backup.tenant_subdomain', '')) ?: null;

            if ($configuredHost === '' || $configuredPort === '' || $configuredUsername === '') {
                throw new \RuntimeException('BACKUP_TENANT_DB_HOST, BACKUP_TENANT_DB_PORT and BACKUP_TENANT_DB_USERNAME are required when BACKUP_TENANT_DB_DATABASE is set.');
            }

            $connection = "backup_{$tenantConnection}_configured";

            config([
                "database.connections.{$connection}" => [
                    'driver' => $centralConfig['driver'] ?? 'mysql',
                    'host' => $configuredHost,
                    'port' => $configuredPort,
                    'database' => $configuredDatabase,
                    'username' => $configuredUsername,
                    'password' => $configuredPassword,
                    'unix_socket' => $centralConfig['unix_socket'] ?? '',
                    'charset' => $centralConfig['charset'] ?? 'utf8mb4',
                    'collation' => $centralConfig['collation'] ?? 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => $centralConfig['prefix_indexes'] ?? true,
                    'strict' => $centralConfig['strict'] ?? true,
                    'engine' => $centralConfig['engine'] ?? null,
                    'options' => $centralConfig['options'] ?? [],
                ],
            ]);

            DB::purge($connection);

            return [
                'id' => $configuredId,
                'connection' => $connection,
                'slug' => Str::slug($configuredName ?: $configuredDatabase, '-'),
                'name' => $configuredName,
                'database' => $configuredDatabase,
                'host' => $configuredHost,
                'port' => $configuredPort,
                'username' => $configuredUsername,
                'subdomain' => $configuredSubdomain,
                'source' => 'config',
                'password' => is_string($configuredPassword) ? $configuredPassword : null,
            ];
        }

        if (! is_array($centralConfig)) {
            throw new \RuntimeException('Central database connection is not configured for backups.');
        }

        $query = Iglesias::on($centralConnection)
            ->whereNotNull('db_database')
            ->where(function ($builder) {
                $builder->whereNull('deleted_at');
            });

        $configuredIglesiaId = config('backup.iglesia_id');
        if (filled($configuredIglesiaId)) {
            $query->where('id', (int) $configuredIglesiaId);
        }

        $iglesias = $query->get();

        if ($iglesias->isEmpty()) {
            throw new \RuntimeException('No church tenant was found for backup. Set BACKUP_IGLESIA_ID in .env.');
        }

        if ($iglesias->count() > 1) {
            throw new \RuntimeException('Multiple church tenants were found. Set BACKUP_IGLESIA_ID in .env to select one.');
        }

        /** @var Iglesias $iglesia */
        $iglesia = $iglesias->first();
        $connection = "backup_{$tenantConnection}_{$iglesia->id}";

        config([
            "database.connections.{$connection}" => array_merge($centralConfig, [
                'host' => $iglesia->db_host ?: ($centralConfig['host'] ?? null),
                'port' => $iglesia->db_port ?: ($centralConfig['port'] ?? null),
                'database' => $iglesia->db_database,
                'username' => $iglesia->db_username ?: ($centralConfig['username'] ?? null),
                'password' => $iglesia->db_password ?: ($centralConfig['password'] ?? null),
            ]),
        ]);

        DB::purge($connection);

        return [
            'id' => (int) $iglesia->id,
            'connection' => $connection,
            'slug' => Str::slug((string) ($iglesia->nombre ?: $iglesia->db_database ?: 'tenant'), '-'),
            'name' => (string) ($iglesia->nombre ?: 'IGLESIA LOCAL'),
            'database' => (string) $iglesia->db_database,
            'host' => (string) ($iglesia->db_host ?: ($centralConfig['host'] ?? '')),
            'port' => (string) ($iglesia->db_port ?: ($centralConfig['port'] ?? '')),
            'username' => (string) ($iglesia->db_username ?: ($centralConfig['username'] ?? '')),
            'subdomain' => $iglesia->subdomain,
            'source' => 'central',
            'password' => (string) ($iglesia->db_password ?: ($centralConfig['password'] ?? '')),
        ];
    }

    /**
     * @param array{id:int,connection:string,slug:string,name:string,database:string,host:string,port:string,username:string,subdomain:?string,source:string,password:?string} $tenant
     */
    private function writeTenantMetadata(array $tenant, string $targetDirectory): void
    {
        File::put(
            $targetDirectory . '/tenant.json',
            json_encode([
                'id' => $tenant['id'],
                'name' => $tenant['name'],
                'subdomain' => $tenant['subdomain'],
                'db_host' => $tenant['host'],
                'db_port' => $tenant['port'],
                'db_database' => $tenant['database'],
                'db_username' => $tenant['username'],
                'source' => $tenant['source'],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        $sql = [
            '-- Restore this row into the central iglesias table after running migrations.',
            'INSERT INTO `iglesias` (`id`, `nombre`, `subdomain`, `estado`, `db_host`, `db_port`, `db_database`, `db_username`, `db_password`, `created_at`, `updated_at`)',
            'VALUES (' .
                (int) $tenant['id'] . ', ' .
                $this->quoteSqlLiteral($tenant['name']) . ', ' .
                $this->quoteSqlLiteral($tenant['subdomain']) . ', ' .
                $this->quoteSqlLiteral('Activo') . ', ' .
                $this->quoteSqlLiteral($tenant['host']) . ', ' .
                $this->quoteSqlLiteral($tenant['port']) . ', ' .
                $this->quoteSqlLiteral($tenant['database']) . ', ' .
                $this->quoteSqlLiteral($tenant['username']) . ', ' .
                $this->quoteSqlLiteral($tenant['password']) . ', ' .
                $this->quoteSqlLiteral(now()->toDateTimeString()) . ', ' .
                $this->quoteSqlLiteral(now()->toDateTimeString()) .
            ')',
            'ON DUPLICATE KEY UPDATE',
            '`nombre` = VALUES(`nombre`),',
            '`subdomain` = VALUES(`subdomain`),',
            '`estado` = VALUES(`estado`),',
            '`db_host` = VALUES(`db_host`),',
            '`db_port` = VALUES(`db_port`),',
            '`db_database` = VALUES(`db_database`),',
            '`db_username` = VALUES(`db_username`),',
            '`db_password` = VALUES(`db_password`),',
            '`updated_at` = VALUES(`updated_at`);',
            '',
        ];

        File::put($targetDirectory . '/restore_iglesia.sql', implode("\n", $sql));

        $instructions = [
            'INSTRUCCIONES DE RESTAURACION',
            '=============================',
            '',
            'Este respaldo contiene:',
            '- Un dump SQL de la base tenant de la iglesia',
            '- El archivo metadata/tenant.json con los datos base de la iglesia',
            '- El archivo metadata/restore_iglesia.sql para recrear el registro central',
            '- El archivo files/.env si fue incluido en el respaldo',
            '- Los archivos de storage si fueron incluidos en el respaldo',
            '',
            'PASOS RECOMENDADOS',
            '------------------',
            '1. Clona o copia el proyecto en la maquina destino.',
            '2. Configura el archivo .env para que Laravel pueda conectarse a la base central.',
            '3. Ejecuta las migraciones de la base central:',
            '   php artisan migrate --force',
            '4. Crea la base tenant con este nombre si no existe:',
            '   ' . $tenant['database'],
            '5. Importa el dump SQL de la tenant ubicado en la carpeta databases/.',
            '6. Ejecuta el archivo metadata/restore_iglesia.sql sobre la base central para recrear o actualizar el registro de la iglesia.',
            '7. Si este respaldo incluye archivos, copia:',
            '   - files/storage_app_public  -> storage/app/public',
            '   - files/storage_app_private -> storage/app/private',
            '8. Si este respaldo incluye files/.env, usalo como referencia para restaurar configuracion local.',
            '9. Limpia caches de Laravel:',
            '   php artisan config:clear',
            '   php artisan cache:clear',
            '   php artisan view:clear',
            '10. Si aplica, recrea el enlace publico de storage:',
            '   php artisan storage:link',
            '',
            'DATOS DE ESTA IGLESIA',
            '---------------------',
            'ID: ' . $tenant['id'],
            'Nombre: ' . $tenant['name'],
            'Subdominio: ' . ($tenant['subdomain'] ?: 'N/A'),
            'Host tenant: ' . $tenant['host'],
            'Puerto tenant: ' . $tenant['port'],
            'Base tenant: ' . $tenant['database'],
            'Usuario tenant: ' . $tenant['username'],
            '',
            'NOTA',
            '----',
            'Si restauras en otra maquina, ajusta el .env y el registro central de la iglesia si el host, puerto o nombre de base de datos cambian.',
            '',
        ];

        File::put($targetDirectory . '/RESTAURAR.txt', implode("\n", $instructions));
    }

    /**
     * @return array{label:string,connection:string,database:string,file:string}
     */
    private function dumpConnection(string $connectionName, string $label, string $targetDirectory): array
    {
        $databaseName = (string) config("database.connections.{$connectionName}.database", $connectionName);
        $fileName = $label . '.sql';
        $targetFile = $targetDirectory . '/' . $fileName;

        $this->line("  Dumping {$label} ({$databaseName})");

        $connection = DB::connection($connectionName);
        $pdo = $connection->getPdo();
        $sql = [];
        $sql[] = '-- Backup generated at ' . now()->toDateTimeString();
        $sql[] = '-- Connection: ' . $connectionName;
        $sql[] = '-- Database: ' . $databaseName;
        $sql[] = 'SET FOREIGN_KEY_CHECKS=0;';
        $sql[] = '';

        foreach ($this->tablesForConnection($connectionName) as $table) {
            $createRow = (array) $connection->selectOne('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`');
            $createStatement = (string) ($createRow['Create Table'] ?? array_values($createRow)[1] ?? '');

            $sql[] = '-- Table: ' . $table;
            $sql[] = 'DROP TABLE IF EXISTS `' . $table . '`;';
            $sql[] = $createStatement . ';';
            $sql[] = '';

            $columns = $connection->getSchemaBuilder()->getColumnListing($table);
            if ($columns === []) {
                continue;
            }

            $orderColumn = in_array('id', $columns, true) ? 'id' : $columns[0];
            $offset = 0;
            $limit = 250;

            do {
                $rows = $connection->table($table)
                    ->orderBy($orderColumn)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                if ($rows->isEmpty()) {
                    break;
                }

                $valueGroups = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ($columns as $column) {
                        $rowValues[] = $this->quoteSqlValue($pdo, $row->{$column} ?? null);
                    }
                    $valueGroups[] = '(' . implode(', ', $rowValues) . ')';
                }

                $sql[] = 'INSERT INTO `' . $table . '` (`' . implode('`, `', $columns) . '`) VALUES';
                $sql[] = implode(",\n", $valueGroups) . ';';
                $sql[] = '';

                $offset += $rows->count();
            } while ($rows->count() === $limit);
        }

        $sql[] = 'SET FOREIGN_KEY_CHECKS=1;';
        $sql[] = '';

        File::put($targetFile, implode("\n", $sql));

        return [
            'label' => $label,
            'connection' => $connectionName,
            'database' => $databaseName,
            'file' => 'databases/' . $fileName,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function tablesForConnection(string $connectionName): array
    {
        $rows = DB::connection($connectionName)->select('SHOW TABLES');

        return collect($rows)
            ->map(function ($row) {
                $values = array_values((array) $row);

                return (string) ($values[0] ?? '');
            })
            ->filter()
            ->values()
            ->all();
    }

    private function quoteSqlValue(\PDO $pdo, mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $pdo->quote((string) $value);
    }

    private function quoteSqlLiteral(?string $value): string
    {
        if ($value === null || $value === '') {
            return 'NULL';
        }

        return "'" . str_replace("'", "''", $value) . "'";
    }

    /**
     * @param array<int, string> $manifestFiles
     */
    private function copyIfExists(string $sourcePath, string $targetPath, array &$manifestFiles): void
    {
        if (! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($targetPath));
        File::copy($sourcePath, $targetPath);
        $manifestFiles[] = str_replace('\\', '/', str_replace(dirname(dirname($targetPath)) . '/', '', $targetPath));
    }

    /**
     * @param array<int, string> $manifestFiles
     */
    private function copyDirectoryIfExists(string $sourcePath, string $targetPath, array &$manifestFiles): void
    {
        if (! File::isDirectory($sourcePath)) {
            return;
        }

        File::copyDirectory($sourcePath, $targetPath);
        $manifestFiles[] = str_replace('\\', '/', str_replace(dirname(dirname($targetPath)) . '/', '', $targetPath));
    }

    private function createZipFromDirectory(string $sourceDirectory, string $zipPath): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create backup zip file.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDirectory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', substr($filePath, strlen($sourceDirectory) + 1));
            $zip->addFile($filePath, $relativePath);
        }

        $zip->close();
    }

    private function cleanupOldBackups(string $rootPath): void
    {
        $keepDays = (int) ($this->option('keep') ?: config('backup.keep_days', 14));
        if ($keepDays < 1) {
            return;
        }

        $cutoff = now()->subDays($keepDays);

        foreach (File::files($rootPath) as $file) {
            if ($file->getExtension() !== 'zip') {
                continue;
            }

            if ($file->getMTime() < $cutoff->timestamp) {
                File::delete($file->getRealPath());
            }
        }
    }

    private function isAbsolutePath(string $path): bool
    {
        return preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) === 1
            || str_starts_with($path, '\\\\')
            || str_starts_with($path, '/');
    }
}
