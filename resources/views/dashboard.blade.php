<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Work - ระบบลางาน</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        .radio-option {
            transition: all 0.3s ease;
        }

        .radio-option input[type="radio"]:checked+.radio-content {
            background: #3B82F6;
            border-color: #3B82F6;
            color: white;
            transform: scale(1.02);
            box-shadow: 0 8px 25px -8px rgba(59, 130, 246, 0.5);
        }

        .radio-option input[type="radio"]:checked+.radio-content .radio-indicator {
            border-color: white;
            background-color: white;
        }

        .radio-option input[type="radio"]:checked+.radio-content .radio-dot {
            opacity: 1;
            transform: scale(1);
            background-color: #3B82F6;
        }

        .radio-option input[type="radio"]:checked+.radio-content .radio-text {
            color: white;
        }

        .radio-option input[type="radio"]:checked+.radio-content .radio-description {
            color: rgba(255, 255, 255, 0.8);
        }

        .radio-dot {
            transition: all 0.2s ease;
            transform: scale(0);
        }

        /* File Upload Styles */
        .file-upload-area {
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: #3B82F6;
            background-color: rgba(59, 130, 246, 0.05);
        }

        .file-preview {
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        /* Loading Animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Smooth Animations */
        * {
            transition: all 0.2s ease;
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-6">
        <header
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div class="flex items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white-600">
                            MY WORK
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center space-x-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span>ออกจากระบบ</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งคำขอลางานเรียบร้อย!',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#10B981',
                        timer: 5000,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        <!-- สถิติการลางาน -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 mb-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    ปี {{ thaidate('Y') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- วันลาทั้งหมด -->
                <div
                    class="group relative overflow-hidden bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-300 mb-2">
                                {{ $leaveStats['total_days'] }}
                            </div>
                            <div class="text-sm font-medium text-blue-700 dark:text-blue-200">คุณได้ลางานไปแล้วทั้งหมด
                            </div>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500"></div>
                </div>

                <!-- ลากิจ -->
                <div
                    class="group relative overflow-hidden bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-300 mb-2">
                                {{ $leaveStats['personal_leave'] }}
                            </div>
                            <div class="text-sm font-medium text-yellow-700 dark:text-yellow-200">ลากิจ</div>
                        </div>
                        <div
                            class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-yellow-500"></div>
                </div>

                <!-- ลาป่วย -->
                <div
                    class="group relative overflow-hidden bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-red-600 dark:text-red-300 mb-2">
                                {{ $leaveStats['sick_leave'] }}
                            </div>
                            <div class="text-sm font-medium text-red-700 dark:text-red-200">ลาป่วย</div>
                        </div>
                        <div
                            class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-red-500"></div>
                </div>
            </div>

            {{-- <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">การใช้วันลาในปีนี้</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $leaveStats['total_days'] }}/30 วัน</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ min(($leaveStats['total_days'] / 30) * 100, 100) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span>เหลือ {{ max(30 - $leaveStats['total_days'], 0) }} วัน</span>
                    <span>{{ number_format(($leaveStats['total_days'] / 30) * 100, 1) }}% ใช้ไปแล้ว</span>
                </div>
            </div> --}}

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('leave-history') }}"
                    class="flex-1 inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    ดูประวัติการลางาน
                </a>
                {{-- <button class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    ดาวน์โหลดรายงาน
                </button> --}}
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">ฟอร์มขอลางาน</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">กรอกข้อมูลการลางานของคุณ</p>
                </div>
            </div>

            <form method="POST" action="{{ route('leave-request.store') }}" enctype="multipart/form-data"
                class="space-y-8" id="leaveForm">
                @csrf

                <!-- ประเภทการลางาน -->
                <div class="space-y-2">
                    <label for="leave_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            ประเภทการลางาน
                            <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <select name="leave_type" id="leave_type" required
                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300 hover:border-blue-300">
                        <option value="">เลือกประเภทการลางาน</option>
                        <option value="ลากิจ" {{ old('leave_type') == 'ลากิจ' ? 'selected' : '' }}>🏠 ลากิจ (Personal
                            Leave)</option>
                        <option value="ลาป่วย" {{ old('leave_type') == 'ลาป่วย' ? 'selected' : '' }}>🏥 ลาป่วย (Sick
                            Leave)</option>
                    </select>
                    @error('leave_type')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- ประเภทระยะเวลา -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ประเภทระยะเวลา
                            <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="radio-option">
                            <input type="radio" name="duration_type" value="ทั้งวัน" id="full_day"
                                {{ old('duration_type') == 'ทั้งวัน' ? 'checked' : '' }} class="sr-only" required>
                            <label for="full_day"
                                class="radio-content cursor-pointer flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 transition-all duration-300">
                                <div
                                    class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <div class="radio-dot w-2 h-2 bg-blue-500 rounded-full opacity-0"></div>
                                </div>
                                <div>
                                    <span class="radio-text font-medium block">ทั้งวัน</span>
                                    <p class="radio-description text-sm text-gray-500 dark:text-gray-400">ลาตลอดวัน</p>
                                </div>
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="duration_type" value="ชั่วโมง" id="hourly"
                                {{ old('duration_type') == 'ชั่วโมง' ? 'checked' : '' }} class="sr-only" required>
                            <label for="hourly"
                                class="radio-content cursor-pointer flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 transition-all duration-300">
                                <div
                                    class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <div class="radio-dot w-2 h-2 bg-blue-500 rounded-full opacity-0"></div>
                                </div>
                                <div>
                                    <span class="radio-text font-medium block">ชั่วโมง</span>
                                    <p class="radio-description text-sm text-gray-500 dark:text-gray-400">
                                        ระบุเวลาเริ่มต้น-สิ้นสุด</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('duration_type')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- วันที่ต้องการลา -->
                <div class="space-y-2">
                    <label for="leave_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            วันที่ต้องการลา
                            <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <input type="date" name="leave_date" id="leave_date" value="{{ old('leave_date') }}"
                        required min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300 hover:border-blue-300">
                    @error('leave_date')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- เวลา (แสดงเมื่อเลือกชั่วโมง) -->
                <div id="time_fields"
                    class="hidden space-y-4 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-2 border-blue-200 dark:border-blue-700">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-blue-700 dark:text-blue-300">ระบุช่วงเวลาที่ต้องการลา</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time"
                                class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">เวลาเริ่มต้น</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300">
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time"
                                class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">เวลาสิ้นสุด</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ข้อมูลเพิ่มเติม -->
                <div class="space-y-2">
                    <label for="additional_info" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            ข้อมูลเพิ่มเติม
                            <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <textarea name="additional_info" id="additional_info" rows="4" required
                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300 hover:border-blue-300"
                        placeholder="ระบุเหตุผลหรือข้อมูลเพิ่มเติม เช่น สาเหตุการลา, การติดต่อฉุกเฉิน...">{{ old('additional_info') }}</textarea>
                    @error('additional_info')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- ไฟล์แนบ -->
                <div class="space-y-2">
                    <label for="attachment" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            ไฟล์แนบ
                        </span>
                    </label>

                    <!-- File Upload Area -->
                    <div class="file-upload-area border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center transition-all duration-300"
                        id="fileUploadArea">
                        <input type="file" name="attachment" id="attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="hidden">
                        <label for="attachment" class="cursor-pointer" id="fileUploadLabel">
                            <div class="space-y-2" id="uploadPlaceholder">
                                <svg class="w-8 h-8 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-blue-600">คลิกเพื่อเลือกไฟล์</span>
                                    หรือลากไฟล์มาวางที่นี่
                                </p>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC, DOCX (ขนาดไม่เกิน 2MB)</p>
                            </div>
                        </label>

                        <!-- File Preview -->
                        <div class="file-preview" id="filePreview">
                            <div
                                class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center"
                                        id="fileIcon">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-700 dark:text-gray-300" id="fileName">
                                            ไฟล์ที่เลือก</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400" id="fileSize">0 KB</p>
                                    </div>
                                </div>
                                <button type="button" class="text-red-500 hover:text-red-700 transition-colors"
                                    id="removeFile">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('attachment')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- ปุ่มส่งฟอร์ม -->
                <div class="pt-6">
                    <button type="button" id="submitBtn"
                        class="w-full bg-blue-600 text-white py-4 px-8 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        ส่งคำขอลางาน
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fullDayRadio = document.getElementById('full_day');
            const hourlyRadio = document.getElementById('hourly');
            const timeFields = document.getElementById('time_fields');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const submitBtn = document.getElementById('submitBtn');
            const leaveForm = document.getElementById('leaveForm');

            const fileInput = document.getElementById('attachment');
            const fileUploadArea = document.getElementById('fileUploadArea');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const fileIcon = document.getElementById('fileIcon');
            const removeFileBtn = document.getElementById('removeFile');

            function toggleTimeFields() {
                if (hourlyRadio.checked) {
                    timeFields.classList.remove('hidden');
                    startTimeInput.required = true;
                    endTimeInput.required = true;
                } else {
                    timeFields.classList.add('hidden');
                    startTimeInput.required = false;
                    endTimeInput.required = false;
                    startTimeInput.value = '';
                    endTimeInput.value = '';
                }
            }

            fullDayRadio.addEventListener('change', toggleTimeFields);
            hourlyRadio.addEventListener('change', toggleTimeFields);
            toggleTimeFields();

            // File upload functionality
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function getFileIcon(fileType) {
                if (fileType.includes('image')) {
                    return `<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>`;
                } else if (fileType.includes('pdf')) {
                    return `<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>`;
                } else if (fileType.includes('word') || fileType.includes('document')) {
                    return `<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>`;
                } else {
                    return `<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>`;
                }
            }

            function showFilePreview(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileIcon.innerHTML = getFileIcon(file.type);

                uploadPlaceholder.style.display = 'none';
                filePreview.classList.add('show');
                fileUploadArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
                fileUploadArea.classList.remove('border-gray-300', 'dark:border-gray-600');
            }

            function hideFilePreview() {
                uploadPlaceholder.style.display = 'block';
                filePreview.classList.remove('show');
                fileUploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
                fileUploadArea.classList.add('border-gray-300', 'dark:border-gray-600');
            }

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showFilePreview(file);
                } else {
                    hideFilePreview();
                }
            });

            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                hideFilePreview();
            });

            // Drag and drop functionality
            fileUploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
            });

            fileUploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                if (!fileInput.files[0]) {
                    fileUploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
                }
            });

            fileUploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    showFilePreview(files[0]);
                }
            });

            // Form submission
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const leaveType = document.getElementById('leave_type').value;
                const durationType = document.querySelector('input[name="duration_type"]:checked');
                const leaveDate = document.getElementById('leave_date').value;
                const additionalInfo = document.getElementById('additional_info').value;

                if (!leaveType || !durationType || !leaveDate || !additionalInfo.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        text: 'โปรดตรวจสอบและกรอกข้อมูลที่จำเป็นให้ครบถ้วน',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                if (durationType.value === 'ชั่วโมง') {
                    const startTime = document.getElementById('start_time').value;
                    const endTime = document.getElementById('end_time').value;

                    if (!startTime || !endTime) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาระบุเวลา',
                            text: 'โปรดระบุเวลาเริ่มต้นและเวลาสิ้นสุด',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#3B82F6'
                        });
                        return;
                    }
                }

                const attachmentInfo = fileInput.files[0] ?
                    `<p><strong>ไฟล์แนบ:</strong> ${fileInput.files[0].name}</p>` : '';

                Swal.fire({
                    icon: 'question',
                    title: 'ยืนยันการส่งคำขอลางาน',
                    html: `
                        <div class="text-left space-y-2">
                            <p><strong>ประเภทการลา:</strong> ${leaveType}</p>
                            <p><strong>ประเภทระยะเวลา:</strong> ${durationType.value}</p>
                            <p><strong>วันที่ลา:</strong> ${new Date(leaveDate).toLocaleDateString('th-TH')}</p>
                            ${durationType.value === 'ชั่วโมง' ? 
                                `<p><strong>เวลา:</strong> ${document.getElementById('start_time').value} - ${document.getElementById('end_time').value}</p>` : 
                                ''
                            }
                            <p><strong>ข้อมูลเพิ่มเติม:</strong> ${additionalInfo}</p>
                            ${attachmentInfo}
                        </div>
                        <hr class="my-3">
                        <p class="text-sm text-gray-600">คุณต้องการส่งคำขอลางานนี้หรือไม่?</p>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยันส่งคำขอ',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#EF4444',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'กำลังส่งคำขอลางาน...',
                            text: 'โปรดรอสักครู่',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        leaveForm.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
