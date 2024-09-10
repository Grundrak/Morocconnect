<!-- components/PostCard.vue -->
<template>
  <div class="bg-white rounded-lg shadow mb-6 overflow-hidden w-full">
    <div class="p-4">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
          <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
            <img :src="post.avatar" :alt="post.author" class="w-full h-full object-cover">
          </div>
          <div>
            <p class="font-semibold m-0 mb-1">{{ post.author }}</p>
            <p class="text-sm text-gray-500 m-0">{{ post.role }}</p>
          </div>
        </div>
        <button @click="handleMoreOptions" class="p-1 rounded-full focus:outline-none focus:ring-2 bg-white">
          <svg-icon name="dotsthreeoutlinevertical" class="h-5 w-5" />
        </button>
      </div>
      <p class="mb-4 text-gray-800">{{ post.content }}</p>
      <img v-if="post.image" :src="post.image" :alt="post.author" class="w-full rounded-lg mb-4">
      <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
        <button @click="handleLike" class="flex items-center hover:text-blue-500 bg-white">
          <svg-icon name="thumbsup" class="w-4 h-4 mr-1" :class="{ 'text-blue-500': post.isLiked }" />
          <span>{{ post.likes }}</span>
        </button>
        <button @click="handleComment" class="flex items-center hover:text-blue-500 bg-white">
          <svg-icon name="chatdots" class="w-4 h-4 mr-1" />
          <span>{{ post.comments }}</span>
        </button>
        <button @click="handleShare" class="flex items-center hover:text-blue-500 bg-white">
          <svg-icon name="sharefat" class="w-4 h-4 mr-1" />
          <span>{{ post.shares }}</span>
        </button>
        <button @click="handleBookmark" class="ml-auto text-gray-400 hover:text-gray-600 bg-white">
          <svg-icon name="bookmarksimple" class="w-4 h-4" />
        </button>
      </div>
    </div>
    <div class="border-t p-4">
      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
          <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="User"
            class="w-full h-full object-cover">
        </div>
        <input type="text" placeholder="Write your comment..." class="flex-1 p-2 rounded-full bg-gray-100 text-sm">
        <button @click="handleAttachment"
          class="ml-2 p-2 rounded-full bg-white text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
          <svg-icon name="paper-clip" class="w-5 h-5" />
        </button>
        <button @click="handleEmoji"
          class="ml-2 p-2 rounded-full bg-white text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
          <svg-icon name="emoji" class="w-5 h-5" />
        </button>
        <button @click="handleSend"
          class="ml-2 p-2 rounded-full bg-white text-blue-500 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
          <svg-icon name="share" class="w-5 h-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import SvgIcon from './SvgIcon.vue'

export default {
  name: 'PostCard',
  components: {
    SvgIcon
  },
  props: {
    post: Object
  },
  methods: {
    handleMoreOptions() {
      // Handle more options click
      console.log('More options clicked')
    },
    handleLike() {
      // Handle like click
      console.log('Like clicked')
    },
    handleComment() {
      // Handle comment click
      console.log('Comment clicked')
    },
    handleShare() {
      // Handle share click
      console.log('Share clicked')
    },
    handleBookmark() {
      // Handle bookmark click
      console.log('Bookmark clicked')
    },
    handleAttachment() {
      // Handle attachment click
      console.log('Attachment clicked')
    },
    handleEmoji() {
      // Handle emoji click
      console.log('Emoji clicked')
    },
    handleSend() {
      // Handle send click
      console.log('Send clicked')
    }
  }
}
</script>