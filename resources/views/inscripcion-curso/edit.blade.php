@extends('layouts.app')

@section('title', 'Editar Inscripción Curso')

@section('content')
    <livewire:inscripcion-curso.inscripcion-curso-edit :inscripcion="$inscripcionCurso" />
@endsection