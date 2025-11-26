<!-- resources/views/systems/index.blade.php -->

@extends('layouts.app')

@section('title', 'System Management')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">System Management</h1>
            <p class="text-gray-600 mt-1">Manage all integrated systems</p>
        </div>
        <a href="{{ route('systems.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Add New System
        </a>
    </div>
    
    <!-- Systems Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($systems as $system)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-6">
            <!-- System Icon & Status -->
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cube text-indigo-600 text-xl"></i>
                </div>
                @if($system->is_active)
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>Active
                </span>
                @else
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                    <i class="fas fa-times-circle mr-1"></i>Inactive
                </span>
                @endif
            </div>
            
            <!-- System Info -->
            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $system->system_name }}</h3>
            <p class="text-sm text-gray-600 mb-1">
                <i class="fas fa-code mr-2"></i>{{ $system->system_code }}
            </p>
            <p class="text-sm text-gray-600 mb-4">
                <i class="fas fa-link mr-2"></i>
                <a href="{{ $system->system_url }}" target="_blank" class="text-indigo-600 hover:underline">
                    {{ $system->system_url }}
                </a>
            </p>
            
            <!-- API Key -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 mb-1">API Key:</p>
                <p class="text-xs font-mono text-gray-800 break-all">
                    {{ substr($system->api_key, 0, 30) }}...
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('systems.edit', $system->id) }}" 
                   class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-center text-sm hover:bg-blue-200">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <form method="POST" action="{{ route('systems.regenerate-api-key', $system->id) }}" 
                      onsubmit="return confirm('Regenerate API key? This will invalidate the current key.')"
                      class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full px-3 py-2 bg-orange-100 text-orange-700 rounded-lg text-sm hover:bg-orange-200">
                        <i class="fas fa-sync mr-1"></i>Regen Key
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p>No systems found</p>
        </div>
        @endforelse
    </div>
    
</div>
@endsection