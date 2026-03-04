@extends('layouts.app')

@section('title', 'Editar Primera Comunión')

@section('content')
    <livewire:primera-comunion.primera-comunion-edit :primeraComunion="$primeraComunion" />
@endsection