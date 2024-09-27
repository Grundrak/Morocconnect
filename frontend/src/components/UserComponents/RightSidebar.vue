<template>
  <Suspense>
    <template #default>
      <div v-if="currentUser" class="bg-white dark:bg-gray-800 h-full w-64 p-3 flex flex-col">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-4 relative">
          <div @click="toggleProfileDropdown" class="flex items-center cursor-pointer">
            <img :src="getAvatarUrl(currentUser.avatar)" alt="User Avatar" class="w-8 h-8 rounded-full mr-2">
            <div class="text-sm">
              <div class="font-medium text-gray-900 dark:text-white">{{ currentUser?.name || 'Guest' }}</div>
              <div class="text-gray-500 dark:text-gray-300">@{{ currentUser?.username || 'guest' }}</div>
            </div>
          </div>
          <div class="flex space-x-1">
            <button v-for="icon in icons" :key="icon" @click="handleIconClick(icon)"
              class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-150">
              <svg-icon :name="icon" class="w-4 h-4 text-gray-600 dark:text-gray-300" />
            </button>
          </div>

          <!-- Profile Dropdown -->
          <div v-if="showProfileDropdown"
            class="absolute top-full left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10">
            <div class="py-1">
              <a @click="goToProfile"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                View Profile
              </a>
              <a v-if="isAdmin" @click="goToAdminDashboard"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                Admin Dashboard
              </a>
              <a @click="logout"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                Log Out
              </a>
              <div class="border-t border-gray-200 dark:border-gray-600"></div>
              <div class="px-4 py-2 flex items-center justify-between">
                <span class="text-sm text-gray-700 dark:text-gray-200 cursor-pointer">Dark Mode</span>
                <button @click="toggleDarkMode"
                  class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-200 ease-in-out cursor-pointer"
                  :class="{ 'bg-blue-600': isDarkMode, 'bg-gray-200': !isDarkMode }">
                  <span
                    class="inline-block w-4 h-4 transform transition-transform duration-200 ease-in-out bg-white rounded-full"
                    :class="{ 'translate-x-6': isDarkMode, 'translate-x-1': !isDarkMode }"></span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Notifications Section -->
        <div v-if="showNotifications" class="mb-4">
          <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Notifications</h2>
          <div v-if="isLoading" class="text-center py-4 text-gray-600 dark:text-gray-400">
            Loading...
          </div>
          <div v-else-if="notifications.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
            No notifications
          </div>
          <div v-else class="space-y-2 max-h-96 overflow-y-auto">
            <div v-for="notification in notifications" :key="notification.id"
              class="bg-gray-50 dark:bg-gray-700 rounded p-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150">
              <div class="flex items-center justify-between">
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ getNotificationTitle(notification) }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ notification.data.message }}</p>
                  <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ formatDate(notification.created_at) }}</p>
                </div>
                <button @click="removeNotification(notification.id)"
                  class="text-red-500 hover:text-red-700 transition duration-150 bg-transparent">
                  <svg-icon name="trash" class="w-3 h-3" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Follow Updates Section -->
        <div v-if="!showNotifications" class="mb-4">
          <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Follow Updates</h2>
          <div v-if="followNotifications.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
            No follow updates
          </div>
          <div v-else class="space-y-2">
            <div v-for="notification in followNotifications" :key="notification.id"
              class="bg-gray-50 dark:bg-gray-700 rounded p-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150">
              <div class="flex items-center mb-1">
                <img :src="notification.data.follower_avatar || defaultAvatar" :alt="notification.data.follower_name"
                  class="w-6 h-6 rounded-full mr-2">
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ notification.data.follower_name }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ notification.data.message }}</p>
                </div>
              </div>
              <div class="flex space-x-1 mt-2">
                <button @click="removeNotification(notification.id)"
                  class="flex-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 py-1 px-2 rounded text-xs font-medium hover:bg-gray-300 dark:hover:bg-gray-500 transition duration-150">
                  Remove
                </button>
                <button @click="handleFollowAction(notification)"
                  class="flex-1 bg-blue-500 text-white py-1 px-2 rounded text-xs font-medium hover:bg-blue-600 transition duration-150">
                  {{ notification.type.includes('Unfollow') ? 'Follow Back' : 'View Profile' }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Suggested Users to Follow Section -->
        <div v-if="!showNotifications">
          <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Suggested Users</h2>
            <button @click="fetchSuggestedUsers"
              class="text-xs text-blue-500 font-medium hover:underline bg-transparent">
              <svg-icon name="refresh" class="w-4 h-4" />
            </button>
          </div>
          <div v-if="loadingSuggestedUsers" class="text-center py-4 text-gray-600 dark:text-gray-400">
            Loading...
          </div>
          <div v-else-if="suggestedUsers.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
            No suggestions available
          </div>
          <div v-else class="space-y-2">
            <div v-for="user in suggestedUsers" :key="user.id"
              class="flex items-center justify-between py-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition duration-150">
              <div class="flex items-center">
                <img :src="getAvatarUrl(user.avatar)" :alt="user.name" class="w-8 h-8 rounded-full mr-2">
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">@{{ user.username }}</p>
                </div>
              </div>
              <button @click="followUser(user.id)"
                class="bg-blue-500 text-white py-1 px-2 rounded text-xs font-medium hover:bg-blue-600 transition duration-150">
                Follow
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template #fallback>
      <div class="h-full w-64 flex items-center justify-center">
        <p>Loading...</p>
      </div>
    </template>
  </Suspense>
</template>

<script>
import { ref, computed, onMounted, watch, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import SvgIcon from './SvgIcon.vue'

export default {
  name: 'RightSidebar',
  components: { SvgIcon },
  setup() {
    const store = useStore()
    const router = useRouter()
    const showNotifications = ref(false)
    const showProfileDropdown = ref(false)
    const isDarkMode = ref(false)
    const avatarSrc = 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg'
    const icons = ['chatteardropdots', 'notification', 'gearsix']
    const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'

    const currentUser = computed(() => store.getters['auth/currentUser'])
    const notifications = computed(() => store.getters['notification/allNotifications'])
    const followNotifications = computed(() => store.getters['notification/followNotifications'])
    const isLoading = computed(() => store.getters['notification/isLoading'])

    const suggestedUsers = ref([])
    const loadingSuggestedUsers = ref(false)
    const isAdmin = computed(() => {
      return currentUser.value && currentUser.value.roles.some(role => role.name === 'Admin')
    })

    const goToAdminDashboard = () => {
      cleanup()
      router.push('/admin-dashboard')
    }
    const toggleProfileDropdown = () => {
      showProfileDropdown.value = !showProfileDropdown.value
    }

    const handleIconClick = async (icon) => {
      if (icon === 'notification') {
        await store.dispatch('notification/fetchNotifications')
        showNotifications.value = !showNotifications.value
      } else {
        console.log(`Clicked on ${icon} icon`)
      }
    }

    const removeNotification = async (id) => {
      try {
        await store.dispatch('notification/removeNotification', id)
      } catch (error) {
        console.error('Error removing notification:', error)
      }
    }

    const handleFollowAction = async (notification) => {
      try {
        await store.dispatch('user/followUser', notification.data.follower_id)
        await store.dispatch('notification/removeNotification', notification.id)
      } catch (error) {
        console.error('Error following user:', error)
        await store.dispatch('notification/removeNotification', notification.id)
      }
    }

    const getNotificationTitle = (notification) => {
      const types = {
        'App\\Notifications\\NewFollowerNotification': 'New Follower',
        'App\\Notifications\\UnfollowNotification': 'Unfollow',
        // Add more notification types here
      }
      return types[notification.type] || 'Notification'
    }

    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric'
      })
    }

    const fetchSuggestedUsers = async () => {
      loadingSuggestedUsers.value = true
      try {
        await store.dispatch('user/fetchSuggestedUsers')
        suggestedUsers.value = store.getters['user/suggestedUsers']
      } catch (error) {
        console.error('Error fetching suggested users:', error)
      } finally {
        loadingSuggestedUsers.value = false
      }
    }

    const followUser = async (userId) => {
      try {
        await store.dispatch('user/followUser', userId)
        suggestedUsers.value = suggestedUsers.value.filter(user => user.id !== userId)
      } catch (error) {
        console.error('Error following user:', error)
        suggestedUsers.value = suggestedUsers.value.filter(user => user.id !== userId)
      }
    }

    const goToProfile = async () => {
      if (currentUser.value && currentUser.value.id) {
        cleanup()
        await router.push({ name: 'UserProfil', params: { id: currentUser.value.id } })
      } else {
        console.error('Current user ID is not available')
      }
    }


    const logout = async () => {
      cleanup()
      await store.dispatch('auth/logout')
      await router.push({ name: 'Login' })
    }


    const toggleDarkMode = () => {
      isDarkMode.value = !isDarkMode.value
      localStorage.setItem('darkMode', isDarkMode.value)
      applyDarkMode()
    }

    const applyDarkMode = () => {
      if (isDarkMode.value) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }

    onMounted(() => {
      isDarkMode.value = localStorage.getItem('darkMode') === 'true'
      applyDarkMode()
      store.dispatch('notification/fetchNotifications')
      fetchSuggestedUsers()
    })

    // Close dropdown when clicking outside
    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        showProfileDropdown.value = false
      }
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    const cleanup = () => {
      showNotifications.value = false
      showProfileDropdown.value = false
      suggestedUsers.value = []
      loadingSuggestedUsers.value = false
    }
    const getAvatarUrl = (avatar) => {
      if (!avatar) return defaultAvatar;
      if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
        return avatar;
      }
      return `${import.meta.env.VITE_API_URL}/storage/${avatar}`;
    };

    onBeforeUnmount(() => {
      cleanup()
      document.removeEventListener('click', handleClickOutside)
    })

    watch(isDarkMode, () => {
      applyDarkMode()
    })
    return {
      currentUser,
      avatarSrc,
      icons,
      notifications,
      followNotifications,
      isLoading,
      showNotifications,
      suggestedUsers,
      loadingSuggestedUsers,
      defaultAvatar,
      getAvatarUrl,
      showProfileDropdown,
      isDarkMode,
      handleIconClick,
      removeNotification,
      handleFollowAction,
      getNotificationTitle,
      formatDate,
      fetchSuggestedUsers,
      followUser,
      toggleProfileDropdown,
      goToProfile,
      logout,
      toggleDarkMode,
      isAdmin,
      goToAdminDashboard
    }
  }
}
</script>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.bg-white {
  font-family: 'Inter', sans-serif;
}

@media (max-width: 1024px) {
  .flex-1.flex>.flex-shrink-0 {
    display: none;
  }
}
</style>