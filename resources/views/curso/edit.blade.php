@extends('layouts.app')

@section('title', 'Editar Curso')

@section('content')
    <livewire:curso.curso-edit :curso="$curso" />
@endsection