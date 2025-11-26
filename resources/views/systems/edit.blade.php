<!-- resources/views/systems/edit.blade.php -->

@extends('layouts.app')

@section('title', 'Edit System')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('systems.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Systems
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit System</h1>
        <p class="text-gray-600 mt-1">Update system information</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('systems.update', $system->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- System Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    System Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="system_code" value="{{ old('system_code', $system->system_code) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('system_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- System Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    System Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="system_name" value="{{ old('system_name', $system->system_name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('system_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- System URL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    System URL <span class="text-red-500">*</span>
                </label>
                <input type="url" name="system_url" value="{{ old('system_url', $system->system_url) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('system_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $system->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="is_active" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="1" {{ old('is_active', $system->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $system->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- API Key Display -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current API Key
                </label>
                <div class="flex items-center space-x-2">
                    <input type="text" value="{{ $system->api_key }}" readonly
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm">
                    <button type="button" onclick="copyApiKey()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Use "Regen Key" button on the main page to generate a new API key</p>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('systems.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Update System
                </button>
            </div>
        </form>
    </div>
    
</div>

@push('scripts')
<script>
function copyApiKey() {
    const input = document.querySelector('input[readonly]');
    input.select();
    document.execCommand('copy');
    alert('API Key copied to clipboard!');
}
</script>
@endpush
@endsection