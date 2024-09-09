<!-- components/Avatar.vue -->
<template>
  <div class="flex items-center justify-center">
    <div class="relative">
      <img :src="avatarSrc" :alt="userName" class="w-12 h-12 rounded-full">
      <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Avatar',
  props: {
    avatarSrc: {
      type: String,
      required: true
    },
    userName: {
      type: String,
      required: true
    }
  }
}
</script>