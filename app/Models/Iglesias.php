<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'id_religion',
        'path_logo',
        'db_connection',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
    ];

   protected $appends = ['logo_url'];

    // URL pública del logo (null si no tiene)
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path_logo
                ? asset('storage/' . $this->path_logo)
                : null,
        );
    }


    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_iglesia');
    }
    public function religion()
{
    return $this->belongsTo(Religion::class, 'id_religion');
}
}