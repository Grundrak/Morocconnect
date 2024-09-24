<template>
  <div>
    <slot v-if="!error"></slot>
    <div v-else class="text-red-500 p-4">
      <h2 class="text-xl font-bold">An error occurred</h2>
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script>
import { ref, onErrorCaptured } from 'vue'

export default {
  setup() {
    const error = ref(null)

    onErrorCaptured(err => {
      error.value = err
      return true
    })

    return { error }
  }
}
</script>