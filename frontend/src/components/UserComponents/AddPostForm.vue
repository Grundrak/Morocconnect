<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md w-full max-w-[400px]">
      <h2 class="text-2xl font-bold text-[#09090b] dark:text-white mb-6">Add New Post</h2>
      <form @submit.prevent="submitPost" class="w-[90%]">
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="document-text" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input v-model="title" type="text" placeholder="Title"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative">
            <SvgIcon name="chat" class="absolute left-3 top-3 text-gray-400 w-5 h-5" />
            <textarea v-model="content" placeholder="Content"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white"
              rows="4"></textarea>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 dark:text-gray-300 mb-2">Image</label>
          <input type="file" @change="handleImageUpload" accept="image/*"
            class="w-full p-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
        </div>
        <div class="flex justify-between">
          <button type="submit"
            class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
            Post
          </button>
          <button @click="$emit('close')"
            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
            Cancel
          </button>
        </div>
      </form>
      <div v-if="error" class="mt-2 text-red-600">{{ error }}</div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'
import SvgIcon from '@/components/UserComponents/SvgIcon.vue'

export default {
  name: 'AddPostForm',
  components: { SvgIcon },
  emits: ['close', 'post-added'],
  setup(props, { emit }) {
    const store = useStore()
    const title = ref('')
    const content = ref('')
    const image = ref(null)
    const error = ref('')

    const handleImageUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB limit
          error.value = 'Image size should be less than 2MB'
          event.target.value = '' // Clear the file input
        } else {
          image.value = file
          error.value = ''
        }
      }
    }

    const submitPost = async () => {
      try {
        const formData = new FormData()
        formData.append('title', title.value)
        formData.append('content', content.value)
        if (image.value) {
          formData.append('image', image.value)
        }

        console.log('Submitting post:', Object.fromEntries(formData))

        const result = await store.dispatch('posts/createPost', formData)
        
        if (result.success) {
          console.log('Post created successfully:', result.post)
          emit('post-added', result.post)
          emit('close')
        } else {
          error.value = result.error || 'Failed to create post'
        }
      } catch (err) {
        console.error('Error creating post:', err)
        error.value = err.message || 'An unexpected error occurred'
      }
    }

    return {
      title,
      content,
      handleImageUpload,
      submitPost,
      error
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