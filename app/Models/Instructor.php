<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'instructores';

    protected $fillable = [
        'feligres_id',
        'path_firma',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function feligres()
    {
        return $this->belongsTo(Feligres::class, 'feligres_id');
    }
}