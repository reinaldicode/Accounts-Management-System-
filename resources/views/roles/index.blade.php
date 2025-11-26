<!-- resources/views/roles/index.blade.php -->

@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Role Management</h1>
            <p class="text-gray-600 mt-1">Manage roles and permissions</p>
        </div>
        <a href="{{ route('roles.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Add New Role
        </a>
    </div>
    
    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6">
            <!-- Role Icon -->
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-user-tag text-purple-600 text-xl"></i>
            </div>
            
            <!-- Role Info -->
            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $role->name }}</h3>
            
            @if($role->department || $role->section || $role->title)
            <div class="space-y-1 mb-3 text-sm text-gray-600">
                @if($role->department)
                <p><i class="fas fa-building mr-2 w-4"></i>{{ $role->department }}</p>
                @endif
                @if($role->section)
                <p><i class="fas fa-layer-group mr-2 w-4"></i>{{ $role->section }}</p>
                @endif
                @if($role->title)
                <p><i class="fas fa-briefcase mr-2 w-4"></i>{{ $role->title }}</p>
                @endif
            </div>
            @endif
            
            <p class="text-sm text-gray-600 mb-4">{{ $role->description ?? 'No description' }}</p>
            
            <!-- Permissions Badge -->
            <div class="mb-4">
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                    <i class="fas fa-lock mr-1"></i>{{ $role->permissions->count() }} Permissions
                </span>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('roles.permissions', $role->id) }}" 
                   class="flex-1 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-center text-sm hover:bg-purple-200">
                    <i class="fas fa-key mr-1"></i>Permissions
                </a>
                <a href="{{ route('roles.edit', $role->id) }}" 
                   class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('roles.destroy', $role->id) }}" 
                      onsubmit="return confirm('Are you sure?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p>No roles found</p>
        </div>
        @endforelse
    </div>
    
</div>
@endsection