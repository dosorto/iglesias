<?php

namespace App\Http\Controllers;

use App\Models\Confirmacion;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use Barryvdh\DomPDF\Facade\Pdf;

class ConfirmacionController extends Controller
{
    public function index()
    {
        return view('confirmacion.index');
    }

    public function create()
    {
        return view('confirmacion.create');
    }

    public function show(Confirmacion $confirmacion)
    {
        return view('confirmacion.show', compact('confirmacion'));
    }

    public function edit(Confirmacion $confirmacion)
    {
        return view('confirmacion.edit', compact('confirmacion'));
    }

    public function certificadoPdf(Confirmacion $confirmacion)
    {
        $confirmacion->load([
            'iglesia',
            'feligres.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'ministro.persona',
            'encargado.feligres.persona',
        ]);

        $iglesiaConfig = TenantIglesia::current();

        if (! $iglesiaConfig) {
            $iglesiaId     = session('tenant.id_iglesia');
            $iglesiaConfig = $iglesiaId ? Iglesias::find($iglesiaId) : null;
        }

        $pdf = Pdf::loadView(
            'confirmacion.certificado-pdf',
            compact('confirmacion', 'iglesiaConfig')
        )->setPaper('letter', 'portrait');

        return $pdf->stream('certificado-confirmacion-' . $confirmacion->id . '.pdf');
    }
}