<!-- ContentFrame.vue -->
<template>
  <div class="content-frame bg-gray-0-white border border-gray-20 rounded-3xl shadow-md overflow-hidden">
    <div class="frame-header border-b border-gray-20 p-4 flex items-center justify-between">
      <AvatarLabel
        :mainText="headerText"
        :supportingText="supportingText"
        :isSupportingText="showSupportingText"
      />
      <slot name="header-action"></slot>
    </div>
    <div class="frame-content p-5">
      <slot></slot>
    </div>
    <div v-if="showFooter" class="frame-footer border-t border-gray-20 p-4">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import AvatarLabel from './AvatarLabel.vue';

export default defineComponent({
  name: 'ContentFrame',
  components: { AvatarLabel },
  props: {
    headerText: { type: String, required: true },
    supportingText: { type: String, default: '' },
    showSupportingText: { type: Boolean, default: true },
    showFooter: { type: Boolean, default: false },
  },
});
</script>