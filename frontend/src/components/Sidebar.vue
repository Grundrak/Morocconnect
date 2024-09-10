<!-- components/Sidebar.vue -->
<template>
  <div class="bg-white h-full flex flex-col transition-all duration-300 shadow-lg"
  :class="{ 'w-64 md:w-72 lg:w-80': !isMinimized, 'w-14': isMinimized }">
    <div class="p-4 flex items-center justify-between">
      <div class="flex items-center">
        <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg"
             alt="MarocConnect" class="w-8 h-8 mr-2">
        <h1 v-if="!isMinimized" class="text-base font-medium font-['Plus_Jakarta_Sans'] text-black tracking-[-0.01em] leading-[22px]">
          MarocConnect
        </h1>
      </div>
      <!-- <button @click="toggleSidebar" class="p-1 rounded-full hover:bg-gray-200 lg:hidden">
        <svg v-if="isMinimized" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button> -->
    </div>
    <div v-if="!isMinimized" class="px-8 mb-4">
      <input type="text" placeholder="Search..." class="w-full p-2 rounded-full bg-gray-100">
    </div>
    <nav class="flex-1 overflow-y-auto">
      <SidebarItem 
        v-for="item in menuItems" 
        :key="item.text"
        :icon="item.icon" 
        :text="item.text" 
        :badge="item.badge"
        :to="item.route"
        :isMinimized="isMinimized || $winWidth < 768"
      />
    </nav>
    <div class="p-4 border-t" v-if="!isMinimized">
      <div v-if="user" class="flex items-center justify-between">
        <div class="flex items-center">
          <img :src="user.avatar" :alt="user.name" class="w-10 h-10 rounded-full mr-3">
          <div>
            <p class="font-semibold">{{ user.name }}</p>
            <p class="text-sm text-gray-500">{{ user.membershipType }}</p>
          </div>
        </div>
        <button @click="logout" class="text-red-500 hover:text-red-700">
          Logout
        </button>
      </div>
      <div v-else class="text-center text-gray-500">
        Loading user data...
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted} from 'vue'
import { useWindowSize } from '@vueuse/core'
import SidebarItem from './SidebarItem.vue'

export default {
  name: 'Sidebar',
  components: { SidebarItem },
  setup() {
    const user = ref(null)
    const { width } = useWindowSize()
    const isMinimized = computed(() => width.value < 768)

    const menuItems = [
      { icon: "housesimple", text: "Home", route: "/" },
      { icon: "users", text: "Groups", badge: "12", route: "/groups" },
      { icon: "calendar", text: "Events", badge: "12", route: "/events" },
      { icon: "user", text: "Friends", badge: "2", route: "/friends" },
      { icon: "bookmarksimple", text: "Saved", route: "/saved" },
      { icon: "flag", text: "Pages", route: "/pages" },
      { icon: "gearsix", text: "Settings", route: "/settings" },
      { icon: "chatteardropdots", text: "Help & Support", route: "/support" }
    ]

    const fetchUserData = async () => {
      // Implement user data fetching
    }

    const logout = () => {
      // Implement logout functionality
    }

    const toggleSidebar = () => {
      // This function is no longer needed for automatic resizing,
      // but we'll keep it in case you want manual control in the future
    }

    onMounted(() => {
      fetchUserData()
    })

    return {
      user,
      logout,
      menuItems,
      isMinimized,
      $winWidth: width,
      toggleSidebar
    }
  }
}
</script>