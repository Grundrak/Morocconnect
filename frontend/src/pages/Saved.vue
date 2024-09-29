<!-- src/views/Saved.vue -->
<template>
    <div class="p-4 cursor-pointer">
      <div v-if="bookmarkedPosts.length === 0" class="text-center text-gray-500">
        No saved posts yet.
      </div>
      <div v-else class="space-y-4">
        <div v-for="post in bookmarkedPosts" :key="post.id" class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                <img v-if="post.user && post.user.avatar" :src="getAvatarUrl(post.user.avatar)" :alt="post.user.name"
                  class="w-full h-full object-cover">
                <div v-else class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                  {{ getInitial(post.user ? post.user.name : 'Anonymous') }}
                </div>
              </div>
              <div>
                <p class="font-semibold">{{ post.user ? post.user.name : 'Anonymous' }}</p>
                <p v-if="post.user && post.user.username" class="text-sm text-gray-500 dark:text-gray-400">
                  @{{ post.user.username }}
                </p>
              </div>
            </div>
            <button @click="removeBookmark(post.id)" class="text-red-500 hover:text-red-700 bg-white cursor-pointer">
              <svg-icon name="trash" class="w-5 h-5" />
            </button>
          </div>
          <!-- <router-link :to="{ name: 'PostDetails', params: { id: post.id } }" class="block">
            <h2 class="text-lg font-semibold mb-2">{{ post.title }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ truncateContent(post.content) }}</p>
          </router-link> -->
          <div class="flex justify-between items-center mt-4">
            <span class="text-sm text-gray-500">{{ formatDate(post.created_at) }}</span>
            <!-- <router-link :to="{ name: 'PostDetails', params: { id: post.id } }" 
              class="text-blue-500 hover:text-blue-700">
              Read more
            </router-link> -->
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { computed } from 'vue'
  import { useStore } from 'vuex'
  import { formatDistanceToNow } from 'date-fns'
  import SvgIcon from '@/components/UserComponents/SvgIcon.vue'
  
  export default {
    name: 'Saved',
    components: { SvgIcon },
    setup() {
      const store = useStore()
  
      const bookmarkedPosts = computed(() => store.getters['posts/bookmarkedPosts'])
  
      const removeBookmark = (postId) => {
        store.dispatch('posts/removeBookmark', postId)
      }
  
      const truncateContent = (content, maxLength = 150) => {
        if (content.length <= maxLength) return content
        return content.slice(0, maxLength) + '...'
      }
  
      const formatDate = (date) => {
        return formatDistanceToNow(new Date(date), { addSuffix: true })
      }
  
      const getAvatarUrl = (avatar) => {
        if (!avatar) return null;
        if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
          return avatar;
        }
        return `${import.meta.env.VITE_API_URL}/storage/${avatar}`;
      }
  
      const getInitial = (name) => {
        return name && typeof name === 'string' ? name.charAt(0).toUpperCase() : '?'
      }
  
      return {
        bookmarkedPosts,
        removeBookmark,
        truncateContent,
        formatDate,
        getAvatarUrl,
        getInitial,
      }
    }
  }
  </script>