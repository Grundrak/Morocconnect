<!-- Button.vue -->
<template>
  <button
    class="flex items-center justify-center px-5 py-3 bg-brand-60 text-gray-0-white rounded-full font-bold"
  >
    <img v-if="isIconLeft" :src="leftIcon" class="w-5 h-5 mr-2.5" alt="" />
    <span>{{ text }}</span>
    <img v-if="isIconRight" :src="rightIcon" class="w-5 h-5 ml-2.5" alt="" />
  </button>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Button',
  props: {
    text: { type: String, required: true },
    isIconLeft: { type: Boolean, default: false },
    isIconRight: { type: Boolean, default: false },
    leftIcon: { type: String, default: '' },
    rightIcon: { type: String, default: '' },
  },
});
</script>