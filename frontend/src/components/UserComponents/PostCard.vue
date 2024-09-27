<template>
  <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded-lg shadow mb-6 overflow-hidden w-full">
    <div class="p-4">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
          <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
            <img v-if="post.user && post.user.avatar" :src="getAvatarUrl(post.user.avatar)" :alt="post.user.name"
              class="w-full h-full object-cover">
            <div v-else class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
              {{ getInitial(post.user ? post.user.name : 'Anonymous') }}
            </div>
          </div>
          <div>
            <p class="font-semibold m-0 mb-1">{{ post.user ? post.user.name : 'Anonymous' }}</p>
            <p v-if="post.user && post.user.username" class="text-sm text-gray-500 m-0">{{ post.user.username }}</p>
          </div>
        </div>
        <button @click="handleMoreOptions"
          class="p-1 rounded-full focus:outline-none focus:ring-2 bg-white dark:bg-gray-800">
          <svg-icon name="dotsthreeoutlinevertical" class="h-5 w-5" />
        </button>
      </div>
      <p v-if="post.content" class="text-gray-800 dark:text-gray-200 mb-4">{{ post.content }}</p>
      <p v-else class="mb-4 text-gray-500 italic">No content</p>
      <img v-if="post.image" :src="getImageUrl(post.image)" :alt="post.user ? post.user.name : 'Post image'"
        class="w-full rounded-lg mb-4">
      <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
        <button @click="toggleLike" class="flex items-center hover:text-blue-500 bg-white dark:bg-gray-800">
          <svg-icon :name="isLiked ? 'thumbsup' : 'thumbsup'" class="w-4 h-4 mr-1"
            :class="{ 'text-blue-500 ': isLiked }" />
          <span>{{ postLikes }}</span>
        </button>
        <button @click="toggleComments" class="flex items-center hover:text-blue-500 bg-white  dark:bg-gray-800">
          <svg-icon name="chatdots" class="w-4 h-4 mr-1" />
          <span>{{ comments.length }}</span>
        </button>
        <button @click="handleShare" class="flex items-center hover:text-blue-500 bg-white dark:bg-gray-800">
          <svg-icon name="sharefat" class="w-4 h-4 mr-1" />
          <span>{{ post.shares_count || 0 }}</span>
        </button>
        <button @click="handleBookmark" class="ml-auto text-gray-400 hover:text-gray-600 dark:bg-gray-800 ">
          <svg-icon name="bookmarksimple" class="w-4 h-4" />
        </button>
      </div>
    </div>
    <div v-if="showComments" class="border-t p-4 dark:border-gray-700">
      <div v-if="commentsLoading" class="text-center py-4 ">
        Loading comments...
      </div>
      <div v-else-if="comments.length === 0" class="text-center py-4 dark:text-white">
        No comments yet.
      </div>
      <div v-else class="space-y-4">
        <div v-for="comment in displayedComments" :key="comment.id" class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <img v-if="comment.user && comment.user.avatar" :src="getAvatarUrl(comment.user.avatar)"
              :alt="comment.user.name" class="w-8 h-8 rounded-full">
            <div v-else
              class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">
              {{ getInitial(comment.user ? comment.user.name : 'Anonymous') }}
            </div>
          </div>
          <div class="flex-grow bg-gray-100 rounded-lg p-3">
            <p class="font-semibold text-sm">{{ comment.user ? comment.user.name : 'Anonymous' }}</p>
            <p class="text-sm text-gray-700">{{ comment.content }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ formatCommentDate(comment.created_at) }}</p>
          </div>
        </div>
      </div>
      <div v-if="comments.length > 3 && !showAllComments" class="mt-4 text-center">
        <button @click="showAllComments = true" class="text-blue-500 hover:underline">
          See more comments
        </button>
      </div>
      <div class="flex items-center mt-4">
        <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
          <img v-if="currentUser && currentUser.avatar" :src="getAvatarUrl(currentUser.avatar)" :alt="currentUser.name"
            class="w-full h-full object-cover">
          <div v-else class="w-full h-full bg-purple-500 flex items-center justify-center text-white text-sm font-bold">
            {{ getInitial(currentUser ? currentUser.name : 'Anonymous') }}
          </div>
        </div>
        <input v-model="newComment" type="text" placeholder="Write your comment..."
          class="flex-1 p-2 rounded-full bg-gray-100 text-sm">
        <button @click="submitComment"
          class="ml-2 p-2 rounded-full text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
          <svg-icon name="share" class="w-5 h-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted } from 'vue'
