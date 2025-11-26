<!-- resources/views/systems/create.blade.php -->

@extends('layouts.app')

@section('title', 'Add New System')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('systems.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Systems
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New System</h1>
        <p class="text-gray-600 mt-1">Register a new system to AMS</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('systems.store') }}" class="space-y-6">
            @csrf
            
            <!-- System Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    System Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="system_code" value="{{ old('system_code') }}" required
                       placeholder="e.g. docoline, hrms, erp"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">Lowercase alphanumeric, no spaces</p>
                @error('system_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- System Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    System Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="system_name" value="{{ old('system_name') }}" required
                       placeholder="e.g. Docoline Document Management"
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
                <input type="url" name="system_url" value="{{ old('system_url') }}" required
                       placeholder="https://docoline.company.com"
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
                          placeholder="Describe the purpose of this system..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Note:</p>
                        <p>API key will be automatically generated when you create this system. Make sure to save it securely.</p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('systems.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Create System
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection