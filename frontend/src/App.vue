<template>
  <div :class="{ 'dark': isDarkMode }"
    class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <ErrorBoundary>
      <!-- Full layout for authenticated routes -->
      <div v-if="isAuthenticatedRoute" class="flex h-screen overflow-hidden">
        <!-- Left Sidebar -->
        <Sidebar :class="{ 'w-16 md:w-64': !isMobileScreen, 'w-16': isMobileScreen }" />

        <!-- Main Content Area -->
        <div class="flex-grow flex flex-col overflow-hidden">
          <Navbar @toggle-right-sidebar="toggleRightSidebar" :isMobileScreen="isMobileScreen" />
          <main class="flex-1 overflow-y-auto bg-gray-100 dark:bg-gray-800">
            <div class="max-w-3xl mx-auto px-4 py-6">
              <router-view></router-view>
            </div>
          </main>
        </div>

        <!-- Right Sidebar -->
        <div :class="{
          'hidden lg:block': !showRightSidebar,
          'fixed inset-y-0 right-0 z-50 w-64 md:w-80': showRightSidebar && isMobileScreen,
          'w- md:w-80': !isMobileScreen || (isMobileScreen && showRightSidebar)
        }" class="bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 overflow-y-auto">
          <RightSidebar />
        </div>
      </div>

      <!-- Simple layout for unauthenticated routes -->
      <div v-else class="min-h-screen">
        <router-view></router-view>
      </div>
    </ErrorBoundary>

    <!-- Overlay for right sidebar on small screens -->
    <div v-if="showRightSidebar && isMobileScreen" class="fixed inset-0 bg-black bg-opacity-50 z-40"
      @click="toggleRightSidebar"></div>
  </div>
</template>


<script>
import { computed, ref, watch, onMounted, onUnmounted } from 'vue'
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
    ErrorBoundary,
  },
  setup() {
    const route = useRoute()
    const isDarkMode = ref(false)
    const showRightSidebar = ref(false)
    const isMobileScreen = ref(false)

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

    const toggleRightSidebar = () => {
      showRightSidebar.value = !showRightSidebar.value
    }

    const checkScreenSize = () => {
      isMobileScreen.value = window.innerWidth < 1024
      if (!isMobileScreen.value) {
        showRightSidebar.value = true
      }
    }

    onMounted(() => {
      updateDarkMode()
      checkScreenSize()
      window.addEventListener('resize', checkScreenSize)
    })

    onUnmounted(() => {
      window.removeEventListener('resize', checkScreenSize)
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
      toggleDarkMode,
      showRightSidebar,
      toggleRightSidebar,
      isMobileScreen
    }
  }
}
</script>