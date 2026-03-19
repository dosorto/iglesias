<?php

namespace App\Http\Controllers;

use App\Models\Matrimonio;
use App\Models\TenantIglesia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MatrimonioController extends Controller
{
    public function index()
    {
        return view('matrimonio.index');
    }

    public function create()
    {
        return view('matrimonio.create');
    }

    public function show(Matrimonio $matrimonio)
    {
        return view('matrimonio.show', compact('matrimonio'));
    }

    public function edit(Matrimonio $matrimonio)
    {
        return view('matrimonio.edit', compact('matrimonio'));
    }

    public function certificadoPdf(Matrimonio $matrimonio)
    {
        $matrimonio->load([
            'iglesia',
            'esposo.persona',
            'esposa.persona',
            'testigo1.persona',
            'testigo2.persona',
            'encargado.feligres.persona',
        ]);

        $iglesiaConfig = TenantIglesia::current();

        $pdf = Pdf::loadView('matrimonio.certificado-pdf', compact('matrimonio', 'iglesiaConfig'))
            ->setPaper('letter', 'portrait');

        $nombreArchivo = 'constancia-matrimonio-' . $matrimonio->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
