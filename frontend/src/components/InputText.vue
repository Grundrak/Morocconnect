<!-- InputText.vue -->
<template>
  <div class="input-text-wrapper">
    <label v-if="label" :for="id" class="block mb-1 font-bold text-sm text-gray-80">{{ label }}</label>
    <div class="relative">
      <input
        :id="id"
        :type="type"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="w-full px-3 py-2 border border-gray-30 rounded-full text-sm text-gray-80 focus:outline-none focus:border-brand-60"
        :class="{ 'pl-10': leftIcon, 'pr-10': rightIcon }"
      >
      <img v-if="leftIcon" :src="leftIcon" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
      <img v-if="rightIcon" :src="rightIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
    </div>
    <p v-if="helperText" class="mt-1 text-sm text-gray-60">{{ helperText }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'InputText',
  props: {
    id: { type: String, required: true },
    label: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    modelValue: { type: String, required: true },
    helperText: { type: String, default: '' },
    leftIcon: { type: String, default: '' },
    rightIcon: { type: String, default: '' },
  },
  emits: ['update:modelValue'],
});
</script>