<!-- BadgeText.vue -->
<template>
  <div class="inline-flex items-center px-2 py-1 rounded-full bg-brand-5 border border-brand-30 text-brand-60 text-sm font-semibold">
    <img v-if="isIconLeft" :src="leftIcon" class="w-4 h-4 mr-1" alt="" />
    <span v-if="isDot" class="w-2 h-2 bg-brand-60 rounded-full mr-1"></span>
    <span>{{ text }}</span>
    <img v-if="isIconRight" :src="rightIcon" class="w-4 h-4 ml-1" alt="" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'BadgeText',
  props: {
    text: { type: String, required: true },
    isIconLeft: { type: Boolean, default: false },
    isIconRight: { type: Boolean, default: false },
    isDot: { type: Boolean, default: false },
    leftIcon: { type: String, default: '' },
    rightIcon: { type: String, default: '' },
  },
});
</script>