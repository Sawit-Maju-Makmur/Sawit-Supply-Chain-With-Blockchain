@props(['title' => null])

@extends('layouts.app')

@section('title', $title ?? 'SIPS Sawit')

@section('content')
    {{ $slot }}
@endsection
