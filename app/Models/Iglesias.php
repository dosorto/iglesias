<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Iglesias extends Model
{
    use HasFactory;

    protected $table = 'iglesias';

    protected $fillable = [
        'nombre',
        'direccion',
        'parroco_nombre',
        'telefono',
        'email',
        'estado',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_iglesia');
    }
}