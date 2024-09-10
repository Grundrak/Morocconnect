<!-- views/HomePage.vue -->
<template>
  <div class="max-w-2xl mx-auto w-full">
    <PostCard v-for="post in posts" :key="post.id" :post="post" />
  </div>
</template>

<script>
import PostCard from '@/components/PostCard.vue'

export default {
  name: 'HomePage',
  components: { PostCard },
  data() {
    return {
      posts: [
        {
          id: 1,
          author: 'Morocconect',
          role: 'Test item',
          avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg',
          content: 'Offer tips for building self-confidence, such as celebrating small victories, practicing self-compassion, and surrounding yourself with positive influences. Explain how self-confidence is essential for achieving goals and overcoming challenges.',
          image: 'https://res.cloudinary.com/dgjynovaj/image/upload/v1724932909/cld-sample-2.jpg',
          likes: 100,
          comments: 200,
          shares: 187
        },
        {
          id: 2,
          author: 'Morocconect',
          role: 'Test item',
          avatar: 'https://res.cloudinary.com/dgjynovaj/image/upload/w_1000,c_fill,ar_1:1,g_auto,r_max,bo_5px_solid_red,b_rgb:262c35/v1724932906/samples/smile.jpg',
          content: 'Offer tips for building self-confidence, such as celebrating small victories, practicing self-compassion, and surrounding yourself with positive influences. Explain how self-confidence is essential for achieving goals and overcoming challenges.',
          image: 'https://res.cloudinary.com/dgjynovaj/image/upload/v1724932909/cld-sample-2.jpg',
          likes: 100,
          comments: 200,
          shares: 187
        },      ]
    }
  }
}
</script>