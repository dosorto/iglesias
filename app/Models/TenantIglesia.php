<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantIglesia extends Model
{
    use HasFactory;

    protected $table = 'iglesias';

    public static function currentId(): ?int
    {
        $iglesiaId = static::query()->value('id');

        return $iglesiaId ? (int) $iglesiaId : null;
    }

    public static function current(): ?self
    {
        return static::query()->first();
    }
}