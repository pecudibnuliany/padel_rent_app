<nav class="flex flex-wrap items-center justify-between p-3 bg-[#e8e8e5]">
    <div class="flex items-center">
        <img src="/assets/img/futsal.png" alt="Logo" class="h-10 mr-3">
        <div class="text-xl">Cipinang Indah Padel Club</div>
    </div>
    <div class="flex md:hidden">
        <button id="hamburger">
            <img class="toggle block" src="https://img.icons8.com/fluent-systems-regular/2x/menu-squared-2.png" width="40" height="40" />
            <img class="toggle hidden" src="https://img.icons8.com/fluent-systems-regular/2x/close-window.png" width="40" height="40" />
        </button>
    </div>
    <div class="toggle hidden w-full md:w-auto md:flex text-right text-bold mt-5 md:mt-0 md:border-none">
        <a href="{{ url('/') }}#beranda" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Beranda</a>
        <a href="{{ url('/') }}#aboutus" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Tentang Kami</a>
        <a href="{{ url('/') }}#fields" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Lapangan</a>
        <a href="{{ url('/') }}#layanan" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Layanan</a>
        <a href="{{ url('/') }}#contactUs" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Lokasi</a>
        @auth
        <a href="{{ route('user.administration.index') }}" class="block md:inline-block hover:text-blue-500 px-3 py-3 md:border-none">Administrasi</a>
        @endauth
    </div>

    <div class="toggle w-full text-end hidden md:flex md:w-auto px-2 py-2 md:rounded">
        @auth
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Hello, {{ Auth::user()->name }}!</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center h-10 w-30 rounded-md bg-blue-500 hover:bg-blue-700 text-white font-medium p-2">
                        Log Out
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="flex items-center h-10 w-30 rounded-md bg-blue-500 hover:bg-blue-700 text-white font-medium p-2">
                Log In
            </a>
        @endauth
    </div>
</nav>
