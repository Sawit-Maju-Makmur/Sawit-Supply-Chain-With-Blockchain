@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Data Lahan</h1>
<form action="{{ route('lahan.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @include('lahan._form', ['lahan' => null])
</form>
@endsection