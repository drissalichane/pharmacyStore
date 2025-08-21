@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Upload Pharmacy Info Image</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.pharmacy-image.upload') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pharmacy_image" accept="image/*" required class="mb-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
    </form>
</div>
@endsection
