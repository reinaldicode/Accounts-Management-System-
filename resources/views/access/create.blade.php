<!-- resources/views/access/create.blade.php -->

@extends('layouts.app')

@section('title', 'Grant User Access')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ route('access.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Access Management
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Grant User Access</h1>
        <p class="text-gray-600 mt-1">Give a user access to a system</p>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('access.store') }}" class="space-y-6">
            @csrf
            
            <!-- Select User -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select User <span class="text-red-500">*</span>
                </label>
                <select name="user_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">-- Choose User --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->iduser }}" {{ old('user_id') == $user->iduser ? 'selected' : '' }}>
                        {{ $user->firstname }} {{ $user->lastname }} ({{ $user->username }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Select System -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select System <span class="text-red-500">*</span>
                </label>
                <select name="system_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">-- Choose System --</option>
                    @foreach($systems as $system)
                    <option value="{{ $system->id }}" {{ old('system_id') == $system->id ? 'selected' : '' }}>
                        {{ $system->system_name }} ({{ $system->system_code }})
                    </option>
                    @endforeach
                </select>
                @error('system_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Select Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Role <span class="text-red-500">*</span>
                </label>
                <select name="role_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">-- Choose Role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                        @if($role->department || $role->section)
                        - {{ $role->department }} {{ $role->section }}
                        @endif
                    </option>
                    @endforeach
                </select>
                @error('role_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Expiry Date (Optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Access Expires At <span class="text-gray-500 text-xs">(Optional - leave blank for permanent)</span>
                </label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('expires_at')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Note:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>User will be able to access the selected system with the specified role</li>
                            <li>Access will be active immediately after creation</li>
                            <li>You can revoke or toggle access status anytime</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('access.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-key mr-2"></i>Grant Access
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection