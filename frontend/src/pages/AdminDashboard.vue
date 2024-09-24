<template>
  <div class="flex flex-col h-screen"
    :class="{ 'bg-gray-100 text-gray-900': !isDarkMode, 'bg-gray-900 text-white': isDarkMode }">

    <AdminNavbar />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <div class="w-64 bg-[#3b5e81] text-white">
        <div class="p-4">
          <nav>
            <a @click="activeTab = 'home'"
              class="flex items-center py-2 px-4 rounded transition-colors duration-200 ease-in-out text-white"
              :class="{ 'bg-[#85AAF2]': activeTab === 'home', 'hover:bg-[#85AAF2]': activeTab !== 'home' }">
              <svg-icon name="house" class="w-5 h-5 mr-2 text-white" />
              Dashboard
            </a>
            <a @click="activeTab = 'users'"
              class="flex items-center py-2 px-4 mt-2 rounded transition-colors duration-200 ease-in-out text-white"
              :class="{ 'bg-[#85AAF2]': activeTab === 'users', 'hover:bg-[#85AAF2]': activeTab !== 'users' }">
              <svg-icon name="usersadmin" class="w-5 h-5 mr-2 text-white" />
              Users
            </a>
            <a @click="activeTab = 'posts'"
              class="flex items-center py-2 px-4 mt-2 rounded transition-colors duration-200 ease-in-out text-white"
              :class="{ 'bg-[#85AAF2]': activeTab === 'posts', 'hover:bg-[#85AAF2]': activeTab !== 'posts' }">
              <svg-icon name="document-text" class="w-5 h-5 mr-2 text-white" />
              Posts
            </a>
            <a @click="activeTab = 'comments'"
              class="flex items-center py-2 px-4 mt-2 rounded transition-colors duration-200 ease-in-out text-white"
              :class="{ 'bg-[#85AAF2]': activeTab === 'comments', 'hover:bg-[#85AAF2]': activeTab !== 'comments' }">
              <svg-icon name="chat" class="w-5 h-5 mr-2 text-white" />
              Comments
            </a>
          </nav>
        </div>
      </div>

      <!-- Content area -->
      <div class="flex-1 p-5 overflow-y-auto">
        <!-- Dashboard Overview -->
        <div v-if="activeTab === 'home'">
          <!-- 4 Cards at the top -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <DashboardCard title="Total Users" :value="dashboardData.totalUsers" icon="user" color="blue" />
            <DashboardCard title="Total Posts" :value="dashboardData.totalPosts" icon="document-text" color="green" />
            <DashboardCard title="Total Comments" :value="dashboardData.totalComments" icon="chat" color="yellow" />
            <DashboardCard title="Active Users" :value="dashboardData.activeUsers" icon="users" color="purple" />
          </div>

          <!-- 2 Charts at the bottom -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
              <h3 class="text-lg font-semibold mb-4">User Growth</h3>
              <UserGrowthChart :data="dashboardData.userGrowthData" />
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
              <h3 class="text-lg font-semibold mb-4">Post Activity</h3>
              <PostActivityChart :data="dashboardData.postActivityData" />
            </div>
          </div>
        </div>

        <!-- Tables -->
        <component :is="activeComponent" />
      </div>
    </div>

    <!-- Edit User Modal -->
    <EditUserModal v-if="showEditModal" :user="selectedUser" @close="closeEditModal" @save="saveUser" />
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
import EditUserModal from '@/components/AdminComponents/EditUserModel.vue'
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
    EditUserModal,
    UserGrowthChart,
    PostActivityChart
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const activeTab = ref('home')
    const isDarkMode = ref(false)
    const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'
    const isDropdownOpen = ref(false)
    const showEditModal = ref(false)
    const selectedUser = ref(null)

    const currentUser = computed(() => store.getters['auth/currentUser'])
    const dashboardData = computed(() => store.getters['admin/dashboardData'])

    const activeComponent = computed(() => {
      switch (activeTab.value) {
        case 'users':
          return UsersTable
        case 'posts':
          return PostsTable
        case 'comments':
          return CommentsTable
        default:
          return null
      }
    })
    const goToHome = () => {
      router.push('/home')
      isDropdownOpen.value = false
    }
    const fetchDashboardData = async () => {
      await store.dispatch('admin/fetchDashboardData')
    }

    const performSearch = () => {
      // Implement search functionality
      console.log('Performing search...')
    }

    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value
    }

    const logout = () => {
      router.push('/login')
      isDropdownOpen.value = false
    }

    const openEditModal = (user) => {
      selectedUser.value = user
      showEditModal.value = true
    }

    const closeEditModal = () => {
      showEditModal.value = false
      selectedUser.value = null
    }

    const saveUser = (updatedUser) => {
      // Implement save user logic
      console.log('Saving user:', updatedUser)
      closeEditModal()
    }

    onMounted(() => {
      fetchDashboardData()
    })

    return {
      activeTab,
      isDarkMode,
      currentUser,
      defaultAvatar,
      activeComponent,
      isDropdownOpen,
      dashboardData,
      showEditModal,
      selectedUser,
      performSearch,
      toggleDropdown,
      logout,
      openEditModal,
      closeEditModal,
      saveUser,
      goToHome
    }
  }
}
</script>