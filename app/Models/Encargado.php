<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Encargado extends BaseModel
{

    use HasFactory, SoftDeletes;

    protected $table = 'encargado'; 

    
    protected $fillable = [
        'id_feligres',
        'path_firma_principal',
        'estado',
    ];

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'id_feligres');
    }
}
