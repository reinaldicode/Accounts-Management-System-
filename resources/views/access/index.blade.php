<!-- resources/views/access/index.blade.php -->

@extends('layouts.app')

@section('title', 'User System Access')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header dengan SSO Info -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">User System Access</h1>
                <p class="mt-2 text-purple-100">
                    <i class="fas fa-info-circle mr-2"></i>
                    Manage user access and permissions across all integrated systems
                </p>
            </div>
            <a href="{{ route('access.create') }}" 
               class="px-6 py-3 bg-white text-purple-600 rounded-lg hover:bg-purple-50 transition font-medium">
                <i class="fas fa-plus mr-2"></i>Grant New Access
            </a>
        </div>
    </div>
    
    <!-- Filters dengan SSO Status -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1"></i>Filter by User
                </label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->iduser }}" {{ request('user_id') == $user->iduser ? 'selected' : '' }}>
                        {{ $user->firstname }} {{ $user->lastname }} ({{ $user->username }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-cube mr-1"></i>Filter by System
                </label>
                <select name="system_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Systems</option>
                    @foreach($systems as $system)
                    <option value="{{ $system->id }}" {{ request('system_id') == $system->id ? 'selected' : '' }}>
                        {{ $system->system_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-1"></i>Status
                </label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                @if(request()->hasAny(['user_id', 'system_id', 'status']))
                <a href="{{ route('access.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Access Table dengan SSO Info -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">System</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">SSO Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Granted</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accesses as $access)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- User Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $access->user->firstname }} {{ $access->user->lastname }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $access->user->username }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <!-- System -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-cube text-gray-400 mr-2"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $access->system->system_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $access->system->system_code }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Role -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-user-tag mr-1"></i>
                                {{ $access->role->name }}
                            </span>
                        </td>
                        
                        <!-- SSO Status (NEW!) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $hasActiveSession = \App\Helpers\JWTHelper::hasActiveSession($access->user_id, $access->system_id);
                            @endphp
                            
                            @if($hasActiveSession)
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-wifi mr-1"></i>Connected
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                <i class="fas fa-minus-circle mr-1"></i>Not Connected
                            </span>
                            @endif
                        </td>
                        
                        <!-- Granted Date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600">{{ $access->granted_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $access->granted_at->format('H:i') }}</p>
                        </td>
                        
                        <!-- Status -->
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
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <!-- Toggle Active/Inactive -->
                                <form method="POST" action="{{ route('access.toggle', $access->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 {{ $access->is_active ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                
                                <!-- Revoke Access -->
                                <form method="POST" action="{{ route('access.revoke', $access->id) }}" 
                                      onsubmit="return confirm('Revoke access? This will remove all permissions.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                            <p class="text-lg">No access records found</p>
                            <a href="{{ route('access.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                Grant your first access →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($accesses->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $accesses->links() }}
        </div>
        @endif
    </div>
    
</div>
@endsection