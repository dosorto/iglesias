<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    public function getConnectionName(): string
    {
        return config('tenancy.central_connection', config('database.default'));
    }

    protected $fillable = [
        'company_name',
        'company_logo_path',
    ];

    protected $appends = ['company_logo_url'];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['company_name' => 'NekoTech']
        );
    }

    protected function companyLogoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->company_logo_path
                ? asset('storage/' . ltrim($this->company_logo_path, '/'))
                : null,
        );
    }
}
