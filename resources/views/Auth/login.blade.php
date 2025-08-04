<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Laravel</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-white mb-4">เข้าสู่ระบบ</h2>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                @if ($errors->any())
                    <div class="mb-4">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-white mb-2">
                            อีเมล
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="กรอกอีเมลของคุณ"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-white mb-2">
                            รหัสผ่าน
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="กรอกรหัสผ่านของคุณ"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium transition"
                    >
                        เข้าสู่ระบบ
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-sm text-[#1b1b18] dark:text-white">
                        ยังไม่มีบัญชี? 
                        <a href="{{ route('register') }}" class="text-blue-600 underline hover:text-blue-700">
                            สมัครสมาชิก
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ url('/') }}" class="text-blue-600 underline hover:text-blue-700">
                    กลับหน้าหลัก
                </a>
            </div>
        </div>
    </body>
</html>
