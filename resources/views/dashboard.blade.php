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
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">My Work - ระบบลางาน</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    ออกจากระบบ
                </button>
            </form>
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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">สถิติการลางานปี {{ date('Y') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-300">{{ $leaveStats['total_days'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">วันลาทั้งหมด</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-300">
                        {{ $leaveStats['personal_leave'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">ลากิจ</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                    <div class="text-3xl font-bold text-red-600 dark:text-red-300">{{ $leaveStats['sick_leave'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">ลาป่วย</div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('leave-history') }}"
                    class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    ดูประวัติการลางาน
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">ฟอร์มขอลางาน</h2>

            <form method="POST" action="{{ route('leave-request.store') }}" enctype="multipart/form-data"
                class="space-y-6" id="leaveForm">
                @csrf

                <div>
                    <label for="leave_type" class="block text-sm font-medium mb-2">ประเภทการลางาน *</label>
                    <select name="leave_type" id="leave_type" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">เลือกประเภทการลางาน</option>
                        <option value="ลากิจ" {{ old('leave_type') == 'ลากิจ' ? 'selected' : '' }}>ลากิจ</option>
                        <option value="ลาป่วย" {{ old('leave_type') == 'ลาป่วย' ? 'selected' : '' }}>ลาป่วย</option>
                    </select>
                    @error('leave_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">ประเภทระยะเวลา *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="duration_type" value="ทั้งวัน" id="full_day"
                                {{ old('duration_type') == 'ทั้งวัน' ? 'checked' : '' }}
                                class="mr-2 text-blue-600 focus:ring-blue-500" required>
                            <span>ทั้งวัน</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="duration_type" value="ชั่วโมง" id="hourly"
                                {{ old('duration_type') == 'ชั่วโมง' ? 'checked' : '' }}
                                class="mr-2 text-blue-600 focus:ring-blue-500" required>
                            <span>ชั่วโมง</span>
                        </label>
                    </div>
                    @error('duration_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="leave_date" class="block text-sm font-medium mb-2">วันที่ต้องการลา *</label>
                    <input type="date" name="leave_date" id="leave_date" value="{{ old('leave_date') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    @error('leave_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="time_fields" class="hidden space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium mb-2">เวลาเริ่มต้น</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium mb-2">เวลาสิ้นสุด</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="additional_info" class="block text-sm font-medium mb-2">ข้อมูลเพิ่มเติม</label>
                    <textarea name="additional_info" id="additional_info" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="ระบุเหตุผลหรือข้อมูลเพิ่มเติม...">{{ old('additional_info') }}</textarea>
                    @error('additional_info')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="attachment" class="block text-sm font-medium mb-2">ไฟล์แนบ</label>
                    <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <p class="text-sm text-gray-500 mt-1">รองรับไฟล์: PDF, JPG, PNG, DOC, DOCX (ขนาดไม่เกิน 2MB)</p>
                    @error('attachment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="button" id="submitBtn"
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition font-medium">
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

            // Confirm
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const leaveType = document.getElementById('leave_type').value;
                const durationType = document.querySelector('input[name="duration_type"]:checked');
                const leaveDate = document.getElementById('leave_date').value;

                if (!leaveType || !durationType || !leaveDate) {
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
