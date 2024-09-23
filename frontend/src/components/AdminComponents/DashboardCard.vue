<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
      <div class="flex items-center">
        <div :class="`bg-${color}-100 dark:bg-${color}-900 rounded-full p-3`">
          <svg-icon :name="icon" class="w-6 h-6" :class="`text-${color}-500`" />
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-semibold">{{ title }}</h3>
          <p class="text-2xl font-bold">{{ value }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import SvgIcon from '@/components/UserComponents/SvgIcon.vue'
  
  export default {
    name: 'DashboardCard',
    components: { SvgIcon },
    props: {
      title: String,
      value: Number,
      icon: String,
      color: String
    }
  }
  </script>