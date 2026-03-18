@extends('layouts.app') {{-- o el layout que uses --}}

@section('content')
    @livewire('confirmacion.confirmacion-show', ['confirmacion' => $confirmacion])
@endsection