import { useStore } from 'vuex'
import SvgIcon from './SvgIcon.vue'
import { formatDistanceToNow } from 'date-fns'

export default {
  name: 'PostCard',
  components: { SvgIcon },
  props: {
    post: { type: Object, required: true }
  },
  setup(props) {
    const store = useStore()
    const newComment = ref('')
    const showComments = ref(false)
    const comments = ref([])
    const commentsLoading = ref(false)
    const showAllComments = ref(false)
    const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'

    const currentUser = computed(() => store.getters['auth/currentUser'])
    const postLikes = computed(() => props.post.likes_count || props.post.likes || 0)
    const isLiked = computed(() => props.post.is_liked || false)

    const displayedComments = computed(() => {
      return showAllComments.value ? comments.value : comments.value.slice(0, 3)
    })

    const getInitial = (name) => {
      return name && typeof name === 'string' ? name.charAt(0).toUpperCase() : '?'
    }

    const getAvatarUrl = (avatar) => {
      if (!avatar) return defaultAvatar;
      if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
        return avatar;
      }
      return `${import.meta.env.VITE_API_URL}/storage/${avatar}`;
    }

    const getImageUrl = (imagePath) => {
      if (!imagePath) return '';
      if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
      }
      return `${import.meta.env.VITE_API_URL}/storage/${imagePath}`;
    }

    const formatCommentDate = (date) => {
      return formatDistanceToNow(new Date(date), { addSuffix: true })
    }

    const toggleLike = async () => {
      try {
        const action = isLiked.value ? 'posts/unlikePost' : 'posts/likePost'
        const response = await store.dispatch(action, props.post.id)
        props.post.likes_count = response.likes_count || response.likes;
        props.post.is_liked = response.is_liked;
      } catch (error) {
        if (error.response && error.response.status === 400) {
          const data = error.response.data;
          props.post.likes_count = data.likes;
          props.post.is_liked = data.is_liked;
        } else {
          console.error('Error toggling like:', error)
        }
      }
    }

    const toggleComments = async () => {
      showComments.value = !showComments.value
      if (showComments.value && comments.value.length === 0) {
        await fetchComments()
      }
    }

    const fetchComments = async () => {
      commentsLoading.value = true
      try {
        const fetchedComments = await store.dispatch('posts/fetchComments', props.post.id)
        comments.value = fetchedComments.map(comment => ({
          ...comment,
          user: comment.user || { name: 'Anonymous' }
        }))
      } catch (error) {
        console.error('Error fetching comments:', error)
        comments.value = []
      } finally {
        commentsLoading.value = false
      }
    }

    const submitComment = async () => {
      if (newComment.value.trim()) {
        try {
          const comment = await store.dispatch('posts/addComment', {
            postId: props.post.id,
            content: newComment.value
          })
          if (comment) {
            comments.value = [
              {
                ...comment,
                user: currentUser.value,
                created_at: new Date().toISOString()
              },
              ...comments.value
            ]
            newComment.value = ''
          }
        } catch (error) {
          console.error('Error submitting comment:', error)
        }
      }
    }

    const handleShare = () => console.log('Share clicked')
    const handleBookmark = () => console.log('Bookmark clicked')
    const handleMoreOptions = () => console.log('More options clicked')

    watch(() => props.post.comments_count, (newCount, oldCount) => {
      if (newCount > oldCount && showComments.value) {
        fetchComments()
      }
    })

    onMounted(() => {
      fetchComments()
    })

    return {
      newComment,
      postLikes,
      isLiked,
      currentUser,
      showComments,
      comments,
      commentsLoading,
      getInitial,
      toggleLike,
      toggleComments,
      submitComment,
      handleShare,
      handleBookmark,
      handleMoreOptions,
      displayedComments,
      showAllComments,
      getAvatarUrl,
      getImageUrl,
      formatCommentDate
    }
  }
}
</script>


<style scoped>
@media (min-width: 640px) {
  .w-full {
    max-width: 100%;
  }
}

@media (max-width: 639px) {
  .max-w-2xl {
    max-width: 100%;
  }
}
</style>