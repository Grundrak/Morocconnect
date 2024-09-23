
<template>
  <img :src="iconSrc" :alt="name" :class="[classes, 'bg-transparent']" />
</template>

<script>
export default {
  name: 'SvgIcon',
  props: {
    name: String,
    size: {
      type: String,
      default: '24'
    }
  },
  computed: {
    iconSrc() {
      return `/icons/${this.name}.svg`
    },
    classes() {
      return `icon icon-${this.name} w-${this.size} h-${this.size}`
    }
  }
}
</script>