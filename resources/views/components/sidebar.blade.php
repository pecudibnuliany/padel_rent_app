<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="relative bg-sidebar w-64 hidden sm:block shadow-xl flex-shrink-0">
        <div class="p-6">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard')}}" 
                class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>

            <!-- Tabel User -->
            <a href="{{ route('admin.users.index') }}" 
                class="flex items-center {{ request()->routeIs('admin.users.index') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-users mr-3"></i>
                Tabel User
            </a>

            <!-- Tabel Lapangan -->
            <a href="{{ route('admin.fields.index') }}" 
                class="flex items-center {{ request()->routeIs('admin.fields.index') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-futbol mr-3"></i>
                Tabel Lapangan
            </a>

            <!-- Tabel Jadwal -->
            <a href="{{ route('admin.schedules.index') }}" 
                class="flex items-center {{ request()->routeIs('admin.schedules.index') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-calendar-alt mr-3"></i>
                Tabel Jadwal
            </a>

            <!-- Tabel Booking -->
            <a href="{{ route('admin.bookings.index') }}" 
                class="flex items-center {{ request()->routeIs('admin.bookings.index') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-book mr-3"></i>
                Tabel Booking
            </a>

            <!-- Tabel Pembayaran (Payment) -->
            <a href="{{ route('admin.payments.index') }}" 
                class="flex items-center {{ request()->routeIs('admin.payments.index') ? 'active-nav-link' : 'opacity-75 hover:opacity-100' }} py-4 pl-6 nav-item">
                <i class="fas fa-credit-card mr-3"></i>
                Tabel Pembayaran
            </a>

            <!-- Log Out -->
            <form method="POST" action="{{ route('logout') }}" class="absolute w-full bottom-0">
                @csrf
                <button type="submit" class="upgrade-btn w-full active-nav-link text-white flex items-center justify-center py-4 bg-red-600 hover:bg-red-700">
                    <i class="fas fa-arrow-circle-up mr-3"></i>
                    Log Out
                </button>
            </form>        
        </nav>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-6 bg-gray-100">
        @yield('content') <!-- Konten halaman lainnya -->
    </main>
</div>
