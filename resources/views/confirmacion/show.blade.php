@extends('layouts.app')
@section('content')
    @livewire('confirmacion.confirmacion-show', ['confirmacion' => $confirmacion])
@endsection