<template>
  <router-link 
    :to="to" 
    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 no-underline dark:text-white dark:hover:bg-gray-600"
    :class="{ 'bg-gray-100 dark:bg-gray-600': isActive, 'justify-center': isMinimized }"
  >
    <svg-icon :name="icon" class="w-5 h-5" :class="{ 'mr-3': !isMinimized }" />
    <span v-if="!isMinimized" class="transition-opacity duration-200">{{ text }}</span>
    <span v-if="badge && !isMinimized" class="ml-auto bg-blue-500 text-white rounded-full px-2 py-1 text-xs">
      {{ badge }}
    </span>
  </router-link>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import SvgIcon from './SvgIcon.vue'

export default {
  name: 'SidebarItem',
  components: { SvgIcon },
  props: {
    icon: String,
    text: String,
    badge: String,
    to: String,
    isMinimized: Boolean
  },
  setup(props) {
    const route = useRoute()
    const isActive = computed(() => route.path === props.to)
    return { isActive }
  }
}
</script>