<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Work - ระบบลางาน</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }

        .radio-option {
            transition: all 0.3s ease;
        }

        .radio-option input[type="radio"]:checked+.radio-content {
            background: #3B82F6;
            border-color: #3B82F6;
            color: white;
            transform: scale(1.02);
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

        /* Theme Toggle Animation */
        #themeToggle {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            z-index: 10;
        }

        #themeToggle svg {
            transition: all 0.3s ease;
            pointer-events: none;
        }

        #themeToggle:active {
            transform: scale(0.95);
        }

        /* Smooth theme transition */
        html {
            transition: color-scheme 0.3s ease;
        }

        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Manual Dark Mode Styles */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #0a0a0a;
            color: white;
        }

        .dark .bg-white {
            background-color: #1f2937 !important;
        }

        .dark .bg-gray-50 {
            background-color: #374151 !important;
        }

        .dark .bg-gray-100 {
            background-color: #4b5563 !important;
        }

        .dark .text-gray-700 {
            color: #d1d5db !important;
        }

        .dark .text-gray-600 {
            color: #9ca3af !important;
        }

        .dark .text-gray-500 {
            color: #6b7280 !important;
        }

        .dark .border-gray-100 {
            border-color: #374151 !important;
        }

        .dark .border-gray-200 {
            border-color: #4b5563 !important;
        }

        .dark .border-gray-600 {
            border-color: #4b5563 !important;
        }

        .dark .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .dark .border-blue-200 {
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        .dark .text-blue-700 {
            color: #93c5fd !important;
        }

        .dark .bg-gray-800 {
            background-color: #1f2937 !important;
        }

        .dark .bg-gray-700 {
            background-color: #374151 !important;
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white min-h-screen">
    <div class="max-w-2xl mx-auto p-6">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-4 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div class="flex items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white-600 mb-5">
                            MY WORK
                        </h1>
                        <h1 class="text-2xl text-center font-normal text-white-600 mb-5">
                            ลางาน
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4 mb-5">
                    <!-- Dark/Light Mode Toggle -->
                    <button id="themeToggle"
                        class="flex items-center justify-center w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                        <svg id="sunIcon" class="w-5 h-5 text-yellow-500 hidden dark:block" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z">
                            </path>
                        </svg>
                        <svg id="moonIcon" class="w-5 h-5 text-gray-600 block dark:hidden" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

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


            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'ส่งคำขอลางานเรียบร้อย!',
                            text: '{{ session('success') }}',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#10B981',
                            timer: 2500,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    ปี {{ thaidate('Y') }}
                </h2>
                
                <!-- ปุ่มสลับการแสดงผล -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button id="normalViewBtn"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm">
                        📊 แบบปกติ
                    </button>
                    <button id="chartViewBtn"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        🥧 Pie Chart
                    </button>
                </div>
            </div>

            <!-- การแสดงแบบปกติ -->
            <div id="normalView" class="stats-container">
                <div class="flex items-center justify-between rounded-lg mb-4">
                    <span class="text-lg font-medium">คุณได้ลางานไปแล้วทั้งหมด</span>
                    <span
                        class="text-xl bg-blue-500 text-white p-2 px-30 rounded-lg font-bold">{{ $leaveStats['total_days'] }}
                        วัน</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center justify-between rounded-lg">
                        <span class="text-lg font-medium">ลากิจ</span>
                        <span
                            class="text-xl text-white bg-purple-500 p-2 px-15 rounded-lg font-bold">{{ $leaveStats['personal_leave'] }}
                            วัน</span> |
                    </div>
                    <div class="flex items-center justify-between rounded-lg">
                        <span class="text-lg font-medium">ลาป่วย</span>
                        <span
                            class="text-xl text-white bg-cyan-500 p-2 px-15 rounded-lg font-bold">{{ $leaveStats['sick_leave'] }}
                            วัน</span>
                    </div>
                </div>
            </div>

            <!-- การแสดงแบบ Pie Chart -->
            <div id="chartView" class="stats-container hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pie Chart -->
                    <div class="flex flex-col items-center">
                        <div class="w-80 h-80">
                            <canvas id="leaveStatsChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- สถิติย่อย -->
                    <div class="flex flex-col justify-center space-y-4">
                        <div class="bg-blue-500 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">ลางานทั้งหมด</p>
                                    <p class="text-2xl font-bold">{{ $leaveStats['total_days'] }} วัน</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-2">
                                    📊
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-500 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">ลากิจ</p>
                                    <p class="text-2xl font-bold">{{ $leaveStats['personal_leave'] }} วัน</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-2">
                                    💼
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-cyan-500 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-cyan-100 text-sm">ลาป่วย</p>
                                    <p class="text-2xl font-bold">{{ $leaveStats['sick_leave'] }} วัน</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-2">
                                    🏥
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $totalDays = $leaveStats['total_days'];
                            $personalPercentage = $totalDays > 0 ? round(($leaveStats['personal_leave'] / $totalDays) * 100, 1) : 0;
                            $sickPercentage = $totalDays > 0 ? round(($leaveStats['sick_leave'] / $totalDays) * 100, 1) : 0;
                        @endphp
                        
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">สัดส่วนการลา</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                        <span>ลากิจ</span>
                                    </div>
                                    <span class="font-medium">{{ $personalPercentage }}%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-cyan-500 rounded-full mr-2"></div>
                                        <span>ลาป่วย</span>
                                    </div>
                                    <span class="font-medium">{{ $sickPercentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
            </div>

            <hr class="my-6 border-gray-300 dark:border-gray-600">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold ">กรอกข้อมูลขอลางาน</h2>
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
                        <option value="ลากิจ" {{ old('leave_type') == 'ลากิจ' ? 'selected' : '' }}>ลากิจ</option>
                        <option value="ลาป่วย" {{ old('leave_type') == 'ลาป่วย' ? 'selected' : '' }}>ลาป่วย
                        </option>
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
                                    <p class="radio-description text-sm text-gray-500 dark:text-gray-400">ลาตลอดวัน
                                    </p>
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
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
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

                    <!-- ประเภทการเลือกวันที่ -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div class="radio-option">
                            <input type="radio" name="date_selection_type" value="single" id="single_date" checked
                                class="sr-only">
                            <label for="single_date"
                                class="radio-content cursor-pointer flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 transition-all duration-300">
                                <div
                                    class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <div class="radio-dot w-2 h-2 bg-blue-500 rounded-full opacity-0"></div>
                                </div>
                                <div>
                                    <span class="radio-text font-medium block">วันเดียว</span>
                                    <p class="radio-description text-sm text-gray-500 dark:text-gray-400">
                                        ลาเพียงวันเดียว</p>
                                </div>
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="date_selection_type" value="range" id="date_range"
                                class="sr-only">
                            <label for="date_range"
                                class="radio-content cursor-pointer flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-300 transition-all duration-300">
                                <div
                                    class="radio-indicator w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <div class="radio-dot w-2 h-2 bg-blue-500 rounded-full opacity-0"></div>
                                </div>
                                <div>
                                    <span class="radio-text font-medium block">หลายวัน</span>
                                    <p class="radio-description text-sm text-gray-500 dark:text-gray-400">
                                        ลาติดต่อกันหลายวัน</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Single Date Input -->
                    <div id="single_date_field">
                        <input type="date" name="leave_date" id="leave_date" value="{{ old('leave_date') }}"
                            required min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300 hover:border-blue-300">
                    </div>

                    <div id="date_range_fields"
                        class="hidden space-y-4 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border-2 border-blue-200 dark:border-blue-700">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <h3 class="font-semibold text-blue-700 dark:text-blue-300">ระบุช่วงวันที่ต้องการลา</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">วันที่เริ่มต้น</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300">
                            </div>
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">วันที่สิ้นสุด</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-300">
                            </div>
                        </div>
                        <div id="date_summary" class="hidden mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-700 dark:text-green-300" id="summary_text"></span>
                            </div>
                        </div>
                    </div>

                    @error('leave_date')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    @error('end_date')
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
                                        <p class="text-sm text-gray-500 dark:text-gray-400" id="fileSize">0 KB
                                        </p>
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
                        class="w-full bg-yellow-400 text-white py-4 px-8 rounded-xl hover:bg-yellow-500 transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        ส่งเรื่องขออนุมัติ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let leaveStatsChart;

        // ฟังก์ชันสำหรับเปลี่ยนการแสดงผล
        function toggleView(viewType) {
            const normalView = document.getElementById('normalView');
            const chartView = document.getElementById('chartView');
            const normalBtn = document.getElementById('normalViewBtn');
            const chartBtn = document.getElementById('chartViewBtn');

            if (viewType === 'normal') {
                // แสดงแบบปกติ
                normalView.classList.remove('hidden');
                chartView.classList.add('hidden');
                
                // อัพเดทปุ่ม - ปุ่มปกติเป็น active
                normalBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                normalBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
                
                chartBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                chartBtn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
                
                // ทำลาย Chart หากมี
                if (leaveStatsChart) {
                    leaveStatsChart.destroy();
                    leaveStatsChart = null;
                }
            } else {
                // แสดงแบบ Pie Chart
                normalView.classList.add('hidden');
                chartView.classList.remove('hidden');
                
                // อัพเดทปุ่ม - ปุ่ม chart เป็น active
                chartBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                chartBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
                
                normalBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                normalBtn.classList.add('text-gray-500', 'dark:text-gray-400', 'hover:text-gray-900', 'dark:hover:text-white');
                
                // สร้าง Pie Chart
                setTimeout(() => {
                    createPieChart();
                }, 100);
            }
        }

        // ฟังก์ชันสร้าง Pie Chart
        function createPieChart() {
            const ctx = document.getElementById('leaveStatsChart').getContext('2d');
            
            const personalLeave = {{ $leaveStats['personal_leave'] }};
            const sickLeave = {{ $leaveStats['sick_leave'] }};
            
            // หากไม่มีข้อมูลการลา
            if (personalLeave === 0 && sickLeave === 0) {
                leaveStatsChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['ยังไม่มีการลา'],
                        datasets: [{
                            data: [1],
                            backgroundColor: ['#e5e7eb'],
                            borderColor: ['#d1d5db'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'ยังไม่มีข้อมูลการลา';
                                    }
                                }
                            }
                        },
                        cutout: '40%'
                    }
                });
                return;
            }
            
            leaveStatsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['ลากิจ', 'ลาป่วย'],
                    datasets: [{
                        data: [personalLeave, sickLeave],
                        backgroundColor: [
                            '#8b5cf6', // purple-500
                            '#06b6d4'  // cyan-500
                        ],
                        borderColor: [
                            '#7c3aed', // purple-600
                            '#0891b2'  // cyan-600
                        ],
                        borderWidth: 2,
                        hoverBackgroundColor: [
                            '#7c3aed',
                            '#0891b2'
                        ],
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 14
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label;
                                    const value = context.parsed;
                                    const total = personalLeave + sickLeave;
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} วัน (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '40%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // เริ่มต้นด้วยการแสดงแบบปกติ
            toggleView('normal');
            
            // เพิ่ม Event Listeners สำหรับปุ่มสลับการแสดงผล
            const normalViewBtn = document.getElementById('normalViewBtn');
            const chartViewBtn = document.getElementById('chartViewBtn');
            
            normalViewBtn.addEventListener('click', function() {
                toggleView('normal');
            });
            
            chartViewBtn.addEventListener('click', function() {
                toggleView('chart');
            });
            
            // Theme Toggle Functionality
            const themeToggle = document.getElementById('themeToggle');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');
            const html = document.documentElement;

            // Check for saved theme preference or default to 'light' mode
            const currentTheme = localStorage.getItem('theme') || 'light';

            // Apply initial theme
            if (currentTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            themeToggle.addEventListener('click', function(e) {
                e.preventDefault();

                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });

            // Form functionality
            const fullDayRadio = document.getElementById('full_day');
            const hourlyRadio = document.getElementById('hourly');
            const timeFields = document.getElementById('time_fields');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const submitBtn = document.getElementById('submitBtn');
            const leaveForm = document.getElementById('leaveForm');

            // Date selection functionality
            const singleDateRadio = document.getElementById('single_date');
            const dateRangeRadio = document.getElementById('date_range');
            const singleDateField = document.getElementById('single_date_field');
            const dateRangeFields = document.getElementById('date_range_fields');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const leaveDateInput = document.getElementById('leave_date');
            const dateSummary = document.getElementById('date_summary');
            const summaryText = document.getElementById('summary_text');

            // File upload elements
            const fileInput = document.getElementById('attachment');
            const fileUploadArea = document.getElementById('fileUploadArea');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const fileIcon = document.getElementById('fileIcon');
            const removeFileBtn = document.getElementById('removeFile');

            function toggleDateFields() {
                if (dateRangeRadio.checked) {
                    singleDateField.classList.add('hidden');
                    dateRangeFields.classList.remove('hidden');
                    leaveDateInput.required = false;
                    startDateInput.required = true;
                    endDateInput.required = true;
                } else {
                    singleDateField.classList.remove('hidden');
                    dateRangeFields.classList.add('hidden');
                    leaveDateInput.required = true;
                    startDateInput.required = false;
                    endDateInput.required = false;
                    startDateInput.value = '';
                    endDateInput.value = '';
                    dateSummary.classList.add('hidden');
                }
            }

            function calculateDateRange() {
                if (startDateInput.value && endDateInput.value) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (endDate >= startDate) {
                        const timeDiff = endDate.getTime() - startDate.getTime();
                        const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                        const startDateThai = startDate.toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        const endDateThai = endDate.toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        summaryText.textContent = `ลางานตั้งแต่ ${startDateThai} ถึง ${endDateThai} รวม ${dayDiff} วัน`;
                        dateSummary.classList.remove('hidden');
                    } else {
                        dateSummary.classList.add('hidden');
                    }
                } else {
                    dateSummary.classList.add('hidden');
                }
            }

            // Set minimum date for end_date when start_date changes
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                calculateDateRange();
            });

            endDateInput.addEventListener('change', calculateDateRange);

            singleDateRadio.addEventListener('change', toggleDateFields);
            dateRangeRadio.addEventListener('change', toggleDateFields);
            toggleDateFields();

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
                const dateSelectionType = document.querySelector(
                    'input[name="date_selection_type"]:checked');
                const additionalInfo = document.getElementById('additional_info').value;

                // ตรวจสอบข้อมูลพื้นฐาน
                if (!leaveType || !durationType || !additionalInfo.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        text: 'โปรดตรวจสอบและกรอกข้อมูลที่จำเป็นให้ครบถ้วน',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3B82F6'
                    });
                    return;
                }

                // ตรวจสอบวันที่
                let dateInfo = '';
                let dateValid = false;

                if (dateSelectionType.value === 'single') {
                    const leaveDate = document.getElementById('leave_date').value;
                    if (!leaveDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกวันที่ลา',
                            text: 'โปรดเลือกวันที่ต้องการลา',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#3B82F6'
                        });
                        return;
                    }
                    dateInfo =
                        `<p><strong>วันที่ลา:</strong> ${new Date(leaveDate).toLocaleDateString('th-TH')}</p>`;
                    dateValid = true;
                } else {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;

                    if (!startDate || !endDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกช่วงวันที่ลา',
                            text: 'โปรดเลือกวันที่เริ่มต้นและวันที่สิ้นสุด',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#3B82F6'
                        });
                        return;
                    }

                    const startDateObj = new Date(startDate);
                    const endDateObj = new Date(endDate);
                    const dayDiff = Math.ceil((endDateObj.getTime() - startDateObj.getTime()) / (1000 *
                        3600 * 24)) + 1;

                    dateInfo =
                        `<p><strong>ช่วงวันที่ลา:</strong> ${startDateObj.toLocaleDateString('th-TH')} - ${endDateObj.toLocaleDateString('th-TH')} (${dayDiff} วัน)</p>`;
                    dateValid = true;
                }

                if (!dateValid) return;

                // ตรวจสอบเวลา (ถ้าเลือกชั่วโมง)
                let timeInfo = '';
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
                    timeInfo = `<p><strong>เวลา:</strong> ${startTime} - ${endTime}</p>`;
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
                            ${dateInfo}
                            ${timeInfo}
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
