<template>
  <div class="max-w-2xl mx-auto w-full px-4 sm:px-6 lg:px-8">
    <div v-if="isLoading" class="text-center py-4">Loading...</div>
    <div v-else-if="error" class="text-center py-4 text-red-500">{{ error }}</div>
    <div v-else-if="!posts || posts.length === 0" class="text-center py-4">No posts available.</div>
    <template v-else>
      <PostCard v-for="post in posts" :key="post.id" :post="post" class="mb-6" />
    </template>
  </div>
</template>

<script>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import PostCard from '@/components/UserComponents/PostCard.vue'

export default {
  name: 'HomePage',
  components: { PostCard },
  setup() {
    const store = useStore()

    const posts = computed(() => store.getters['posts/allPosts'])
    const isLoading = computed(() => store.getters['posts/isLoading'])
    const error = computed(() => store.getters['posts/error'])
    const pagination = computed(() => store.getters['posts/pagination'])
    
    const debugPosts = computed(() => JSON.stringify(posts.value, null, 2))

    onMounted(async () => {
      try {
        await store.dispatch('posts/fetchPosts')
        console.log('Fetched posts:', posts.value)
      } catch (err) {
        console.error('Error fetching posts:', err)
      }
    })

    return {
      posts,
      isLoading,
      error,
      pagination,
      debugPosts
    }
  }
}
</script>