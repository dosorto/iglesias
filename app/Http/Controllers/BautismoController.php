<?php

namespace App\Http\Controllers;

use App\Models\Bautismo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BautismoController extends Controller
{
    public function index()
    {
        return view('bautismo.index');
    }

    public function create()
    {
        return view('bautismo.create');
    }

    public function show(Bautismo $bautismo)
    {
        return view('bautismo.show', compact('bautismo'));
    }

    public function edit(Bautismo $bautismo)
    {
        return view('bautismo.edit', compact('bautismo'));
    }

    public function certificadoPdf(Bautismo $bautismo)
    {
        $bautismo->load([
            'iglesia',
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'padrino.persona',
            'madrina.persona',
            'encargado.feligres.persona',
        ]);

        $pdf = Pdf::loadView('bautismo.certificado-pdf', compact('bautismo'))
            ->setPaper('letter', 'portrait');

        $nombreArchivo = 'certificado-bautismo-' . $bautismo->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
