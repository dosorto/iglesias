<?php

namespace App\Http\Controllers;

use App\Models\PrimeraComunion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrimeraComunionController extends Controller
{
    public function index()
    {
        return view('primera-comunion.index');
    }

    public function create()
    {
        return view('primera-comunion.create');
    }

    public function show(PrimeraComunion $primeraComunion)
    {

    $primeraComunion->load([
        'iglesia',
        'feligres.persona',
        'catequista.persona',
        'ministro.persona',
        'parroco.persona',
    ]);
        return view('primera-comunion.show', compact('primeraComunion'));
    }


    public function edit(PrimeraComunion $primeraComunion)
    {
        return view('primera-comunion.edit', compact('primeraComunion'));
    }

    public function certificadoPdf(PrimeraComunion $primeraComunion)
    {
        $primeraComunion->load([
            'iglesia',
            'feligres.persona',
            'catequista.persona',
            'ministro.persona',
            'parroco.persona',
        ]);

        $pdf = Pdf::loadView('primera-comunion.certificado-pdf', compact('primeraComunion'))
            ->setPaper('letter', 'portrait');

        $nombreArchivo = 'certificado-primera-comunion-' . $primeraComunion->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}