<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIglesiaRequest;
use App\Http\Requests\UpdateIglesiaRequest;
use App\Models\Iglesias;
use App\Models\Religion;
use App\Services\Tenancy\TenantProvisioner;

class IglesiaController extends Controller
{
    public function index()
    {
        return view('iglesias.index');
    }

    public function create()
    {
        $religiones = Religion::orderBy('religion')->get();
        return view('iglesias.create', compact('religiones'));
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
        return view('iglesias.show', compact('iglesia'));
    }

    public function edit(Iglesias $iglesia)
    {
        $religiones = Religion::orderBy('religion')->get();
        return view('iglesias.edit', compact('iglesia', 'religiones'));
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

    public function destroy(Iglesias $iglesia)
    {
        $iglesia->delete();
        return redirect()->route('iglesias.index')
            ->with('success', 'Iglesia eliminada exitosamente.');
    }
}