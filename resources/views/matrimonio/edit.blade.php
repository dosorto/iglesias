@extends('layouts.app')

@section('title', 'Editar Matrimonio')

@section('content')
    <livewire:matrimonio.matrimonio-edit :matrimonio="$matrimonio" />
@endsection
