<!-- components/Navbar.vue -->
<template>
  <nav class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between h-16">
        <div class="flex-1 flex justify-center">
          <div class="relative w-full max-w-xl">
            <input type="text" placeholder="Search..."
                   class="w-full p-2 pr-10 rounded-full bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
            <button @click="performSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2">
              <svg-icon name="search" class="w-5 h-5 text-gray-500" />
            </button>
          </div>
        </div>
        <div class="flex items-center ml-4">
          <button @click="showAddPostForm = true" 
                  class="bg-blue-500 text-white px-2 sm:px-4 py-2 rounded-full flex items-center hover:bg-blue-600 transition duration-150 ease-in-out">
            <span class="hidden sm:inline mr-2">Add New Post</span>
            <svg-icon name="plus" class="w-5 h-5" />
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Add Post Form Modal -->
  <teleport to="body">
    <div v-if="showAddPostForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <add-post-form @close="showAddPostForm = false" />
    </div>
  </teleport>
</template>

<script>
import { ref } from 'vue'
import SvgIcon from './SvgIcon.vue'
import AddPostForm from './AddPostForm.vue'

export default {
  name: 'Navbar',
  components: {
    SvgIcon,
    AddPostForm
  },
  setup() {
    const showAddPostForm = ref(false)

    const performSearch = () => {
      // Implement search functionality
      console.log('Performing search...')
    }

    return {
      showAddPostForm,
      performSearch
    }
  }
}
</script>