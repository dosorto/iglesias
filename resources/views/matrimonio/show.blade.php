@extends('layouts.app')

@section('title', 'Detalle de Matrimonio')

@section('content')
    <livewire:matrimonio.matrimonio-show :matrimonio="$matrimonio" />
@endsection
