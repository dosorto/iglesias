<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'orientacion_certificado_bautismo' => ['length' => 20, 'default' => 'portrait'],
            'orientacion_certificado_confirmacion' => ['length' => 20, 'default' => 'portrait'],
            'orientacion_certificado_primera_comunion' => ['length' => 20, 'default' => 'portrait'],
            'orientacion_certificado_matrimonio' => ['length' => 20, 'default' => 'portrait'],
            'orientacion_certificado_curso' => ['length' => 20, 'default' => 'landscape'],
            'paper_size_certificado' => ['length' => 20, 'default' => 'letter'],
            'paper_size_certificado_bautismo' => ['length' => 20, 'default' => 'letter'],
            'paper_size_certificado_confirmacion' => ['length' => 20, 'default' => 'letter'],
            'paper_size_certificado_primera_comunion' => ['length' => 20, 'default' => 'letter'],
            'paper_size_certificado_matrimonio' => ['length' => 20, 'default' => 'letter'],
            'paper_size_certificado_curso' => ['length' => 20, 'default' => 'letter'],
        ];

        foreach ($columns as $column => $config) {
            if (Schema::hasColumn('iglesias', $column)) {
                continue;
            }

            Schema::table('iglesias', function (Blueprint $table) use ($column, $config) {
                $table
                    ->string($column, $config['length'])
                    ->default($config['default']);
            });
        }
    }

    public function down(): void
    {
        // Esta migración solo repara esquemas incompletos.
    }
};
