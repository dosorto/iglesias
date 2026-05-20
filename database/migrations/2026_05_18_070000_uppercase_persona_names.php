<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('personas')
            ->select(['id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'])
            ->orderBy('id')
            ->chunkById(200, function ($personas): void {
                foreach ($personas as $persona) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update([
                            'primer_nombre' => $this->uppercase($persona->primer_nombre),
                            'segundo_nombre' => $this->uppercase($persona->segundo_nombre),
                            'primer_apellido' => $this->uppercase($persona->primer_apellido),
                            'segundo_apellido' => $this->uppercase($persona->segundo_apellido),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // No revertimos porque la normalización a mayúsculas es intencional.
    }

    private function uppercase(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? mb_strtoupper($value, 'UTF-8') : null;
    }
};
