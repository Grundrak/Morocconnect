
<template>
    <nav class="bg-white border-b dark:bg-gray-800 text-gray-900 dark:text-gray-200 border-gray-200 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between h-16">
          <!-- Project Icon and Name -->
          <div class="flex items-center">
            <img class="h-8 w-auto mr-2" src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="MarocConnect" />
            <span class="font-semibold text-lg">MarocConnect</span>
          </div>
  
          <!-- Search Bar -->
          <div class="flex-1 max-w-xl mx-4">
            <div class="relative">
              <input type="text" placeholder="Search..."
                class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:text-white">
              <button @click="performSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                <svg-icon name="search" class="w-5 h-5 text-gray-500 dark:text-gray-400" />
              </button>
            </div>
          </div>
  
          <!-- User Avatar and Dropdown -->
          <div class="flex items-center">
            <div class="relative bg-white" @click="toggleDropdown" @keydown.escape="isDropdownOpen = false">
              <button class="flex items-center focus:outline-none">
                <img class="h-8 w-8 rounded-full object-cover" :src="userAvatar" alt="User avatar" />
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1">
                <router-link to="/" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Home</router-link>
                <a @click="logout" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import SvgIcon from './SvgIcon.vue'
  
  export default {
    name: 'AdminNavbar',
    components: {
      SvgIcon
    },
    setup() {
      const router = useRouter()
      const isDropdownOpen = ref(false)
      const userAvatar = ref('https://via.placeholder.com/150') // Replace with actual user avatar
  
      const performSearch = () => {
        // Implement search functionality
        console.log('Performing search...')
      }
  
      const toggleDropdown = () => {
        isDropdownOpen.value = !isDropdownOpen.value
      }
  
      const logout = () => {
        // Implement logout functionality
        console.log('Logging out...')
        // After logout, redirect to login page
        router.push('/login')
      }
  
      return {
        isDropdownOpen,
        userAvatar,
        performSearch,
        toggleDropdown,
        logout
      }
    }
  }
  </script>