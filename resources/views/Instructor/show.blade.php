@extends('layouts.app')

@section('title', 'Detalle de Instructor')

@section('content')
    <livewire:instructor.instructor-show :instructor="$instructor" />
@endsection
