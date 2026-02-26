<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Models\Feligres;

class InstructorController extends Controller
{
    public function index()
    {
        return view('instructor.index');
    }

    public function create()
    {
        // Solo feligreses que aún no son instructores
        $feligres = Feligres::with('persona')
            ->whereDoesntHave('instructor')
            ->orderBy('id')
            ->get();

        return view('instructor.create', compact('feligres'));
    }

    public function store(StoreInstructorRequest $request)
    {
        Instructor::create($request->validated());

        return redirect()->route('instructor.index')
            ->with('success', 'Instructor registrado exitosamente.');
    }

    public function show(Instructor $instructor)
    {
        $instructor->load([
            'feligres.persona',
            'feligres.iglesia',
            'auditLogs'
        ]);

        return view('instructor.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        // Feligreses sin instructor o el asignado actualmente
        $feligres = Feligres::with('persona')
            ->where(function ($q) use ($instructor) {
                $q->whereDoesntHave('instructor')
                  ->orWhere('id', $instructor->feligres_id);
            })
            ->orderBy('id')
            ->get();

        return view('instructor.edit', compact('instructor', 'feligres'));
    }

    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        $instructor->update($request->validated());

        return redirect()->route('instructor.index')
            ->with('success', 'Instructor actualizado exitosamente.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();

        return redirect()->route('instructor.index')
            ->with('success', 'Instructor eliminado exitosamente.');
    }
}