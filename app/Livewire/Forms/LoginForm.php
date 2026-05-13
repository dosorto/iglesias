<?php

namespace App\Livewire\Forms;

use App\Models\Iglesias;
use App\Models\User;
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

        $this->resetTenantSessionWhenLoggingFromCentralDomain();

        session()->forget('tenant_login_subdomain_url');

        $baseDomain = strtolower(trim((string) config('tenancy.base_domain', '')));
        $host = strtolower((string) request()->getHost());
        $isCentralHost = $baseDomain === '' || $host === $baseDomain || $host === 'www.' . $baseDomain;

        // 1. Try central DB first only when user logs in from the central domain.
        if ($isCentralHost && Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            $this->hideTemporaryPasswordAfterInstructorLogin();
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // 2. Scan each tenant DB looking for the user
        $centralConn = config('tenancy.central_connection', config('database.default'));
        $baseConfig  = config("database.connections.{$centralConn}");

        $iglesias = Iglesias::whereNotNull('db_database')->get();

        $tenantMatches = [];

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
                $tenantMatches[] = [
                    'iglesia'      => $iglesia,
                    'tenantConfig' => $tenantConfig,
                ];
            }
        }

        // If credentials match multiple tenants, avoid logging into a random DB.
        if (count($tenantMatches) > 1) {
            $preferredTenantId = null;
            $currentHost = strtolower((string) request()->getHost());
            $baseDomain = strtolower(trim((string) config('tenancy.base_domain', '')));

            if ($baseDomain !== '' && $currentHost !== $baseDomain) {
                $suffix = '.' . $baseDomain;

                if (str_ends_with($currentHost, $suffix)) {
                    $hostLabel = substr($currentHost, 0, -strlen($suffix));

                    $preferredTenantId = Iglesias::query()
                        ->where(function ($query) use ($currentHost, $hostLabel) {
                            $query->whereRaw('LOWER(subdomain) = ?', [$currentHost])
                                ->orWhereRaw('LOWER(subdomain) = ?', [$hostLabel]);
                        })
                        ->value('id');
                }
            }

            if ($preferredTenantId) {
                $tenantMatches = array_values(array_filter(
                    $tenantMatches,
                    fn (array $match) => (int) $match['iglesia']->id === (int) $preferredTenantId
                ));
            }

            if (count($tenantMatches) !== 1) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'form.email' => 'Estas credenciales existen en varias iglesias. Selecciona primero la iglesia y vuelve a iniciar sesión, o usa credenciales únicas por tenant.',
                ]);
            }
        }

        if (count($tenantMatches) === 1) {
            $matchedIglesia = $tenantMatches[0]['iglesia'];
            $matchedConfig = $tenantMatches[0]['tenantConfig'];
            $tenantConnection = config('tenancy.tenant_connection', 'tenant');
            $subdomain = $this->ensureIglesiaSubdomain($matchedIglesia);

            // Login tenant directo: no habilitar regreso a panel global.
            session()->forget('tenant_can_return_global');

            config([
                "database.connections.{$tenantConnection}" => $matchedConfig,
                'database.default'                          => $tenantConnection,
            ]);
            DB::purge($tenantConnection);
            DB::reconnect($tenantConnection);

            session()->put('tenant', [
                'id_iglesia' => $matchedIglesia->id,
                'connection' => $tenantConnection,
                'subdomain' => $subdomain,
            ]);

            $tenantSubdomainUrl = $this->buildTenantSubdomainUrl($subdomain);
            if ($tenantSubdomainUrl) {
                session()->put('tenant_login_subdomain_url', $tenantSubdomainUrl);
            }

            if (Auth::attempt($this->only(['email', 'password']), $this->remember)) {
                $this->hideTemporaryPasswordAfterInstructorLogin();
                RateLimiter::clear($this->throttleKey());
                return;
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

    private function hideTemporaryPasswordAfterInstructorLogin(): void
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return;
        }

        $user = User::with('roles')->find($authUser->id);

        if (! $user || empty($user->password_visible)) {
            return;
        }

        $isInstructor = $user->roles->contains('name', 'instructor');

        if ($isInstructor) {
            $user->update(['password_visible' => null]);
        }
    }

    private function ensureIglesiaSubdomain(Iglesias $iglesia): string
    {
        $current = strtolower(trim((string) ($iglesia->subdomain ?? '')));

        if ($current !== '') {
            return $current;
        }

        $candidate = Iglesias::resolveUniqueSubdomainForName((string) $iglesia->nombre, (int) $iglesia->id);

        $iglesia->update(['subdomain' => $candidate]);

        return $candidate;
    }

    private function buildTenantSubdomainUrl(string $subdomain): ?string
    {
        if (str_contains($subdomain, '.')) {
            $scheme = request()->getScheme() ?: 'http';

            return $scheme . '://' . $subdomain;
        }

        $baseDomain = trim((string) config('tenancy.base_domain', ''));

        if ($baseDomain === '') {
            return null;
        }

        $scheme = request()->getScheme() ?: 'http';

        return $scheme . '://' . $subdomain . '.' . $baseDomain;
    }

    private function resetTenantSessionWhenLoggingFromCentralDomain(): void
    {
        $baseDomain = strtolower(trim((string) config('tenancy.base_domain', '')));

        if ($baseDomain === '') {
            return;
        }

        $host = strtolower((string) request()->getHost());

        if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
            session()->forget('tenant');
            session()->forget('tenant_can_return_global');
        }
    }
}
