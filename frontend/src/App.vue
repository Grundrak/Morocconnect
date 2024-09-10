<!-- App.vue -->
<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Full layout for authenticated routes -->
    <div v-if="isAuthenticatedRoute" class="grid grid-cols-[auto,1fr,auto] h-screen">
      <Sidebar class="w-64" />
      <div class="flex flex-col overflow-hidden">
        <Navbar />
        <main class="flex-1 overflow-y-auto bg-gray-100 px-4 py-6">
          <div class="max-w-2xl mx-auto">
            <router-view></router-view>
          </div>
        </main>
      </div>
      <RightSidebar class="w-64 border-l border-gray-200" />
    </div>

    <!-- Simple layout for unauthenticated routes -->
    <div v-else class="flex flex-col min-h-screen">
      <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <img class="h-8 w-auto" src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="MarocConnect" />
              </div>
              <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <router-link to="/" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                  Home
                </router-link>
                <router-link to="/login" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                  Login
                </router-link>
                <router-link to="/register" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                  Register
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </nav>
      <main class="flex-grow">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Sidebar from './components/Sidebar.vue'
import Navbar from './components/Navbar.vue'
import RightSidebar from './components/RightSidebar.vue'

export default {
  name: 'App',
  components: {
    Sidebar,
    Navbar,
    RightSidebar
  },
  setup() {
    const route = useRoute()

    const isAuthenticatedRoute = computed(() => {
      // Add all routes that require authentication here
      const authRoutes = ['/home']
      return authRoutes.includes(route.path)
    })

    return {
      isAuthenticatedRoute
    }
  }
}
</script>