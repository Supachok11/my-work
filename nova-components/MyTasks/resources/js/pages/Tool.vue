<template>
  <div class="min-h-screen bg-gray-50">

    <Head title="MY WORK" />

    <!-- Header with User Info -->
    <div class="bg-white shadow-sm">
      <div class="max-w-md mx-auto px-4 py-4">
        <div class="flex items-center text-center justify-between">
          <div class="flex items-center space-x-3">
            <button @click="goBack" class="text-gray-700 hover:text-gray-900">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div>
              <div class="font-bold text-xl text-gray-800">MY WORK</div>
            </div>
          </div>
          <button @click="goBack" class="text-gray-700 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="max-w-md mx-auto px-4 py-20">
      <div class="flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <span class="ml-3 text-gray-600">กำลังโหลดข้อมูล...</span>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-md mx-auto px-4 py-6">
      <!-- Filters -->
      <div class="mb-6">
        <div class="grid grid-cols-2 gap-3 mb-3">
          <select v-model="selectedProject" @change="filterTasks"
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกโครงการ</option>
            <option v-for="project in projects" :key="project.ID" :value="project.ID">
              {{ project.ProjectName }}
            </option>
          </select>

          <div class="relative">
            <select v-model="selectedDateRange" @change="filterTasks"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
              <option value="">วันที่เริ่มต้น - สิ้นสุด</option>
              <option value="thisWeek">สัปดาห์นี้</option>
              <option value="thisMonth">เดือนนี้</option>
            </select>
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="text-right">
          <span class="text-sm text-gray-500">ค้นหาเพิ่มเติม</span>
        </div>
      </div>

      <!-- Summary Title -->
      <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">รายการงานของฉัน</h2>

        <!-- Main Stats Card -->
        <div class="bg-teal-400 rounded-lg p-6 mb-4 text-center">
          <div class="text-white text-sm mb-2">งานที่ได้รับมอบหมายทั้งหมด</div>
          <div class="bg-white rounded-lg py-3 mx-4">
            <div class="text-teal-400 text-4xl font-bold">{{ stats.totalTasks }}</div>
          </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-3 gap-3 mb-6">
          <div class="bg-red-500 rounded-lg p-4 text-center">
            <div class="text-white mb-2">
              <svg class="w-6 h-6 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                  clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="text-white text-xs mb-1">รอดำเนินการ</div>
            <div class="bg-white rounded text-red-500 text-2xl font-bold py-1">{{ stats.pendingTasks }}</div>
          </div>

          <div class="bg-orange-500 rounded-lg p-4 text-center">
            <div class="text-white mb-2">
              <svg class="w-6 h-6 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                  clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="text-white text-xs mb-1">กำลังดำเนินการ</div>
            <div class="bg-white rounded text-orange-500 text-2xl font-bold py-1">{{ stats.inProgressTasks }}</div>
          </div>

          <div class="bg-green-500 rounded-lg p-4 text-center">
            <div class="text-white mb-2">
              <svg class="w-6 h-6 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"></path>
              </svg>
            </div>
            <div class="text-white text-xs mb-1">เสร็จสิ้น</div>
            <div class="bg-white rounded text-green-500 text-2xl font-bold py-1">{{ stats.completedTasks }}</div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mb-6">
          <button
            class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg text-sm font-medium flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"></path>
            </svg>
            เมนูรายการ
          </button>
          <button
            class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-lg text-sm font-medium flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z"
                clip-rule="evenodd"></path>
            </svg>
            เมนูปฏิทิน
          </button>
        </div>
      </div>

      <!-- Task List -->
      <div class="space-y-4">
        <TaskCard v-for="task in filteredTasks" :key="task.ID" :task="task" @update="loadData" />
      </div>

      <!-- Empty State -->
      <div v-if="filteredTasks.length === 0" class="text-center py-12">
        <div class="text-4xl mb-4">📋</div>
        <h3 class="text-lg font-semibold text-gray-600 mb-2">
          ไม่พบงานที่ตรงกับเงื่อนไข
        </h3>
        <p class="text-gray-500 text-sm">
          ลองเปลี่ยนเงื่อนไขการค้นหาหรือกรองข้อมูล
        </p>
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
  data() {
    return {
      loading: true,
      tasks: [],
      projects: [],
      filteredTasks: [],
      selectedProject: '',
      selectedDateRange: '',
      currentUser: null,
      stats: {
        totalTasks: 0,
        pendingTasks: 0,
        inProgressTasks: 0,
        completedTasks: 0
      }
    }
  },

  async mounted() {
    await this.loadData()
  },

  methods: {
    async loadData() {
      this.loading = true
      try {
        console.log('Loading tasks data...')
        const tasksResponse = await Nova.request().get('/nova-vendor/my-tasks/my-tasks')
        console.log('Tasks response:', tasksResponse.data)
        this.tasks = tasksResponse.data.tasks || []
        this.projects = tasksResponse.data.projects || []
        this.currentUser = tasksResponse.data.user

        console.log('Tasks loaded:', this.tasks)
        this.calculateStats()
        this.filterTasks()

      } catch (error) {
        console.error('Error loading data:', error)
        this.$toasted.error('เกิดข้อผิดพลาดในการโหลดข้อมูล')
      } finally {
        this.loading = false
      }
    },

    calculateStats() {
      this.stats.totalTasks = this.tasks.length
      this.stats.pendingTasks = this.tasks.filter(task => task.Status === 'NEW').length
      this.stats.inProgressTasks = this.tasks.filter(task => task.Status === 'IN_PROGRESS').length
      this.stats.completedTasks = this.tasks.filter(task => task.Status === 'COMPLETED').length
    },

    filterTasks() {
      let filtered = [...this.tasks]

      if (this.selectedProject) {
        filtered = filtered.filter(task => task.ProjectInfoID == this.selectedProject)
      }

      this.filteredTasks = filtered
    },

    goBack() {
      // Navigate back to MyworkHub
      window.location.href = '/nova/mywork-hub'
    }
  },

  components: {
    TaskCard: {
      props: ['task'],
      emits: ['update'],
      template: `
        <div class="bg-gray-500 rounded-lg overflow-hidden shadow-sm">
          <!-- Issue & Task Header with Status Color -->
          <div :class="getHeaderClass()" class="p-4">
            <div class="text-white font-medium text-sm mb-1">
              Issue : {{ task.IssueName }}
            </div>
            <div class="text-white text-sm">
              • Task : {{ task.IssueDescription }}
            </div>
          </div>
          
          <!-- Gray Content Area -->
          <div class="bg-gray-200 p-4 pt-0">
            <!-- Task Details Grid -->
            <div class="p-3 mb-3 text-white">
              <div class="grid grid-cols-2 gap-y-2 text-xs">
                <div>
                  <span class="text-gray-400">• โครงการ:</span>
                  <div class="text-blue-600 font-medium">{{ task.project?.ProjectName || 'MOC Frontend' }}</div>
                </div>
                <div>
                  <span class="text-gray-400">• ประเภท:</span>
                  <div class="text-black font-medium">{{ task.project?.ProjectType || 'MOC Frontend' }}</div>
                </div>
              </div>
            </div>

            <!-- Status Buttons -->
            <div class="flex gap-2 mb-3">
              <button 
                v-if="task.Status === 'COMPLETED'"
                class="bg-green-600 text-white text-xs px-3 py-1 rounded-full flex items-center gap-1"
              >
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                เสร็จสิ้น
              </button>
              <button 
                v-else-if="task.Status === 'NEW'"
                class="bg-red-500 text-white text-xs px-3 py-1 rounded-full flex items-center gap-1"
              >
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                รอดำเนินการ
              </button>
              <button 
                v-else
                class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full"
              >
                กำลังดำเนินการ
              </button>
            </div>

            <!-- Details Toggle Button -->
            <button 
              @click="toggleDetails"
              class="w-full bg-gray-600 text-white text-xs py-2 rounded flex items-center justify-center gap-1 hover:bg-gray-700 transition-colors"
            >
              <span>รายละเอียดงาน</span>
              <svg 
                class="w-4 h-4 transform transition-transform duration-300" 
                :class="{ 'rotate-180': showDetails }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          
          <!-- Expanded Details -->
          <div v-show="showDetails" class="bg-gray-200 bg-opacity-100 px-4 py-4 border-t border-gray-300 transition-all duration-300 ease-in-out">
            <div class="text-sm text-gray-700">
              <div class="font-semibold mb-3 text-gray-800">รายละเอียดงาน</div>
              
              <!-- Date Information -->
              <div class="space-y-3 mb-4">
                <div class="flex justify-between items-start">
                  <span class="text-gray-600 text-xs">• วันที่การประเมิน:</span>
                  <span class="font-medium text-xs text-right">15/08/2568, 15 ชั่วโมง (2 วัน)</span>
                </div>
                <div class="flex justify-between items-start">
                  <span class="text-gray-600 text-xs">• วันที่ทำจริง:</span>
                  <span class="font-medium text-red-600 text-xs text-right">28 - 30/08/2568 , 20 ชั่วโมง (2.5 วัน)</span>
                </div>
                <div class="flex justify-between items-start">
                  <span class="text-gray-600 text-xs">• หมายเหตุ:</span>
                  <span class="font-medium text-xs text-right">TEST</span>
                </div>
              </div>

              <!-- Progress Section -->
              <div class="border-t border-gray-200 pt-3">
                <div class="font-semibold mb-3 text-gray-800 text-xs">การดำเนินงานข้อมูล</div>
                <div class="grid grid-cols-2 gap-4 text-xs">
                  <div>
                    <span class="text-gray-600">• ความคืบหน้า:</span>
                    <div class="font-medium text-blue-600">{{ getProgress() }}</div>
                  </div>
                  <div>
                    <span class="text-gray-600">• ผู้รับผิดชอบ:</span>
                    <div class="font-medium">{{ getAssignee() }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `,
      data() {
        return {
          showDetails: false
        }
      },
      methods: {
        toggleDetails() {
          this.showDetails = !this.showDetails
        },

        getProgress() {
          if (this.task.Status === 'COMPLETED') return '100%'
          if (this.task.Status === 'IN_PROGRESS') return '50%'
          return '0%'
        },

        getAssignee() {
          if (this.task.assignee?.FirstName && this.task.assignee?.LastName) {
            return `${this.task.assignee.FirstName} ${this.task.assignee.LastName}`
          }
          return 'ผู้ดูแลระบบ เอ็มไอซีที'
        },

        getHeaderClass() {
          const baseClass = 'transition-all duration-200'
          switch (this.task.Status) {
            case 'NEW':
              return `bg-red-500 ${baseClass}`
            case 'IN_PROGRESS':
              return `bg-orange-500 ${baseClass}`
            case 'COMPLETED':
              return `bg-green-500 ${baseClass}`
            default:
              return `bg-gray-600 ${baseClass}`
          }
        },

        getCardClass() {
          return 'bg-gray-500 transition-all duration-200'
        }
      }
    }
  }
}
</script>

<style scoped>
/* Custom select styling */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}
</style>
