<!-- resources/views/users/index.blade.php -->

@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <p class="text-gray-600 mt-1">Manage all users in the system</p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i>Add New User
        </a>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->empid }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-user mr-2 text-gray-400"></i>{{ $user->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->firstname }} {{ $user->lastname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email_corp ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->active == 'y')
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
                                <a href="{{ route('users.edit', $user->iduser) }}" 
                                   class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('users.destroy', $user->iduser) }}" 
                                      onsubmit="return confirm('Are you sure?')" class="inline">
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
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $users->links() }}
        </div>
    </div>
    
</div>
@endsection