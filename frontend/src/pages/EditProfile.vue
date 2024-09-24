<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-[400px]">
      <h2 class="text-2xl font-bold text-[#09090b] mb-6">Edit Profile</h2>
      <form @submit.prevent="submitForm" class="w-[90%]">
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="form.name" type="text" placeholder="Full Name"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="at-symbol"
              class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="form.username" type="text" placeholder="Username"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="email" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="form.email" type="email" placeholder="Email"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="form.location" type="text" placeholder="Location"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="form.birthday" type="date"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="document-text" class="absolute left-3 top-3 text-gray-400 w-5 h-5" />
            <textarea v-model="form.bio" placeholder="Bio"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
              rows="3"></textarea>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Avatar</label>
          <input type="file" @change="handleAvatarUpload" class="w-full p-2 border rounded-lg" />
        </div>
        <div class="flex justify-between">
          <button type="submit"
            class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
            Update Profile
          </button>
          <button @click="$emit('close')"
            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'
import SvgIcon from '@/components/UserComponents/SvgIcon.vue'

export default {
  name: 'EditProfile',
  components: { SvgIcon },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  emits: ['close', 'update'],
  setup(props, { emit }) {
    const store = useStore()
    const form = ref({
      name: props.user.name,
      username: props.user.username,
      email: props.user.email,
      bio: props.user.bio,
      location: props.user.location,
      birthday: props.user.birthday ? new Date(props.user.birthday).toISOString().split('T')[0] : '',
      avatar: props.user.avatar
    })

    const submitForm = async () => {
      try {
        const result = await store.dispatch('user/updateProfile', form.value)
        if (result.success) {
          emit('update')
        } else {
          console.error('Profile update failed:', result.message)
        }
      } catch (error) {
        console.error('Error updating profile:', error.response?.data?.message || error.message)
      }
    }
    const handleAvatarUpload = async (event) => {
      const file = event.target.files[0]
      if (file) {
        try {
          // Convert file to base64 string
          const base64String = await fileToBase64(file)

          const response = await store.dispatch('user/uploadAvatar', { avatar: base64String })
          if (response.success) {
            form.value.avatar = response.data.avatar
          } else {
            console.error('Avatar upload failed:', response.message)
          }
        } catch (error) {
          console.error('Error uploading avatar:', error.response?.data?.message || error.message)
        }
      }
    }

    // Helper function to convert file to base64
    const fileToBase64 = (file) => {
      return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => resolve(reader.result)
        reader.onerror = (error) => reject(error)
      })
    }
    return {
      form,
      submitForm,
      handleAvatarUpload
    }
  }
}
</script>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
  font-family: 'Inter', sans-serif;
}
</style>