<!-- resources/views/roles/permissions.blade.php -->

@extends('layouts.app')

@section('title', 'Manage Permissions')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('roles.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Roles
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Manage Permissions</h1>
        <p class="text-gray-600 mt-1">Assign permissions to: <strong>{{ $role->name }}</strong></p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('roles.permissions.update', $role->id) }}">
            @csrf
            
            <!-- Permissions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @foreach($allPermissions as $permission)
                <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                           {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                           class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</p>
                        <p class="text-sm text-gray-600">{{ $permission->description }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Quick Actions:</p>
                <div class="flex space-x-2">
                    <button type="button" onclick="selectAll()" 
                            class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">
                        <i class="fas fa-check-double mr-1"></i>Select All
                    </button>
                    <button type="button" onclick="deselectAll()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">
                        <i class="fas fa-times mr-1"></i>Deselect All
                    </button>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('roles.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Save Permissions
                </button>
            </div>
        </form>
    </div>
    
</div>

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
}
</script>
@endpush
@endsection