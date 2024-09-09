<!-- src/components/RightSidebar.vue -->
<template>
  <div class="bg-white h-full p-4 transition-all duration-300"
       :class="{ 'w-64 md:w-72 lg:w-80': !isMinimized, 'w-20': isMinimized }">
    <div class="flex items-center justify-center">
      <img :src="avatarSrc" alt="User Avatar" class="w-12 h-12 rounded-full">
    </div>
    <div class="flex justify-center mt-4 space-x-2" :class="{ 'flex-col space-x-0 space-y-2': isMinimized }">
      <button v-for="icon in icons" :key="icon" 
              class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100">
        <img :src="getIconSrc(icon)" :alt="icon" class="w-6 h-6">
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'

export default {
  name: 'RightSidebar',
  setup() {
    const avatarSrc = '/path-to-your-avatar-image.jpg'
    const icons = ['message', 'notification', 'settings']
    const isMinimized = ref(window.innerWidth < 1024)

    const getIconSrc = (icon) => {
      return `/path-to-your-icons/${icon}.svg`
    }

    const handleResize = () => {
      isMinimized.value = window.innerWidth < 1024
    }

    onMounted(() => {
      window.addEventListener('resize', handleResize)
    })

    onUnmounted(() => {
      window.removeEventListener('resize', handleResize)
    })

    return {
      avatarSrc,
      icons,
      isMinimized,
      getIconSrc
    }
  }
}
</script>