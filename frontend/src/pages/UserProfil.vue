<template>
  <div>
    <Navbar />
    <div v-if="isLoading" class="text-center py-4">
      Loading...
    </div>
    <div v-else-if="error" class="text-center py-4 text-red-500">
      {{ error }}
    </div>
    <div v-else-if="user" class="bg-gray-100 min-h-screen">
      <!-- Cover Photo -->
      <div class="h-64 bg-gradient-to-r from-blue-600 via-purple-500 to-purple-800"></div>

      <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg -mt-20 p-8">
        <div class="flex justify-between items-start mb-8">
          <div class="flex items-center">
            <img :src="avatarUrl" alt="User Avatar" class="w-32 h-32 rounded-full mr-6 border-4 border-white">
            <div>
              <h3 class="text-3xl font-bold">{{ user.name }}</h3>
              <p class="text-gray-600">@{{ user.username }}</p>
              <p class="text-gray-600">{{ user.email }}</p>
              <p class="text-gray-600">From: {{ user.location }}</p>
              <p class="text-gray-600">Bio: {{ user.bio }}</p>
              <p class="text-gray-600">Birthday: {{ formatDate(user.birthday) }}</p>
            </div>
          </div>
          <div class="flex flex-col items-end">
            <div class="flex mb-2">
              <p class="mr-4"><strong>{{ user.followers_count }}</strong> Followers</p>
              <p><strong>{{ user.following_count }}</strong> Following</p>
            </div>
            <button v-if="isCurrentUser" @click="showEditForm = true"
              class="bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-600">
              Edit Profile
            </button>
            <button v-else @click="toggleFollow"
              class="bg-blue-500 text-white py-2 px-4 rounded-full hover:bg-blue-600">
              {{ isFollowing ? 'Unfollow' : 'Follow' }}
            </button>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-lg font-semibold mb-2">Badges</h4>
            <div class="flex flex-wrap gap-2">
              <span v-for="badge in user.badges" :key="badge.id"
                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">
                {{ badge.name }}
              </span>
            </div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-lg font-semibold mb-2">Recent Posts</h4>
            <ul class="list-disc list-inside">
              <li v-for="post in user.recent_posts" :key="post.id" class="truncate">
                {{ post.content }}
              </li>
            </ul>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-lg font-semibold mb-2">Recent Comments</h4>
            <ul class="list-disc list-inside">
              <li v-for="comment in user.recent_comments" :key="comment.id" class="truncate">
                {{ comment.content }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <EditProfile v-if="showEditForm" :user="user" @close="showEditForm = false" @update="updateProfile" />
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import EditProfile from './EditProfile.vue'
import Navbar from '../components/AdminComponents/navbar.vue'

export default {
  name: 'UserProfil',
  components: { EditProfile, Navbar },
  setup() {
    const store = useStore()
    const route = useRoute()
    const user = ref(null)
    const currentUser = computed(() => store.getters['auth/currentUser'])
    const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'
    const isLoading = ref(true)
    const error = ref(null)
    const showEditForm = ref(false)
    const isFollowing = ref(false)

    const isCurrentUser = computed(() => user.value?.id === currentUser.value?.id)

    const avatarUrl = computed(() => {
      if (!user.value || !user.value.avatar) return defaultAvatar
      return user.value.avatar.startsWith('http') ? user.value.avatar : `${import.meta.env.VITE_API_URL}/storage/${user.value.avatar}`
    })

    const fetchUserProfile = async (userId) => {
      isLoading.value = true
      error.value = null
      try {
        const userData = await store.dispatch('user/fetchUserProfile', userId)
        user.value = userData
        isFollowing.value = userData.is_followed_by_current_user || false
      } catch (err) {
        console.error('Error fetching user profile:', err)
        error.value = 'Failed to load user profile. Please try again.'
      } finally {
        isLoading.value = false
      }
    }

    const toggleFollow = async () => {
      try {
        const action = isFollowing.value ? 'user/unfollowUser' : 'user/followUser'
        await store.dispatch(action, user.value.id)
        isFollowing.value = !isFollowing.value
        await fetchUserProfile(user.value.id)
      } catch (error) {
        console.error('Error toggling follow:', error)
      }
    }

    const updateProfile = async () => {
      await fetchUserProfile(user.value.id)
      showEditForm.value = false
    }

    const formatDate = (dateString) => {
      if (!dateString) return 'Not specified'
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    }


    onMounted(async () => {
      if (route.params.id) {
        await fetchUserProfile(route.params.id)
      }
    })

    watch(() => route.params.id, async (newId) => {
      if (newId) {
        await fetchUserProfile(newId)
      }
    })

    return {
      user,
      isCurrentUser,
      isFollowing,
      defaultAvatar,
      isLoading,
      error,
      showEditForm,
      toggleFollow,
      updateProfile,
      avatarUrl,
      formatDate
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.bg-white {
  font-family: 'Inter', sans-serif;
}
</style>