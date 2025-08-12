<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-light text-gray-900 mb-1">ระบบขอลางาน</h1>
            <p class="text-gray-600">สวัสดี, {{ user.name }} </p>
          </div>
          <div class="flex items-center space-x-4">
            <span v-if="user.role === 'admin'"
              class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
              Admin
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md">
          <div class="p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-4">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-600 mb-1">ลาไปแล้วทั้งหมด</p>
                <p class="text-2xl font-semibold text-gray-900">{{ Math.round(leaveStats.total_days) }} <span
                    class="text-sm font-normal text-gray-500">วัน</span></p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md">
          <div class="p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-4">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-600 mb-1">ลากิจ</p>
                <p class="text-2xl font-semibold text-gray-900">{{ Math.round(leaveStats.personal_leave) }} <span
                    class="text-sm font-normal text-gray-500">วัน</span></p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md">
          <div class="p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-4">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                      clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
              <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-600 mb-1">ลาป่วย</p>
                <p class="text-2xl font-semibold text-gray-900">{{ Math.round(leaveStats.sick_leave) }} <span
                    class="text-sm font-normal text-gray-500">วัน</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Requests (Admin Only) -->
      <div v-if="user.role === 'admin' && pendingCount > 0" class="mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
          <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
              <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                ⚠️ คำขอลาที่รอการอนุมัติ
              </h3>
              <p class="text-yellow-700 mb-4">
                มีคำขอลา {{ pendingCount }} รายการที่รอการอนุมัติจากคุณ
              </p>
              <a href="/nova/resources/leave-requests"
                class="inline-flex items-center px-4 py-2 border border-gray-200 bg-yellow text-black text-sm font-medium rounded-lg">
                ตรวจสอบคำขอ →
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
          <div class="p-6">
            <div class="flex items-center mb-6">
              <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-900">ดำเนินการ</h3>
            </div>
            <div class="space-y-3">
              <a href="/nova/resources/leave-requests/new"
                class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                  </path>
                </svg>
                สร้างคำขอลาใหม่
              </a>
              <a href="/nova/resources/leave-requests"
                class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                  </path>
                </svg>
                ดูคำขอทั้งหมด
              </a>
            </div>
          </div>
        </div>

        <!-- Recent Requests -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
          <div class="p-6">
            <div class="flex items-center mb-6">
              <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-900">คำขอลางานล่าสุด</h3>
            </div>
            <div class="space-y-4">
              <div v-for="request in recentRequests" :key="request.id"
                class="flex items-center justify-between p-4 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50">
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-1">
                    <span class="text-sm font-medium text-gray-900">{{ request.leave_type }}</span>
                    <span :class="getStatusClass(request.status)"
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium">
                      {{ request.status }}
                    </span>
                  </div>
                  <p class="text-xs text-gray-500">{{ formatLeaveInfo(request) }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ formatDate(request.created_at) }}</p>
                </div>
                <div>
                  <a :href="`/nova/resources/leave-requests/${request.id}`"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    ดูรายละเอียด →
                  </a>
                </div>
              </div>
              <div v-if="recentRequests.length === 0" class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                </div>
                <p class="text-gray-500 text-sm">ยังไม่มีคำขอลา</p>
                <p class="text-gray-400 text-xs mt-1">สร้างคำขอลาแรกของคุณ</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'LeavePortalDashboard',
  props: {
    user: {
      type: Object,
      required: true
    },
    leaveStats: {
      type: Object,
      required: true
    },
    recentRequests: {
      type: Array,
      required: true
    },
    pendingCount: {
      type: Number,
      default: 0
    }
  },
  methods: {
    getStatusClass(status) {
      const classes = {
        'รออนุมัติ': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        'อนุมัติ': 'bg-green-100 text-green-800 border border-green-200',
        'ไม่อนุมัติ': 'bg-red-100 text-red-800 border border-red-200'
      }
      return classes[status] || 'bg-gray-100 text-gray-800 border border-gray-200'
    },

    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('th-TH', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    formatLeaveInfo(request) {
      if (request.is_range && request.range_start_date && request.range_end_date) {
        const startDate = new Date(request.range_start_date)
        const endDate = new Date(request.range_end_date)
        const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1
        return `${days} วัน (${startDate.toLocaleDateString('th-TH')} - ${endDate.toLocaleDateString('th-TH')})`
      } else if (request.duration_type === 'ชั่วโมง' && request.start_time && request.end_time) {
        const startTime = new Date(request.start_time).toLocaleTimeString('th-TH', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        })
        const endTime = new Date(request.end_time).toLocaleTimeString('th-TH', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        })
        return `${startTime} - ${endTime}`
      } else {
        return 'ทั้งวัน'
      }
    },

    logout() {
      window.location.href = '/logout'
    }
  }
})
</script>
