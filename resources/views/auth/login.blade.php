<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Padel Reservation</title>
    <link rel="icon" href="/assets/img/futsal.png" type="image/png">
    @vite('resources/css/app.css')
</head>
<body class="bg-blue-500">

    <div class="min-h-screen flex items-center justify-center">
        <!-- Login Container -->
        <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg space-y-6">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Logo -->
            <div class="text-center">
                <img src="/assets/img/futsal.png" alt="Padel Logo" class="w-20 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Login</h1>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <!-- Error message for email -->
                    @if($errors->has('email'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div class="space-y-2 mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <!-- Error message for password -->
                    @if($errors->has('password'))
                        <p class="text-red-600 text-sm mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Ingat Password</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">Login</button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Tidak punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
            </p>
        </div>
    </div>

</body>
</html>
