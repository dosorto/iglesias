<?php

namespace App\Livewire\Forms;

use App\Models\Iglesias;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Try central DB first (root / superadmin users)
        if (Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // 2. Scan each tenant DB looking for the user
        $centralConn = config('tenancy.central_connection', config('database.default'));
        $baseConfig  = config("database.connections.{$centralConn}");

        $iglesias = Iglesias::whereNotNull('db_database')->get();

        foreach ($iglesias as $iglesia) {
            $tenantConfig = array_merge($baseConfig, [
                'host'     => $iglesia->db_host     ?? $baseConfig['host'],
                'port'     => $iglesia->db_port      ?? $baseConfig['port'],
                'database' => $iglesia->db_database,
                'username' => $iglesia->db_username  ?? $baseConfig['username'],
                'password' => $iglesia->db_password  ?? $baseConfig['password'],
            ]);

            $tempConn = 'tenant_auth_temp';
            config(["database.connections.{$tempConn}" => $tenantConfig]);
            DB::purge($tempConn);

            $tenantUser = DB::connection($tempConn)
                ->table('users')
                ->where('email', strtolower($this->email))
                ->whereNull('deleted_at')
                ->first();

            if ($tenantUser && Hash::check($this->password, $tenantUser->password)) {
                // Found — configure the real tenant connection for this request
                $tenantConnection = config('tenancy.tenant_connection', 'tenant');

                config([
                    "database.connections.{$tenantConnection}" => $tenantConfig,
                    'database.default'                          => $tenantConnection,
                ]);
                DB::purge($tenantConnection);
                DB::reconnect($tenantConnection);

                // Store tenant data in session so future requests use the right DB
                session()->put('tenant', [
                    'id_iglesia' => $iglesia->id,
                    'connection' => $tenantConnection,
                    'host'       => $iglesia->db_host,
                    'port'       => $iglesia->db_port,
                    'database'   => $iglesia->db_database,
                    'username'   => $iglesia->db_username,
                    'password'   => $iglesia->db_password,
                ]);

                // Authenticate against the tenant DB
                if (Auth::attempt($this->only(['email', 'password']), $this->remember)) {
                    RateLimiter::clear($this->throttleKey());
                    return;
                }
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
