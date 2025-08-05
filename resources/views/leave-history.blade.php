<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประวัติการลางาน - My Work</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white min-h-screen">
    <div class="max-w-2xl mx-auto p-6">
        <header class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700">← กลับหน้าหลัก</a>
            </div>
        </header>

        <!-- สถิติการลางาน -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 mb-4 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center">
                    ปี {{ thaidate('Y') }}
                </h2>
            </div>

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

            <hr class="my-6 border-gray-300 dark:border-gray-600">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">ประวัติการลา</h1>

                <!-- ปุ่มสลับการแสดงผล -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button id="tableViewBtn"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm">
                        ตาราง
                    </button>
                    <button id="cardViewBtn"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        การ์ด
                    </button>
                </div>
            </div>

            @if ($leaveRequests->count() > 0)
                <!-- Table View -->
                <div id="tableView" class="view-container">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        วันที่ส่งคำขอ</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ประเภท</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        วันที่ลา</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ระยะเวลา</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        สถานะ</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($leaveRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $request->created_at->thaidate('j M y  H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $request->leave_type === 'ลากิจ' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $request->leave_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $request->leave_date->thaidate('j M y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            @if ($request->duration_type === 'ทั้งวัน')
                                                ทั้งวัน
                                            @else
                                                {{ $request->start_time->format('H:i') }} -
                                                {{ $request->end_time->format('H:i') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if ($request->status === 'อนุมัติ') bg-green-100 text-green-800
                                                    @elseif($request->status === 'รออนุมัติ') bg-yellow-100 text-yellow-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            @if ($request->additional_info)
                                                <div class="max-w-xs truncate" title="{{ $request->additional_info }}">
                                                    {{ $request->additional_info }}
                                                </div>
                                            @endif
                                            @if ($request->attachment_path)
                                                <a href="{{ asset('storage/' . $request->attachment_path) }}"
                                                    target="_blank" class="text-blue-600 hover:text-blue-700 text-xs">
                                                    📎 ไฟล์แนบ
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View -->
                <div id="cardView" class="view-container hidden space-y-4">
                    @foreach ($leaveRequests as $request)
                        <div
                            class="bg-white dark:bg-gray-700 rounded-lg shadow-md border border-gray-200 dark:border-gray-600 overflow-hidden">
                            <!-- Header Bar with leave type info -->
                            @if ($request->leave_type === 'ลากิจ')
                                <div class="bg-orange-300 text-white p-4 flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">💼</span>
                                    </div>
                                    <div class="font-semibold text-lg">ลากิจ : 1 วัน</div>
                                    <div class="font-semibold text-lg">(วันที่
                                        {{ $request->leave_date->thaidate('j M Y') }})
                                    </div>
                                </div>
                            @else
                                <div class="bg-green-600 text-white p-4 flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">🏥</span>
                                    </div>
                                    <div class="font-semibold text-lg">ลาป่วย :
                                        @if ($request->duration_type === 'ทั้งวัน')
                                            1 วัน
                                        @else
                                            ชั่วโมง
                                        @endif
                                    </div>
                                    <div class="font-semibold text-lg">(วันที่
                                        {{ $request->leave_date->thaidate('j - j M Y') }})
                                    </div>
                                </div>
                            @endif

                            <!-- Card Content -->
                            <div class="p-4">
                                <!-- Additional info -->
                                <div class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                                    {{ $request->additional_info }}
                                </div>

                                <!-- Bottom section with info and status -->
                                <div class="flex justify-between items-center">
                                    <div class="flex flex-col space-y-1 text-xs text-gray-500 dark:text-gray-400">
                                    </div>

                                    <!-- Status badge in bottom right -->
                                    <div class="flex items-center">
                                        @if ($request->status === 'รออนุมัติ')
                                            <div
                                                class="bg-orange-300 text-white px-3 py-1 rounded-full text-sm flex items-center">
                                                <span class="mr-1">⏳</span> รออนุมัติ
                                            </div>
                                        @elseif($request->status === 'อนุมัติ')
                                            <div
                                                class="bg-green-500 text-white px-3 py-1 rounded-full text-sm flex items-center">
                                                <span class="mr-1">✅</span> อนุมัติ
                                            </div>
                                        @else
                                            <div
                                                class="bg-red-500 text-white px-3 py-1 rounded-full text-sm flex items-center">
                                                <span class="mr-1">❌</span> ไม่อนุมัติ
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $leaveRequests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-lg font-medium">ยังไม่มีประวัติการลางาน</p>
                        <p class="mt-1">เมื่อคุณส่งคำขอลางาน ประวัติจะแสดงที่นี่</p>
                    </div>
                </div>
            @endif
        </div>

        <script>
            const tableViewBtn = document.getElementById('tableViewBtn');
            const cardViewBtn = document.getElementById('cardViewBtn');
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');

            function showTableView() {
                tableView.classList.remove('hidden');
                cardView.classList.add('hidden');

                tableViewBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                tableViewBtn.classList.remove('text-gray-500', 'dark:text-gray-400');

                cardViewBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                cardViewBtn.classList.add('text-gray-500', 'dark:text-gray-400');

                localStorage.setItem('leaveHistoryView', 'table');
            }

            function showCardView() {
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');

                cardViewBtn.classList.add('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                cardViewBtn.classList.remove('text-gray-500', 'dark:text-gray-400');

                tableViewBtn.classList.remove('bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white', 'shadow-sm');
                tableViewBtn.classList.add('text-gray-500', 'dark:text-gray-400');

                localStorage.setItem('leaveHistoryView', 'card');
            }

            tableViewBtn.addEventListener('click', showTableView);
            cardViewBtn.addEventListener('click', showCardView);

            const savedView = localStorage.getItem('leaveHistoryView');
            if (savedView === 'card') {
                showCardView();
            } else {
                showTableView();
            }
        </script>
</body>

</html>
