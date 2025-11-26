<!-- resources/views/roles/create.blade.php -->

@extends('layouts.app')

@section('title', 'Add New Role')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('roles.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Roles
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New Role</h1>
        <p class="text-gray-600 mt-1">Create a new role with permissions</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('roles.store') }}" class="space-y-6">
            @csrf
            
            <!-- Role Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Role Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="e.g. admin, manager, user"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" rows="3"
                          placeholder="Describe the role purpose..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Department -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Department
                </label>
                <input type="text" name="department" value="{{ old('department') }}"
                       placeholder="e.g. IT, Finance, HR"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('department')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Section
                </label>
                <input type="text" name="section" value="{{ old('section') }}"
                       placeholder="e.g. Application Support, Accounting"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('section')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Title/Jabatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Title / Jabatan
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="e.g. Manager, Staff, Supervisor"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('roles.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Create Role
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection