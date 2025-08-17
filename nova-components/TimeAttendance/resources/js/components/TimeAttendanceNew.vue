<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-md mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">{{ user.name }}</div>
                            <div class="text-xs text-gray-400">{{ user.email }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ currentTime }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-md mx-auto px-4 py-6">
            <!-- Title -->
            <div class="text-center mb-8">
                <h1 class="text-xl font-bold text-gray-800 mb-2">ลงชื่อเข้างาน (Check-in)</h1>
                <div class="text-sm text-gray-500">{{ currentDateFull }}</div>
            </div>

            <!-- Status Card -->
            <div v-if="todayLog" class="bg-white rounded-2xl shadow-sm border p-6 mb-6">
                <div class="text-center">
                    <div v-if="todayLog.clock_in && !todayLog.clock_out" class="mb-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-green-600 mb-1">เข้างานแล้ว</div>
                        <div class="text-2xl font-bold text-gray-800 mb-1">{{ formatTime(todayLog.clock_in) }}</div>
                        <div class="text-sm text-gray-500">{{ getStatusText(todayLog.status) }}</div>
                    </div>

                    <div v-else-if="todayLog.clock_in && todayLog.clock_out" class="mb-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-blue-600 mb-1">เสร็จสิ้นแล้ว</div>
                        <div class="text-sm text-gray-600 mb-2">
                            เข้างาน: {{ formatTime(todayLog.clock_in) }} | ออกงาน: {{ formatTime(todayLog.clock_out) }}
                        </div>
                        <div class="text-lg font-semibold text-gray-800">ทำงาน {{ getWorkingHours(todayLog) }} ชั่วโมง</div>
                    </div>
                </div>
            </div>

            <!-- Company/Location Info -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-800">บริษัท ABC จำกัด</div>
                        <div class="text-sm text-blue-600">ระยะทาง: 30 ม.</div>
                    </div>
                </div>
            </div>

            <!-- Check In/Out Buttons -->
            <div class="space-y-4">
                <!-- Check In Button -->
                <div v-if="!todayLog || !todayLog.clock_in">
                    <button @click="clockIn" :disabled="loading"
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-black py-4 rounded-2xl text-lg font-semibold transition-all duration-200 shadow-lg">
                        <div v-if="loading" class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white mr-2"></div>
                            กำลังบันทึก...
                        </div>
                        <div v-else class="flex items-center justify-center ">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Check in
                        </div>
                    </button>
                </div>

                <!-- Check Out Button -->
                <div v-else-if="todayLog.clock_in && !todayLog.clock_out">
                    <button @click="clockOut" :disabled="loading"
                        class="w-full bg-red-500 hover:bg-red-600 disabled:bg-gray-400 text-white py-4 rounded-2xl text-lg font-semibold transition-all duration-200 shadow-lg">
                        <div v-if="loading" class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white mr-2"></div>
                            กำลังบันทึก...
                        </div>
                        <div v-else class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                            Check out
                        </div>
                    </button>
                </div>

                <!-- Already Completed -->
                <div v-else class="text-center py-8">
                    <div class="text-gray-500 mb-2">วันนี้คุณได้ลงเวลาเรียบร้อยแล้ว</div>
                    <div class="text-sm text-gray-400">ขอบคุณสำหรับการทำงานวันนี้</div>
                </div>
            </div>

            <!-- Additional Options -->
            <div class="mt-8 space-y-3">
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-600">กำหนดเวลาทำงาน</span>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        09:00 - 18:00
                    </div>
                </div>

                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-600">วันทำงานเดือนนี้</span>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        {{ monthlyStats.total_days || 0 }} วัน
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div v-if="message" class="fixed top-4 left-4 right-4 z-50">
            <div class="max-w-md mx-auto">
                <div class="rounded-2xl p-4 shadow-lg" :class="messageType === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg v-if="messageType === 'success'" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium" :class="messageType === 'success' ? 'text-green-800' : 'text-red-800'">
                                {{ message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,
        todayLog: Object,
        monthlyStats: Object,
        recentLogs: Array,
        averageWorkingHours: Number,
        currentTime: String,
    },

    data() {
        return {
            loading: false,
            message: '',
            messageType: 'success',
            liveTime: new Date(),
            timeInterval: null,
            location: null,
        }
    },

    computed: {
        currentDate() {
            return this.formatDate(new Date())
        },

        currentDateFull() {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }
            return this.liveTime.toLocaleDateString('th-TH', options)
        },

        currentTime() {
            return this.liveTime.toLocaleTimeString('th-TH', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            })
        }
    },

    mounted() {
        // Update time every second
        this.timeInterval = setInterval(() => {
            this.liveTime = new Date()
        }, 1000)

        // Get user location
        this.getCurrentLocation()
    },

    beforeUnmount() {
        if (this.timeInterval) {
            clearInterval(this.timeInterval)
        }
    },

    methods: {
        async clockIn() {
            this.loading = true
            this.message = ''

            try {
                const response = await Nova.request().post('/nova-vendor/time-attendance/clock-in', {
                    latitude: this.location?.latitude,
                    longitude: this.location?.longitude,
                })

                if (response.data.success) {
                    this.showMessage(response.data.message, 'success')
                    // Reload page data
                    setTimeout(() => {
                        window.location.reload()
                    }, 1500)
                }
            } catch (error) {
                const message = error.response?.data?.message || 'เกิดข้อผิดพลาดในการลงเวลาเข้างาน'
                this.showMessage(message, 'error')
            } finally {
                this.loading = false
            }
        },

        async clockOut() {
            this.loading = true
            this.message = ''

            try {
                const response = await Nova.request().post('/nova-vendor/time-attendance/clock-out', {
                    latitude: this.location?.latitude,
                    longitude: this.location?.longitude,
                })

                if (response.data.success) {
                    this.showMessage(response.data.message, 'success')
                    // Reload page data
                    setTimeout(() => {
                        window.location.reload()
                    }, 1500)
                }
            } catch (error) {
                const message = error.response?.data?.message || 'เกิดข้อผิดพลาดในการลงเวลาออกงาน'
                this.showMessage(message, 'error')
            } finally {
                this.loading = false
            }
        },

        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.location = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        }
                    },
                    (error) => {
                        console.warn('ไม่สามารถระบุตำแหน่งได้:', error.message)
                    }
                )
            }
        },

        showMessage(message, type = 'success') {
            this.message = message
            this.messageType = type

            setTimeout(() => {
                this.message = ''
            }, 3000)
        },

        formatTime(datetime) {
            if (!datetime) return '-'
            return new Date(datetime).toLocaleTimeString('th-TH', {
                hour: '2-digit',
                minute: '2-digit'
            })
        },

        formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            })
        },

        getWorkingHours(log) {
            if (!log || !log.clock_in || !log.clock_out) return '-'

            const clockIn = new Date(log.clock_in)
            const clockOut = new Date(log.clock_out)
            const diffMs = clockOut - clockIn
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60))
            const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60))

            return `${diffHours}:${diffMinutes.toString().padStart(2, '0')}`
        },

        getStatusText(status) {
            const statusMap = {
                'present': 'มาทำงาน',
                'late': 'มาสาย',
                'absent': 'ขาดงาน',
                'half_day': 'ลาครึ่งวัน'
            }
            return statusMap[status] || status
        }
    }
}
</script>

<style scoped>
/* เพิ่ม custom styles ถ้าจำเป็น */
</style>
