@extends('layouts.app')

@section('title', 'Editar Tipo de Curso')

@section('content')
    <livewire:tipocurso.tipo-curso-edit :tipocurso="$tipocurso" />
@endsection
