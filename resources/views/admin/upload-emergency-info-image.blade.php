@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Upload Emergency Info Image</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.emergency-info-image.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Image</label>
            <input type="file" name="emergency_info_image" class="border p-2 w-full" required>
            @error('emergency_info_image')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Title (optional)</label>
            <input type="text" name="title" class="border p-2 w-full">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Description (optional)</label>
            <textarea name="description" class="border p-2 w-full"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
    </form>
</div>
@endsection
