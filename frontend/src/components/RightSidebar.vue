<!-- src/components/RightSidebar.vue -->
<template>
  <div class="bg-white h-full w-64 p-3 flex flex-col">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-4">
      <img :src="avatarSrc" alt="User Avatar" class="w-8 h-8 rounded-full cursor-pointer hover:opacity-80" @click="goToProfile">
      <div class="flex space-x-1">
        <button v-for="icon in icons" :key="icon" 
                @click="handleIconClick(icon)"
                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition duration-150">
          <svg-icon :name="icon" class="w-4 h-4 text-gray-600" />
        </button>
      </div>
    </div>

    <!-- Notifications Section -->
    <div v-if="showNotifications" class="mb-4">
      <h2 class="text-lg font-semibold mb-2">Notifications</h2>
      <div v-if="isLoading" class="text-center py-4">
        Loading...
      </div>
      <div v-else-if="notifications.length === 0" class="text-center py-4 text-gray-500">
        No notifications
      </div>
      <div v-else class="space-y-2 max-h-96 overflow-y-auto">
        <div v-for="notification in notifications" :key="notification.id" 
             class="bg-gray-50 rounded p-2 text-sm hover:bg-gray-100 transition duration-150">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium">{{ getNotificationTitle(notification) }}</p>
              <p class="text-xs text-gray-500">{{ notification.data.message }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ formatDate(notification.created_at) }}</p>
            </div>
            <button @click="removeNotification(notification.id)" 
                    class="text-red-500 hover:text-red-700 transition duration-150 bg-[#f9fafb]">
              <svg-icon name="trash" class="w-3 h-3" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Follow Updates Section -->
    <div v-if="!showNotifications" class="mb-4">
      <h2 class="text-lg font-semibold mb-2">Follow Updates</h2>
      <div v-if="followNotifications.length === 0" class="text-center py-4 text-gray-500">
        No follow updates
      </div>
      <div v-else class="space-y-2">
        <div v-for="notification in followNotifications" :key="notification.id" 
             class="bg-gray-50 rounded p-2 text-sm hover:bg-gray-100 transition duration-150">
          <div class="flex items-center mb-1">
            <img :src="notification.data.follower_avatar || defaultAvatar" :alt="notification.data.follower_name" 
                 class="w-6 h-6 rounded-full mr-2">
            <div>
              <p class="font-medium">{{ notification.data.follower_name }}</p>
              <p class="text-xs text-gray-500">{{ notification.data.message }}</p>
            </div>
          </div>
          <div class="flex space-x-1 mt-2">
            <button @click="removeNotification(notification.id)" 
                    class="flex-1 bg-gray-200 text-gray-800 py-1 px-2 rounded text-xs font-medium hover:bg-gray-300 transition duration-150">
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
        <h2 class="text-lg font-semibold">Suggested Users</h2>
        <button @click="fetchSuggestedUsers" class="text-xs text-blue-500 font-medium hover:underline bg-white">
          <svg-icon name="refresh" class="w-4 h-4 bg-white" />
        </button>
      </div>
      <div v-if="loadingSuggestedUsers" class="text-center py-4">
        Loading...
      </div>
      <div v-else-if="suggestedUsers.length === 0" class="text-center py-4 text-gray-500">
        No suggestions available
      </div>
      <div v-else class="space-y-2">
        <div v-for="user in suggestedUsers" :key="user.id" 
             class="flex items-center justify-between py-2 hover:bg-gray-50 rounded transition duration-150">
          <div class="flex items-center">
            <img :src="user.avatar || defaultAvatar" :alt="user.name" class="w-8 h-8 rounded-full mr-2">
            <div>
              <p class="text-sm font-medium">{{ user.name }}</p>
              <p class="text-xs text-gray-500">@{{ user.username }}</p>
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

<script>
import { ref, computed, onMounted } from 'vue'
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
    const avatarSrc = 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg'
    const icons = ['chatteardropdots', 'notification', 'gearsix']
    const defaultAvatar = 'path/to/default/avatar.png'

    const notifications = computed(() => store.getters['notification/allNotifications'])
    const followNotifications = computed(() => store.getters['notification/followNotifications'])
    const isLoading = computed(() => store.getters['notification/isLoading'])

    const suggestedUsers = ref([])
    const loadingSuggestedUsers = ref(false)

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
        // Remove the notification from the list after following
        await store.dispatch('notification/removeNotification', notification.id)
      } catch (error) {
        console.error('Error following user:', error)
        // Remove the notification from the list even if there's an error
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
        // User was successfully followed or already being followed
        suggestedUsers.value = suggestedUsers.value.filter(user => user.id !== userId)
      } catch (error) {
        console.error('Error following user:', error)
        // You can add user feedback here, e.g., showing a toast notification
        suggestedUsers.value = suggestedUsers.value.filter(user => user.id !== userId)
      }
    }

    const goToProfile = () => {
      router.push({ name: 'UserProfile' })
    }

    onMounted(() => {
      store.dispatch('notification/fetchNotifications')
      fetchSuggestedUsers()
    })

    return {
      avatarSrc,
      icons,
      notifications,
      followNotifications,
      isLoading,
      showNotifications,
      suggestedUsers,
      loadingSuggestedUsers,
      defaultAvatar,
      handleIconClick,
      removeNotification,
      handleFollowAction,
      getNotificationTitle,
      formatDate,
      fetchSuggestedUsers,
      followUser,
      goToProfile,
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
  .flex-1.flex > .flex-shrink-0 {
    display: none;
  }
}
</style>