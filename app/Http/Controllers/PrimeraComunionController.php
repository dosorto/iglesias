<?php

namespace App\Http\Controllers;

use App\Models\PrimeraComunion;
use App\Models\Encargado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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

        // Encargado activo de la iglesia (para su firma en el PDF)
        $encargado = Encargado::whereHas('feligres', function ($q) use ($primeraComunion) {
                $q->where('id_iglesia', $primeraComunion->id_iglesia);
            })
            ->where('estado', 'Activo')
            ->whereNull('deleted_at')
            ->latest()
            ->first();

        $pdf = Pdf::loadView('primera-comunion.certificado-pdf', compact('primeraComunion', 'encargado'))
            ->setPaper('letter', 'portrait');

        $nombreArchivo = 'certificado-primera-comunion-' . $primeraComunion->id . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}