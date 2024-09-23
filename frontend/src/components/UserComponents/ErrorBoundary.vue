<template>
    <div>
      <slot v-if="!error"></slot>
      <div v-else>
        <h1>Something went wrong</h1>
        <p>{{ error }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onErrorCaptured } from 'vue'
  
  export default {
    setup() {
      const error = ref(null)
  
      onErrorCaptured(e => {
        error.value = e
        return true
      })
  
      return { error }
    }
  }
  </script>