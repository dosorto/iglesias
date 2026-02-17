<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'religion';
    protected $fillable = [
        'religion',
    ];

}
