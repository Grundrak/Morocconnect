<!-- src/components/RightSidebar.vue -->
<template>
  <div class="bg-white h-full w-64 p-3 flex flex-col">
    <div class="flex justify-between items-center mb-4">
      <img :src="avatarSrc" alt="User Avatar" class="w-8 h-8 rounded-full cursor-pointer hover:opacity-80">
      <div class="flex space-x-1">
        <button v-for="icon in icons" :key="icon" 
                @click="handleIconClick(icon)"
                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition duration-150">
          <svg-icon :name="icon" class="w-4 h-4 text-gray-600" />
        </button>
      </div>
    </div>

    <div class="mb-4">
      <h2 class="text-lg font-semibold mb-2">Notifications</h2>
      <div v-for="notification in notifications" :key="notification.id" class="mb-2 bg-gray-50 rounded p-2 text-sm">
        <div class="flex items-center mb-1">
          <img :src="notification.avatar" :alt="notification.name" class="w-6 h-6 rounded-full mr-2">
          <div>
            <p class="font-medium">{{ notification.name }}</p>
            <p class="text-xs text-gray-500">{{ notification.action }}</p>
          </div>
        </div>
        <div class="flex space-x-1 mt-1">
          <button @click="handleRemove(notification.id)" class="flex-1 bg-gray-200 text-gray-800 py-1 px-2 rounded text-xs font-medium hover:bg-gray-300">Remove</button>
          <button @click="handleFollow(notification.id)" class="flex-1 bg-blue-500 text-white py-1 px-2 rounded text-xs font-medium hover:bg-blue-600">Follow</button>
        </div>
      </div>
    </div>

    <div>
      <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-semibold">Recent activities</h2>
        <a href="#" class="text-xs text-blue-500 font-medium hover:underline">See All</a>
      </div>
      <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center justify-between mb-2 py-1 hover:bg-gray-50 rounded">
        <div class="flex items-center">
          <img :src="activity.avatar" :alt="activity.name" class="w-6 h-6 rounded-full mr-2">
          <p class="text-sm">{{ activity.name }}</p>
        </div>
        <button @click="handleAddActivity(activity.id)" class="text-gray-400 hover:text-blue-500 transition duration-150">
          <svg-icon name="plus" class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import SvgIcon from './SvgIcon.vue'

export default {
  name: 'RightSidebar',
  components: { SvgIcon },
  setup() {
    const avatarSrc = 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg'
    const icons = ['chatteardropdots', 'notification', 'gearsix']
    
    const notifications = ref([
      { id: 1, name: 'George Jose', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg', action: 'Followed you · 1min ago' },
      { id: 2, name: 'George Jose', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg', action: 'Followed you · 2min ago' },
      { id: 3, name: 'George Jose', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg', action: 'Followed you · 3min ago' },
    ])

    const recentActivities = ref([
      { id: 1, name: 'Julia Smith', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg' },
      { id: 2, name: 'Vermillion D. Gray', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg' },
      { id: 3, name: 'Mai Senpai', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg' },
      { id: 4, name: 'Azunyan U. Wu', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg' },
      { id: 5, name: 'Barack Babama', avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg' },
    ])

    const handleIconClick = (icon) => {
      console.log(`Clicked on ${icon} icon`)
      // Add your logic here
    }

    const handleRemove = (id) => {
      console.log(`Remove notification ${id}`)
      // Add your logic here
    }

    const handleFollow = (id) => {
      console.log(`Follow user from notification ${id}`)
      // Add your logic here
    }

    const handleAddActivity = (id) => {
      console.log(`Add activity ${id}`)
      // Add your logic here
    }

    return {
      avatarSrc,
      icons,
      notifications,
      recentActivities,
      handleIconClick,
      handleRemove,
      handleFollow,
      handleAddActivity,
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.bg-white {
  font-family: 'Inter', sans-serif;
}
@media (max-width: 1024px) {
  .flex-1.flex > .flex-shrink-0 {
    display: none;
  }
}
</style>