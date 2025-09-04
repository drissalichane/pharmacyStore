@extends('layouts.app')

@section('title', 'Settings - Admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h1 class="ml-2 text-2xl font-medium text-gray-900">Settings</h1>
                </div>
            </div>

            <div class="bg-white p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- General Settings -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">General Settings</h2>
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                            <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? 'PharmaCare' }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">The name displayed in the site header and browser title</p>
                        </div>

                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                            <textarea name="site_description" id="site_description" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $settings['site_description'] ?? '' }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">A brief description of your pharmacy (optional)</p>
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Logo Settings -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Logo Settings</h2>

                    <!-- Current Logo Display -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Current Logo" class="h-16 w-auto border border-gray-300 rounded">
                            <div>
                                <p class="text-sm text-gray-500">This logo will be displayed in the navigation bar</p>
                                @if(\App\Models\Setting::get('site_logo'))
                                    <form action="{{ route('admin.settings.remove-logo') }}" method="POST" class="mt-2 inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                                onclick="return confirm('Are you sure you want to remove the current logo?')">
                                            Remove Logo
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Logo Upload Form -->
                    <form action="{{ route('admin.settings.upload-logo') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Upload New Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">Supported formats: JPEG, PNG, GIF, SVG. Maximum size: 2MB</p>
                        </div>

                        <div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Upload Logo
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Default Logo Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Default Logo</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        If no custom logo is uploaded, the system will use "PharmaCare" as the default text logo.
                    </p>
                    <p class="text-sm text-gray-600">
                        The default logo file should be placed at <code class="bg-gray-200 px-1 rounded">public/images/pharmacare-logo.png</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
