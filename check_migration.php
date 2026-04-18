<?php
require 'vendor/autoload.php';
\ = require_once 'bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();
\ Warning: Module "mysqli" is already loaded in Unknown on line 0 tenant_parroquia_san_juan_12 = App\Models\Iglesias::first();
\ = \ Warning: Module "mysqli" is already loaded in Unknown on line 0 tenant_parroquia_san_juan_12->db_database;
\ = config('database.connections.mysql');
\['database'] = \;
config(['database.connections.tenant' => \]);
\ = Illuminate\Support\Facades\Schema::connection('tenant')->hasColumn('iglesias', 'paper_size_certificado_bautismo');
echo "Column exists: " . (\ ? 'YES' : 'NO') . PHP_EOL;
