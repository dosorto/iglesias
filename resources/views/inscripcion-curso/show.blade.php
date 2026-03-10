@extends('layouts.app')

@section('title', 'Detalle Inscripción Curso')

@section('content')
    <livewire:inscripcion-curso.inscripcion-curso-show :inscripcion="$inscripcionCurso" />
@endsection