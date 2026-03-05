<?php

namespace App\Http\Controllers;

use App\Models\Instructor;

class InstructorController extends Controller
{
    public function index()
    {
        return view('instructor.index');
    }

    public function create()
    {
        return view('instructor.create');
    }

    public function show(Instructor $instructor)
    {
        return view('instructor.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        return view('instructor.edit', compact('instructor'));
    }
}