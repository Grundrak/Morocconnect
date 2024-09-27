<template>
  <div
    class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 h-full flex flex-col transition-all duration-300 shadow-lg"
    :class="{ 'w-64 md:w-72 lg:w-80': !isMinimized, 'w-14': isMinimized }">
    <div class="p-4 flex items-center justify-between">
      <div class="flex items-center">
        <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg"
          alt="MarocConnect" class="w-8 h-8 mr-2">
          <h1 v-if="!isMinimized"
          class="text-base font-medium font-['Plus_Jakarta_Sans'] text-black tracking-[-0.01em] leading-[22px] dark:text-white">
          MarocConnect
        </h1>
      </div>
    </div>
    <div v-if="!isMinimized" class="px-8 mb-4">
      <input type="text" placeholder="Search..."
        class="w-full p-2 rounded-full bg-gray-100 dark:bg-gray-700 dark:focus:ring-blue-400 dark:text-white">
    </div>
    <nav class="flex-1 overflow-y-auto">
      <SidebarItem v-for="item in menuItems" :key="item.text" :icon="item.icon" :text="item.text" :badge="item.badge"
        :to="item.route" :isMinimized="isMinimized" />
    </nav>
    <div class="p-4 border-t" v-if="!isMinimized">
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useWindowSize } from '@vueuse/core'
import SidebarItem from './SidebarItem.vue'
import SvgIcon from './SvgIcon.vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'Sidebar',
  components: { SidebarItem, SvgIcon },
  setup() {
    const user = ref(null)
    const { width } = useWindowSize()
    const isMinimized = ref(false)
    const store = useStore()
    const router = useRouter()

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
      try {
        if (!store.getters['auth/isAuthenticated']) {
          console.error('No token found, redirecting to login');
          router.push('/login');
          return;
        }
        const success = await store.dispatch('auth/fetchUser');
        if (success) {
          user.value = store.state.auth.user;
        } else {
          console.error('Failed to fetch user data');
          router.push('/login');
        }
      } catch (error) {
        console.error('Error fetching user data:', error);
        router.push('/login');
      }
    };

    const logout = async () => {
      try {
        console.log('Attempting logout...');
        await store.dispatch('auth/logout');
        console.log('Logout successful, redirecting...');
        router.push('/login');
      } catch (error) {
        console.error('Error during logout:', error);
        // Even if there's an error, we should clear the local storage and redirect
        localStorage.removeItem('auth_token');
        router.push('/login');
      }
    };

    onMounted(() => {
      fetchUserData()
    })

    watch(width, (newWidth) => {
      isMinimized.value = newWidth < 768
    })
    return {
      user,
      logout,
      menuItems,
      isMinimized,
      $winWidth: width,
    }
  }
}
</script>