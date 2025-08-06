<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการดำเนินการ - {{ $action }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            @if ($success)
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $action === 'อนุมัติ' ? 'bg-green-100' : 'bg-red-100' }} mb-4">
                        @if ($action === 'อนุมัติ')
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold {{ $action === 'อนุมัติ' ? 'text-green-800' : 'text-red-800' }}">
                        {{ $action === 'อนุมัติ' ? '✅ อนุมัติเรียบร้อย' : '❌ ไม่อนุมัติเรียบร้อย' }}
                    </h1>
                </div>

                <div class="mb-6 p-4 {{ $action === 'อนุมัติ' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                    <p class="text-gray-700">{{ $message }}</p>
                </div>

                @if (isset($leaveRequest))
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
                        <h3 class="font-semibold text-gray-800 mb-3">📋 รายละเอียดคำขอลางาน:</h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">พนักงาน:</span>
                                <span class="font-medium">{{ $leaveRequest->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">ประเภท:</span>
                                <span class="font-medium">{{ $leaveRequest->leave_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">วันที่:</span>
                                <span class="font-medium">
                                    @if ($leaveRequest->is_range && $leaveRequest->range_start_date && $leaveRequest->range_end_date)
                                        {{ \Carbon\Carbon::parse($leaveRequest->range_start_date)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($leaveRequest->range_end_date)->format('d/m/Y') }}
                                    @else
                                        {{ $leaveRequest->leave_date->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">สถานะ:</span>
                                <span
                                    class="font-medium px-2 py-1 rounded text-xs {{ $leaveRequest->status === 'อนุมัติ' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $leaveRequest->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-red-800 mb-4">❌ เกิดข้อผิดพลาด</h1>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-700">{{ $message }}</p>
                    </div>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-4">My Work - ระบบลางาน</p>
                <p class="text-xs text-gray-400">
                    การดำเนินการเมื่อ: {{ now()->thaidate('j M Y H:i น.') }}
                </p>
            </div>
        </div>
    </div>
</body>

</html>
