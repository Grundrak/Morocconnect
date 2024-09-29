<template>
  <div class="flex flex-col h-screen"
    :class="{ 'bg-gray-100 text-gray-900': !isDarkMode, 'bg-gray-900 text-white': isDarkMode }">

    <AdminNavbar />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <div class="w-64 bg-[#3b5e81] text-white cursor-pointer">
        <div class="p-4">
          <nav>
            <a v-for="tab in tabs" :key="tab.name" @click="activeTab = tab.name"
              class="flex items-center py-2 px-4 mt-2 rounded transition-colors duration-200 ease-in-out text-white"
              :class="{ 'bg-[#85AAF2]': activeTab === tab.name, 'hover:bg-[#85AAF2]': activeTab !== tab.name }">
              <svg-icon :name="tab.icon" class="w-5 h-5 mr-2 text-white" />
              {{ tab.label }}
            </a>
          </nav>
        </div>
      </div>

      <!-- Content area -->
      <div class="flex-1 p-5 overflow-y-auto">
        <!-- Dashboard Overview -->
        <div v-if="activeTab === 'home'">
          <!-- 4 Cards at the top -->
          <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <DashboardCard v-for="card in dashboardCards" :key="card.title" v-bind="card" />
          </div>

          <!-- 2 Charts at the bottom -->
          <div class="grid grid-cols-2 lg:grid-cols-2 gap-4 mb-8">
            <div v-for="chart in dashboardCharts" :key="chart.title" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
              <h3 class="text-lg font-semibold mb-4">{{ chart.title }}</h3>
              <component :is="chart.component" :data="dashboardData[chart.dataKey]" />
            </div>
          </div>
        </div>

        <!-- Tables -->
        <component :is="activeComponent" v-else />
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import SvgIcon from '@/components/UserComponents/SvgIcon.vue'
import UsersTable from '@/components/AdminComponents/UsersTable.vue'
import PostsTable from '@/components/AdminComponents/PostsTable.vue'
import CommentsTable from '@/components/AdminComponents/CommentsTable.vue'
import DashboardCard from '@/components/AdminComponents/DashboardCard.vue'
import UserGrowthChart from '@/components/AdminComponents/UserGrowthChart.vue'
import PostActivityChart from '@/components/AdminComponents/PostActivityChart.vue'
import AdminNavbar from '../components/AdminComponents/navbar.vue'

export default {
  name: 'AdminDashboard',
  components: {
    AdminNavbar,
    SvgIcon,
    UsersTable,
    PostsTable,
    CommentsTable,
    DashboardCard,
    UserGrowthChart,
    PostActivityChart
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const activeTab = ref('home')
    const isDarkMode = ref(localStorage.getItem('darkMode') === 'true')

    const currentUser = computed(() => store.getters['auth/currentUser'])
    const dashboardData = computed(() => store.getters['admin/dashboardData'])

    const tabs = [
      { name: 'home', label: 'Dashboard', icon: 'house' },
      { name: 'users', label: 'Users', icon: 'usersadmin' },
      { name: 'posts', label: 'Posts', icon: 'document-text' },
      { name: 'comments', label: 'Comments', icon: 'chat' },
    ]

    const dashboardCards = computed(() => [
      { title: "Total Users", value: dashboardData.value.totalUsers, icon: "user", color: "blue" },
      { title: "Total Posts", value: dashboardData.value.totalPosts, icon: "document-text", color: "green" },
      { title: "Total Comments", value: dashboardData.value.totalComments, icon: "chat", color: "yellow" },
      { title: "Active Users", value: dashboardData.value.activeUsers, icon: "users", color: "purple" },
    ])

    const dashboardCharts = [
      { title: "User Growth", component: UserGrowthChart, dataKey: "userGrowthData" },
      { title: "Post Activity", component: PostActivityChart, dataKey: "postActivityData" },
    ]

    const activeComponent = computed(() => {
      switch (activeTab.value) {
        case 'users': return UsersTable
        case 'posts': return PostsTable
        case 'comments': return CommentsTable
        default: return null
      }
    })

    const fetchDashboardData = async () => {
      await store.dispatch('admin/fetchDashboardData')
    }

    onMounted(() => {
      fetchDashboardData()
      store.dispatch('auth/fetchUser')  // Fetch user data on mount
    })

    return {
      activeTab,
      isDarkMode,
      currentUser,
      dashboardData,
      activeComponent,
      tabs,
      dashboardCards,
      dashboardCharts,
    }
  }
}
</script>