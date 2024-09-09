<!-- components/PostCard.vue -->
<template>
  <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
    <div class="p-4">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
          <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
            <img :src="post.avatar" :alt="post.author" class="w-full h-full object-cover">
          </div>
          <div>
            <p class="font-semibold">{{ post.author }}</p>
            <p class="text-sm text-gray-500">{{ post.role }}</p>
          </div>
        </div>
        <button class="text-gray-400 hover:text-gray-600">
          <svg-icon name="dotsthreeoutlinevertical"  />
        </button>
      </div>
      <p class="mb-4 text-gray-800">{{ post.content }}</p>
      <img v-if="post.image" :src="post.image" :alt="post.author" class="w-full rounded-lg mb-4">
      <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
        <span class="flex items-center">
          <svg-icon name="thumbsup" class="w-5 h-5 mr-1 text-blue-500" />
          {{ post.likes }} Likes
        </span>
        <span class="flex items-center">
          <svg-icon name="chatdots" class="w-5 h-5 mr-1 text-gray-500" />
          {{ post.comments }} Comments
        </span>
        <span class="flex items-center">
          <svg-icon name="sharefat" class="w-5 h-5 mr-1 text-gray-500" />
          {{ post.shares }} Share
        </span>
        <button class="text-gray-400 hover:text-gray-600">
          <svg-icon name="bookmarksimple" class="w-5 h-5" />
        </button>
      </div>
    </div>
    <div class="border-t p-4">
      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
          <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="User" class="w-full h-full object-cover">
        </div>
        <input type="text" placeholder="Write your comment..." class="flex-1 p-2 rounded-full bg-gray-100 text-sm">
        <button class="ml-2 text-gray-400 hover:text-gray-600">
          <svg-icon name="paper-clip" class="w-5 h-5" />
        </button>
        <button class="ml-2 text-gray-400 hover:text-gray-600">
          <svg-icon name="emoji" class="w-5 h-5" />
        </button>
        <button class="ml-2 text-blue-500 hover:text-blue-600">
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
  }
}
</script>