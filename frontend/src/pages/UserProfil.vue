<!-- src/components/UserProfile.vue -->
<template>
  <div v-if="isLoading" class="text-center py-4">
    Loading...
  </div>
  <div v-else class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center">
        <img :src="user.avatar || defaultAvatar" alt="User Avatar" class="w-24 h-24 rounded-full mr-6">
        <div>
          <h3 class="text-2xl font-medium">{{ user.name }}</h3>
          <p class="text-gray-500">{{ user.email }}</p>
        </div>
      </div>
      <div>
        <button @click="editProfile" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 mr-2">
          Edit
        </button>
        <button @click="goToHome" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">
          Back to Home
        </button>
      </div>
    </div>
    <form @submit.prevent="updateProfile">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
          <label class="block text-gray-700 mb-2">Full Name</label>
          <input v-model="form.name" type="text" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Username</label>
          <input v-model="form.username" type="text" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Email</label>
          <input v-model="form.email" type="email" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Password</label>
          <input v-model="form.password" type="password" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Bio</label>
          <textarea v-model="form.bio" class="w-full p-3 border rounded"></textarea>
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Location</label>
          <input v-model="form.location" type="text" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Birthday</label>
          <input v-model="form.birthday" type="date" class="w-full p-3 border rounded" />
        </div>
        <div>
          <label class="block text-gray-700 mb-2">Avatar</label>
          <input type="file" @change="handleAvatarUpload" class="w-full p-3 border rounded" />
        </div>
      </div>
      <button type="submit" class="bg-blue-500 text-white py-3 px-6 rounded hover:bg-blue-600">
        Update Profile
      </button>
    </form>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'UserProfile',
  setup() {
    const store = useStore()
    const router = useRouter()
    const user = computed(() => store.getters['user/currentUser'])
    const defaultAvatar = '..\icons\defaultAvatar.svg'
    const isLoading = ref(true)

    const form = ref({
      name: '',
      username: '',
      email: '',
      password: '',
      avatar: '',
      bio: '',
      location: '',
      birthday: ''
    })

    onMounted(async () => {
      try {
        await store.dispatch('user/fetchCurrentUser')
        if (user.value) {
          form.value = { ...user.value }
        }
      } catch (error) {
        console.error('Error fetching current user:', error)
      } finally {
        isLoading.value = false
      }
    })

    const updateProfile = async () => {
      try {
        await store.dispatch('user/updateProfile', form.value)
        alert('Profile updated successfully')
      } catch (error) {
        console.error('Error updating profile:', error)
        alert('Failed to update profile')
      }
    }

    const handleAvatarUpload = async (event) => {
      const file = event.target.files[0]
      if (file) {
        const formData = new FormData()
        formData.append('avatar', file)
        try {
          const response = await store.dispatch('user/uploadAvatar', formData)
          form.value.avatar = response.data.avatar
        } catch (error) {
          console.error('Error uploading avatar:', error)
          alert('Failed to upload avatar')
        }
      }
    }

    const editProfile = () => {
      // Logic to enable editing the profile
    }

    const goToHome = () => {
      router.push({ name: 'Home' })
    }

    return {
      user,
      form,
      defaultAvatar,
      isLoading,
      updateProfile,
      handleAvatarUpload,
      editProfile,
      goToHome
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.bg-white {
  font-family: 'Inter', sans-serif;
}

input[type="file"] {
  padding: 0;
}

button svg {
  width: 1em;
  height: 1em;
}
</style>