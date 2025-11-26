<!-- resources/views/layouts/sidebar.blade.php -->

<div x-data="{ open: true }" @toggle-sidebar.window="open = !open" class="relative">
    
    <!-- Overlay untuk Mobile -->
    <div x-show="open" 
         @click="open = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-10 lg:hidden"
         x-cloak>
    </div>
    
    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg z-20 transition-transform duration-300 ease-in-out">
        
        <!-- Toggle Button Desktop (Sticky) -->
        <button @click="open = !open"
                class="hidden lg:flex absolute -right-3 top-4 w-6 h-6 bg-indigo-600 text-white rounded-full items-center justify-center hover:bg-indigo-700 shadow-lg z-30">
            <i :class="open ? 'fa-chevron-left' : 'fa-chevron-right'" class="fas text-xs"></i>
        </button>
        
        <div class="p-4 h-full overflow-y-auto">
            <nav class="space-y-1">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium" x-show="open">Dashboard</span>
                </a>
                
                <!-- User Management -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('users*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium" x-show="open">Users</span>
                </a>
                
                <!-- System Management -->
                <a href="{{ route('systems.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('systems*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-server w-5"></i>
                    <span class="font-medium" x-show="open">Systems</span>
                </a>
                
                <!-- Role Management -->
                <a href="{{ route('roles.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('roles*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-user-tag w-5"></i>
                    <span class="font-medium" x-show="open">Roles</span>
                </a>
                
                <!-- Access Management -->
                <a href="{{ route('access.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('access*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-key w-5"></i>
                    <span class="font-medium" x-show="open">User Access</span>
                </a>
                
            </nav>
        </div>
    </aside>
</div>