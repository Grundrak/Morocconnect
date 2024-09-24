<template>
  <nav class="bg-white border-b dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-gray-200 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between h-16">
        <div class="flex-1 flex justify-center">
          <div class="relative w-full max-w-xl">
            <input v-model="searchQuery" @input="debouncedSearch" @focus="showDropdown = true"
              @blur="hideDropdownDelayed" type="text" placeholder="Search..."
              class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:text-white">
            <button @click="performSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 dark:bg-gray-700">
              <svg-icon name="search" class="w-5 h-5 text-gray-500 dark:bg-gray-700" />
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
        <!-- Search Results Dropdown -->
        <div
          v-if="showDropdown && (isSearching || searchResults.users.length > 0 || searchResults.posts.length > 0 || searchResults.comments.length > 0)"
          class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 max-h-96 overflow-y-auto">
          <div v-if="isSearching" class="px-4 py-2 text-gray-500 dark:text-gray-400">Searching...</div>
          <div v-else-if="error" class="px-4 py-2 text-red-500">{{ error }}</div>
          <template v-else>
            <div v-if="searchResults.users.length > 0">
              <h3 class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Users</h3>
              <a v-for="user in searchResults.users" :key="user.id" @mousedown="goToUserProfile(user.id)"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                {{ user.name }} (@{{ user.username }})
              </a>
            </div>
            <div v-if="searchResults.posts.length > 0">
              <h3 class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Posts</h3>
              <a v-for="post in searchResults.posts" :key="post.id" @mousedown="goToPost(post.id)"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                {{ post.title }}
              </a>
            </div>
            <div v-if="searchResults.comments.length > 0">
              <h3 class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Comments</h3>
              <a v-for="comment in searchResults.comments" :key="comment.id" @mousedown="goToComment(comment.id)"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                {{ comment.content.substring(0, 50) }}...
              </a>
            </div>
          </template>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import SvgIcon from './SvgIcon.vue'
import AddPostForm from './AddPostForm.vue'
import debounce from 'lodash/debounce'

export default {
  name: 'Navbar',
  components: {
    SvgIcon,
    AddPostForm
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    const showAddPostForm = ref(false)
    const searchQuery = ref('')
    const showDropdown = ref(false)

    const searchResults = computed(() => store.getters['search/searchResults'])
    const isSearching = computed(() => store.getters['search/isSearching'])
    const error = computed(() => store.getters['search/error'])

    const performSearch = async () => {
      if (searchQuery.value.trim()) {
        await store.dispatch('search/performSearch', searchQuery.value)
      }
    }

    const debouncedSearch = debounce(performSearch, 300)

    const hideDropdownDelayed = () => {
      setTimeout(() => {
        showDropdown.value = false
      }, 200)
    }

    const goToUserProfil = (userId) => {
      router.push({ name: 'UserProfil', params: { id: userId } })
      showDropdown.value = false
    }

    const goToPost = (postId) => {
      router.push({ name: 'PostDetail', params: { id: postId } })
      showDropdown.value = false
    }

    const goToComment = (commentId) => {
      // Assuming you have a route to show a specific comment, otherwise redirect to the post
      router.push({ name: 'CommentDetail', params: { id: commentId } })
      showDropdown.value = false
    }

    // Close dropdown when clicking outside
    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        showDropdown.value = false
      }
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    return {
      showAddPostForm,
      searchQuery,
      performSearch,
      debouncedSearch,
      showDropdown,
      hideDropdownDelayed,
      searchResults,
      isSearching,
      error,
      goToUserProfil,
      goToPost,
      goToComment
    }
  }
}
</script>