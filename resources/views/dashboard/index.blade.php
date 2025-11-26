@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ $user->firstname }}!</p>
        </div>
        <div class="text-right text-sm text-gray-600">
            <i class="fas fa-clock mr-2"></i>
            {{ now()->format('d M Y, H:i') }}
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Systems</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_systems'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-server text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Active Sessions</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['active_sessions'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-signal text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Today's Logins</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['today_logins'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-key text-indigo-600 mr-2"></i>
                Your System Access
            </h2>
            
            @if($userSystems->count() > 0)
            <div class="space-y-3">
                @foreach($userSystems as $access)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cube text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $access->system->system_name }}</p>
                            <p class="text-sm text-gray-600">Role: {{ $access->role->name }}</p>
                        </div>
                    </div>
                    <a href="{{ $access->system->system_url }}" target="_blank"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                        Open <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-600 text-center py-4">No system access granted yet</p>
            @endif
        </div>
        
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-indigo-600 mr-2"></i>
                Recent Activity
            </h2>
            
            @if($recentActivity->count() > 0)
            <div class="space-y-3">
                @foreach($recentActivity as $log)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 {{ $log->status == 'success' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $log->status == 'success' ? 'fa-check text-green-600' : 'fa-times text-red-600' }} text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ str_replace('_', ' ', ucwords($log->action)) }}</p>
                        <p class="text-xs text-gray-600">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-600 text-center py-4">No recent activity</p>
            @endif
        </div>
    </div>
    
</div>