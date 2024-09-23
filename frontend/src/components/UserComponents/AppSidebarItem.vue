<template>
  <div
    class="flex items-center p-3 rounded-full hover:bg-gray-10 cursor-pointer mb-2"
  >
    <img v-if="isIconLeft" :src="chatTeardropDots" class="w-6 h-6 mr-3" :alt="mainText" />
    <span v-if="isDot" class="w-2 h-2 bg-success-50 rounded-full mr-3"></span>
    <span class="flex-grow font-bold">{{ mainText }}</span>
    <BadgeText v-if="isBadge" :text="text" />
    <img v-if="isIconRight" src="/caretdown.svg" class="w-6 h-6 ml-3" alt="Expand" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import BadgeText from './BadgeText.vue';

export default defineComponent({
  name: 'AppSidebarItem',
  components: { BadgeText },
  props: {
    mainText: { type: String, required: true },
    isIconLeft: { type: Boolean, default: false },
    isDot: { type: Boolean, default: false },
    chatTeardropDots: { type: String, default: '' },
    isBadge: { type: Boolean, default: false },
    text: { type: String, default: '' },
    isIconRight: { type: Boolean, default: false },
  },
});
</script>