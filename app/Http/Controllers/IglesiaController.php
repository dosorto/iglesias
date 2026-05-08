<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIglesiaRequest;
use App\Http\Requests\UpdateIglesiaConfiguracionRequest;
use App\Http\Requests\UpdateIglesiaRequest;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\Religion;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class IglesiaController extends Controller
{
    public function index()
    {
        return view('Iglesias.index');
    }

    public function create()
    {
        $religiones = Religion::orderBy('religion')->get();
        return view('Iglesias.create', compact('religiones'));
    }

    public function store(StoreIglesiaRequest $request)
    {
        $iglesia = Iglesias::create([
            'nombre'        => $request->nombre,
            'direccion'     => $request->direccion,
            'telefono'      => $request->telefono,
            'email'         => $request->email,
            'parroco_nombre'=> $request->parroco_nombre,
            'estado'        => $request->estado,
            'id_religion'   => $request->id_religion ?: null,
        ]);

        $iglesia->update([
            'subdomain' => Iglesias::resolveUniqueSubdomainForName($iglesia->nombre, $iglesia->id),
        ]);

        try {
            $provisioner = app(TenantProvisioner::class);
            $tenant = $provisioner->provisionDatabase($iglesia);
            $iglesia->update([
                'db_connection' => $tenant['connection'],
                'db_host'       => $tenant['host'],
                'db_port'       => $tenant['port'],
                'db_database'   => $tenant['database'],
                'db_username'   => $tenant['username'],
                'db_password'   => $tenant['password'],
            ]);
        } catch (\Throwable $e) {
            logger()->error('TenantProvisioner falló para iglesia #' . $iglesia->id, ['error' => $e->getMessage()]);
        }

        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia Creada Exitosamente.');
    }

    public function show(Iglesias $iglesia)
    {
        $iglesia->load('religion');
        return view('Iglesias.show', compact('iglesia'));
    }

    public function edit(Iglesias $iglesia)
    {
        $religiones = Religion::orderBy('religion')->get();
        return view('Iglesias.edit', compact('iglesia', 'religiones'));
    }

    public function editConfiguracion()
    {
        $iglesia = TenantIglesia::current();

        abort_unless($iglesia, 404, 'No se encontró una iglesia activa para configurar.');

        return view('configuracion.iglesia', compact('iglesia'));
    }

    public function update(UpdateIglesiaRequest $request, Iglesias $iglesia)
    {
        $iglesia->update([
            'nombre'        => $request->nombre,
            'direccion'     => $request->direccion,
            'telefono'      => $request->telefono,
            'email'         => $request->email,
            'parroco_nombre'=> $request->parroco_nombre,
            'estado'        => $request->estado,
            'id_religion'   => $request->id_religion ?: null,
        ]);

        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia Actualizada Exitosamente.');
    }

    public function updateConfiguracion(UpdateIglesiaConfiguracionRequest $request)
    {
        $iglesia = TenantIglesia::current();

        abort_unless($iglesia, 404, 'No se encontró una iglesia activa para configurar.');

        $data = $request->validated();
        $updates = [
            'nombre' => $data['nombre'],
            'direccion' => $data['direccion'],
        ];

        if (Schema::hasColumn('iglesias', 'header_diocesis')) {
            $updates['header_diocesis'] = $data['header_diocesis'] ?? null;
        }

        if (Schema::hasColumn('iglesias', 'header_lugar')) {
            // El lugar del encabezado siempre replica la dirección.
            $updates['header_lugar'] = $data['direccion'] ?? null;
        }

        $iglesia->update($updates);

        return redirect()->route('configuracion.iglesia.edit')
            ->with('success', 'Configuración de la iglesia actualizada exitosamente.');
    }

    public function destroy(Iglesias $iglesia)
    {
        $iglesia->delete();
        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia eliminada exitosamente.');
    }

    public function gestionar(Iglesias $iglesia): RedirectResponse
    {
        if (empty($iglesia->db_database)) {
            return redirect()->route('iglesias.index')
                ->with('error', 'La iglesia seleccionada no tiene una base tenant configurada.');
        }

        $tenantEnterUrl = $this->buildTenantUrl($iglesia, '/tenant/enter');

        if ($tenantEnterUrl === null) {
            return redirect()->route('iglesias.index')
                ->with('error', 'La iglesia seleccionada no tiene un subdominio válido configurado.');
        }

        session()->put('tenant', [
            'id_iglesia' => $iglesia->id,
            'connection' => config('tenancy.tenant_connection', 'tenant'),
            'subdomain' => $iglesia->subdomain,
        ]);

        $managerEmail = (string) (Auth::user()?->email ?? '');
        if ($managerEmail !== '') {
            session()->put('tenant_auto_login', [
                'tenant_id' => $iglesia->id,
                'email' => strtolower($managerEmail),
                'initiated_at' => now()->timestamp,
            ]);
        }

        session()->put('tenant_can_return_global', true);

        session()->flash('success', "Ahora estás gestionando: {$iglesia->nombre}.");

        return redirect()->to($tenantEnterUrl);
    }

    public function salirGestion(): RedirectResponse
    {
        if (!session('tenant_can_return_global')) {
            return redirect()->route('dashboard')
                ->with('error', 'No hay una gestión global activa para finalizar.');
        }

        session()->forget('tenant');
        session()->forget('tenant_can_return_global');

        $baseDomain = trim((string) config('tenancy.base_domain', ''));

        if ($baseDomain !== '') {
            $scheme = request()->getScheme() ?: 'https';

            return redirect()->to($scheme . '://' . $baseDomain . '/dashboard')
                ->with('success', 'Regresaste al panel global de iglesias.');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Regresaste al panel global de iglesias.');
    }

    private function buildTenantUrl(Iglesias $iglesia, string $path = '/dashboard'): ?string
    {
        $subdomain = strtolower(trim((string) $iglesia->subdomain));

        if ($subdomain === '') {
            return null;
        }

        $scheme = request()->getScheme() ?: 'https';

        if (str_contains($subdomain, '.')) {
            return $scheme . '://' . $subdomain . $path;
        }

        $baseDomain = trim((string) config('tenancy.base_domain', ''));

        if ($baseDomain === '') {
            return null;
        }

        return $scheme . '://' . $subdomain . '.' . $baseDomain . $path;
    }

}