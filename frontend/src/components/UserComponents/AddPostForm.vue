<template>
  <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4">Add New Post</h2>
    <form @submit.prevent="submitPost">
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" v-model="title" 
               class="mt-1 h-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
      </div>
      <div class="mb-4">
        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
        <textarea id="content" v-model="content" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
      </div>
      <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" id="image" @change="handleImageUpload"
               class="mt-1 block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-700
                      hover:file:bg-blue-100">
      </div>
      <div class="flex justify-end">
        <button type="button" @click="$emit('close')" 
                class="mr-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Cancel
        </button>
        <button type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Post
        </button>
      </div>
    </form>
    <div v-if="error" class="mt-2 text-red-600">{{ error }}</div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'

export default {
  name: 'AddPostForm',
  emits: ['close', 'post-added'],
  setup(props, { emit }) {
    const store = useStore()
    const title = ref('')
    const content = ref('')
    const image = ref(null)
    const error = ref('')

    const handleImageUpload = (event) => {
      image.value = event.target.files[0]
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