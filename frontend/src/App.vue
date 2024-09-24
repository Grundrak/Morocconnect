<template>
  <div :class="{ 'dark': isDarkMode }" class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <ErrorBoundary>
      <!-- Full layout for authenticated routes -->
      <div v-if="isAuthenticatedRoute" class="grid grid-cols-[auto,1fr,auto] h-screen">
        <Sidebar class="w-64" />
        <div class="flex flex-col overflow-hidden">
          <Navbar />
          <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-800 px-4 py-6">
            <div class="max-w-2xl mx-auto">
              <router-view></router-view>
            </div>
          </main>
        </div>
        <RightSidebar class="w-64 border-l border-gray-200 dark:border-gray-700" />
      </div>

      <!-- Simple layout for unauthenticated routes -->
      <div v-else class="min-h-screen">
        <router-view></router-view>
      </div>
    </ErrorBoundary>
  </div>
</template>

<script>
import { computed, ref, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Sidebar from './components/UserComponents/Sidebar.vue'
import Navbar from './components/UserComponents/Navbar.vue'
import RightSidebar from './components/UserComponents/RightSidebar.vue'
import ErrorBoundary from './components/UserComponents/ErrorBoundary.vue'

export default {
  name: 'App',
  components: {
    Sidebar,
    Navbar,
    RightSidebar,
    ErrorBoundary
  },
  setup() {
    const route = useRoute()
    const isDarkMode = ref(false)

    const isAuthenticatedRoute = computed(() => {
      const authRoutes = ['/home', '/profile']
      return authRoutes.some(path => route.path.startsWith(path))
    })

    const toggleDarkMode = () => {  
      isDarkMode.value = !isDarkMode.value
      localStorage.setItem('darkMode', isDarkMode.value)
    }

    const updateDarkMode = () => {
      isDarkMode.value = localStorage.getItem('darkMode') === 'true'
    }

    onMounted(() => {
      updateDarkMode()
    })

    watch(isDarkMode, () => {
      if (isDarkMode.value) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    })

    return {
      isAuthenticatedRoute,
      isDarkMode,
      toggleDarkMode
    }
  }
}
</script>

