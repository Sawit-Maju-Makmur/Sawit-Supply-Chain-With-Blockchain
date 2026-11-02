@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Data Lahan</h1>
<form action="{{ route('lahan.update', $lahan) }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf @method('PUT')
    @include('lahan._form', ['lahan' => $lahan])
</form>
@endsection