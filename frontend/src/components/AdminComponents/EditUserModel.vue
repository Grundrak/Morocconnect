<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-[400px]">
      <h2 class="text-2xl font-bold text-[#09090b] dark:text-white mb-6">Edit User</h2>
      <form @submit.prevent="saveUser" class="w-[90%]">
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="editedUser.name" type="text" placeholder="Full Name"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="at-symbol" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="editedUser.username" type="text" placeholder="Username"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="email" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="editedUser.email" type="email" placeholder="Email"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="editedUser.location" type="text" placeholder="Location"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="editedUser.birthday" type="date"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="document-text" class="absolute left-3 top-3 text-gray-400 w-5 h-5" />
            <textarea v-model="editedUser.bio" placeholder="Bio"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              rows="3"></textarea>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 dark:text-gray-300 mb-2">Avatar</label>
          <input type="file" @change="handleAvatarUpload" class="w-full p-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
        </div>
        <div class="flex justify-between">
          <button type="submit"
            class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
            Update User
          </button>
          <button @click="$emit('close')"
            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import SvgIcon from '@/components/UserComponents/SvgIcon.vue'

export default {
  name: 'EditUserModal',
  components: { SvgIcon },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  emits: ['close', 'save'],
  setup(props, { emit }) {
    const editedUser = ref({
      id: props.user.id,
      name: props.user.name,
      username: props.user.username,
      email: props.user.email,
      bio: props.user.bio,
      location: props.user.location,
      birthday: props.user.birthday ? new Date(props.user.birthday).toISOString().split('T')[0] : '',
      avatar: props.user.avatar
    })

    const saveUser = () => {
      emit('save', editedUser.value)
    }

    const handleAvatarUpload = (event) => {
      // Handle avatar upload logic here
      console.log('Avatar file:', event.target.files[0])
      // You might want to update the editedUser.avatar here or emit an event to handle it in the parent component
    }

    return {
      editedUser,
      saveUser,
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