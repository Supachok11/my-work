<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header with User Info -->
        <div class="bg-white shadow-sm">
            <div class="max-w-md mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ userInitial }}</span>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ user.name }}</div>
                            <div class="text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-800">{{ currentTime }}</div>
                        <div class="text-xs text-gray-500">{{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-md mx-auto px-4 py-6">
            <!-- App Title -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">MY WORK</h1>
                <div class="text-sm text-gray-500">สำหรับพนักงาน / เจ้าหน้าที่ทั่วไป</div>
            </div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <!-- Row 1 -->
                <!-- ลงเวลาทำงาน -->
                <div class="text-center">
                    <button @click="goToAttendance" 
                            class="w-16 h-16 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 mb-2 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="text-xs text-gray-700 font-medium">ลงเวลาทำงาน</div>
                </div>

                <!-- งานของฉัน -->
                <div class="text-center">
                    <button @click="goToMyTasks" 
                            class="w-16 h-16 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 mb-2 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                    </button>
                    <div class="text-xs text-gray-700 font-medium">งานของฉัน</div>
                </div>

                <!-- สรุปผลงาน -->
                <div class="text-center">
                    <button @click="goToReports" 
                            class="w-16 h-16 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 mb-2 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </button>
                    <div class="text-xs text-gray-700 font-medium">สรุปผลงาน</div>
                </div>

                <!-- รางวัลที่ได้ -->
                <div class="text-center">
                    <button @click="goToRewards" 
                            class="w-16 h-16 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 mb-2 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </button>
                    <div class="text-xs text-gray-700 font-medium">รางวัลที่ได้</div>
                </div>

                <!-- Row 2 -->
                <!-- การลา -->
                <div class="text-center">
                    <button @click="goToLeaveRequest" 
                            class="w-16 h-16 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 mb-2 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="text-xs text-gray-700 font-medium">การลา</div>
                </div>
            </div>


            <!-- Additional Info -->
            <div class="mt-8 text-center">
                <div class="text-xs text-gray-400">
                    เวอร์ชัน 1.0.0 | © 2025 MyWork System
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,
        stats: Object,
        currentTime: String,
    },

    data() {
        return {
            liveTime: new Date(),
            timeInterval: null,
        }
    },

    computed: {
        userInitial() {
            return this.user.name.charAt(0).toUpperCase()
        },

        currentTime() {
            return this.liveTime.toLocaleTimeString('th-TH', {
                hour: '2-digit',
                minute: '2-digit'
            })
        },

        currentDate() {
            return this.liveTime.toLocaleDateString('th-TH', {
                weekday: 'long',
                day: 'numeric',
                month: 'short'
            })
        },

        attendanceStatusText() {
            const status = this.stats.attendance.today_status
            const statusMap = {
                'not_checked_in': 'ยังไม่เช็คอิน',
                'checked_in': 'เช็คอินแล้ว',
                'completed': 'เสร็จสิ้น'
            }
            return statusMap[status] || 'ไม่ทราบสถานะ'
        },

        attendanceStatusClass() {
            const status = this.stats.attendance.today_status
            const classMap = {
                'not_checked_in': 'bg-gray-100',
                'checked_in': 'bg-yellow-100',
                'completed': 'bg-green-100'
            }
            return classMap[status] || 'bg-gray-100'
        },

        attendanceIconClass() {
            const status = this.stats.attendance.today_status
            const classMap = {
                'not_checked_in': 'text-gray-500',
                'checked_in': 'text-yellow-600',
                'completed': 'text-green-600'
            }
            return classMap[status] || 'text-gray-500'
        },

        attendanceTextClass() {
            const status = this.stats.attendance.today_status
            const classMap = {
                'not_checked_in': 'text-gray-600',
                'checked_in': 'text-yellow-600',
                'completed': 'text-green-600'
            }
            return classMap[status] || 'text-gray-600'
        }
    },

    mounted() {
        // Update time every second
        this.timeInterval = setInterval(() => {
            this.liveTime = new Date()
        }, 1000)
    },

    beforeUnmount() {
        if (this.timeInterval) {
            clearInterval(this.timeInterval)
        }
    },

    methods: {
        goToAttendance() {
            window.location.href = '/nova/time-attendance'
        },

        goToLeaveRequest() {
            window.location.href = '/nova/leave-portal'
        },

        goToMyTasks() {
            window.location.href = '/nova/my-tasks'
        },

        goToReports() {
            // TODO: Implement reports functionality
            alert('ฟีเจอร์สรุปผลงาน กำลังพัฒนา')
        },

        goToRewards() {
            // TODO: Implement rewards functionality
            alert('ฟีเจอร์รางวัลดีเด่น กำลังพัฒนา')
        },
    }
}
</script>

<style scoped>
/* Additional custom styles if needed */
</style>
