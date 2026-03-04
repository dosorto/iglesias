@extends('layouts.app')

@section('title', 'Detalle de Primera Comunión')

@section('content')
    <livewire:primera-comunion.primera-comunion-show :primeraComunion="$primeraComunion" />
@endsection