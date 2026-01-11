<header class="bg-white shadow-md h-16 flex justify-between items-center px-6 z-20">
    <div class="flex items-center">
        <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="text-gray-500 focus:outline-none md:hidden mr-4">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
    </div>

<div class="flex items-center space-x-4">
        <span class="text-gray-600 text-sm">Hi, {{ Auth::user()->name ?? 'User' }}</span>
        
        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
    </div>
</header>