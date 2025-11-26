<!-- resources/views/access/index.blade.php -->

@extends('layouts.app')

@section('title', 'User System Access')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User System Access</h1>
            <p class="text-gray-600 mt-1">Manage user access to systems</p>
        </div>
        <a href="{{ route('access.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Grant Access
        </a>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-4">
        <form method="GET" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by User</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->iduser }}" {{ request('user_id') == $user->iduser ? 'selected' : '' }}>
                        {{ $user->firstname }} {{ $user->lastname }} ({{ $user->username }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by System</label>
                <select name="system_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Systems</option>
                    @foreach($systems as $system)
                    <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                        {{ $system->system_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            
            @if(request('user_id') || request('system_id'))
            <a href="{{ route('access.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                <i class="fas fa-times mr-2"></i>Clear
            </a>
            @endif
        </form>
    </div>
    
    <!-- Access Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">System</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Granted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accesses as $access)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $access->user->firstname }} {{ $access->user->lastname }}</p>
                                    <p class="text-xs text-gray-500">{{ $access->user->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-cube text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-900">{{ $access->system->system_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                {{ $access->role->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $access->granted_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $access->expires_at ? $access->expires_at->format('d M Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($access->is_active)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('access.toggle', $access->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 {{ $access->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('access.revoke', $access->id) }}" 
                                      onsubmit="return confirm('Revoke access?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No access records found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $accesses->links() }}
        </div>
    </div>
    
</div>
@endsection