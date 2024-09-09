<!-- views/HomePage.vue -->
<template>
  <div class="max-w-3xl mx-auto py-6">
    <PostCard v-for="post in posts" :key="post.id" :post="post" />
  </div>
</template>

<script>
import PostCard from '@/components/PostCard.vue'

export default {
  name: 'HomePage',
  components: {
    PostCard
  },
  data() {
    return {
      posts: [
        {
          id: 1,
          author: 'Morocconect',
          role: 'Test item',
          avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg',
          content: 'Offer tips for building self-confidence, such as celebrating small victories, practicing self-compassion, and surrounding yourself with positive influences. Explain how self-confidence is essential for achieving goals and overcoming challenges.',
          image: 'https://res.cloudinary.com/dgjynovaj/image/upload/v1724932909/cld-sample-2.jpg',
          likes: 100,
          comments: 200,
          shares: 187
        },
        // Add more post objects here
      ]
    }
  }
}
</script>