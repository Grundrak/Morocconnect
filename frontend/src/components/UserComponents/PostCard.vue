<template>
  <div class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-lg shadow mb-6 overflow-hidden w-full">
    <div class="p-4">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
          <div class="w-10 h-10 rounded-full overflow-hidden mr-3 cursor-pointer"
            @click="navigateToProfile(post.user.id)">
            <img v-if="post.user && post.user.avatar" :src="getAvatarUrl(post.user.avatar)" :alt="post.user.name"
              class="w-full h-full object-cover">
            <div v-else class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
              {{ getInitial(post.user ? post.user.name : 'Anonymous') }}
            </div>
          </div>
          <div>
            <p class="font-semibold m-0 mb-1">{{ post.user ? post.user.name : 'Anonymous' }}</p>
            <p v-if="post.user && post.user.username" class="text-sm text-gray-500 m-0 dark:text-white">
              @{{ post.user.username }}</p>
          </div>
        </div>
        <div class="relative">
          <button @click="toggleMoreOptions"
            class="p-1 rounded-full focus:outline-none focus:ring-2 bg-white dark:bg-gray-700">
            <svg-icon name="dotsthreeoutlinevertical" class="h-5 w-5" />
          </button>
          <!-- Dropdown menu -->
          <div v-if="showMoreOptions"
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10">
            <div class="py-1">
              <button v-if="isCurrentUserPost" @click="editPost"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                Edit Post
              </button>
              <button v-if="isCurrentUserPost" @click="deletePost"
                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                Delete Post
              </button>
              <button @click="reportPost"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                Report Post
              </button>
            </div>
          </div>
        </div>
      </div>
      <p v-if="post.content" class="text-gray-700 dark:text-gray-200 mb-4">{{ post.content }}</p>
      <p v-else class="mb-4 text-gray-500 italic">No content</p>
      <img v-if="post.image" :src="getImageUrl(post.image)" :alt="post.user ? post.user.name : 'Post image'"
        class="w-full rounded-lg mb-4">
      <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
        <button @click="toggleLike"
          class="flex items-center hover:text-blue-500 bg-white cursor-pointer dark:bg-gray-700">
          <svg-icon :name="isLiked ? 'thumbsup' : 'thumbsup'" class="w-4 h-4 mr-1"
            :class="{ 'text-blue-500 ': isLiked }" />
          <span class="dark:text-white">{{ postLikes }}</span>
        </button>
        <button @click="toggleComments"
          class="flex items-center hover:text-blue-500 bg-white cursor-pointer  dark:bg-gray-700">
          <svg-icon name="chatdots" class="w-4 h-4 mr-1" />
          <span class="dark:text-white">{{ comments.length }}</span>
        </button>
        <button @click="handleShare"
          class="flex items-center hover:text-blue-500 bg-white cursor-pointer dark:bg-gray-700">
          <svg-icon name="sharefat" class="w-4 h-4 mr-1" />
          <span class="dark:text-white">{{ post.shares_count || 0 }}</span>
        </button>
        <button @click="toggleBookmark" class="ml-auto bg-white hover:text-gray-600 dark:bg-gray-700 cursor-pointer ">
          <svg-icon :name="isBookmarked ? 'bookmarkfill' : 'bookmarksimple'" class="w-4 h-4" />
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


    <!-- Edit Post Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray  flex items-center justify-center px-4 z-50">
      <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md w-[40%] max-w-[400px]">
        <h2 class="text-2xl font-bold text-[#09090b] dark:text-white mb-6">Edit Post</h2>
        <form @submit.prevent="saveEdit" class="w-[90%]">
          <div class="mb-4">
            <div class="relative">
              <SvgIcon name="document-text"
                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
              <input v-model="editedTitle" type="text" placeholder="Title"
                class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
            </div>
          </div>
          <div class="mb-4">
            <div class="relative">
              <SvgIcon name="chat" class="absolute left-3 top-3 text-gray-400 w-5 h-5" />
              <textarea v-model="editedContent" placeholder="Content"
                class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                rows="4"></textarea>
            </div>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 mb-2">Image</label>
            <input type="file" @change="handleEditImageUpload" accept="image/*"
              class="w-full p-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
          </div>
          <div v-if="post.image" class="mb-4">
            <img :src="getImageUrl(post.image)" alt="Current post image" class="w-full h-32 object-cover rounded-lg" />
            <button @click="removeImage" class="mt-2 text-red-500 hover:text-red-700">Remove Image</button>
          </div>
          <div class="flex justify-between">
            <button type="submit"
              class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
              Save
            </button>
            <button @click="cancelEdit"
              class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
              Cancel
            </button>
          </div>
        </form>
        <div v-if="editError" class="mt-2 text-red-600">{{ editError }}</div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
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
    const router = useRouter()
    const newComment = ref('')
    const showComments = ref(false)
    const comments = ref([])
    const commentsLoading = ref(false)
    const showAllComments = ref(false)
    const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'

    const currentUser = computed(() => store.getters['auth/currentUser'])
    const postLikes = computed(() => props.post.likes_count || props.post.likes || 0)
    const isLiked = computed(() => props.post.is_liked || false)
    const isCurrentUserPost = computed(() => currentUser.value?.id === props.post.user?.id)

    const showMoreOptions = ref(false)
    const showEditModal = ref(false)
    const editedTitle = ref('')
    const editedContent = ref('')
    const editedImage = ref(null)
    const editError = ref('')
    const isBookmarked = computed(() => store.getters['posts/isPostBookmarked'](props.post.id))
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

    const toggleMoreOptions = () => {
      showMoreOptions.value = !showMoreOptions.value
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

    const navigateToProfile = (userId) => {
      router.push({ name: 'UserProfil', params: { id: userId } })
    }
    const editPost = () => {
      editedTitle.value = props.post.title
      editedContent.value = props.post.content
      showEditModal.value = true
      showMoreOptions.value = false
    }

    const handleEditImageUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB limit
          editError.value = 'Image size should be less than 2MB'
          event.target.value = '' // Clear the file input
        } else {
          editedImage.value = file
          editError.value = ''
        }
      }
    }

    const removeImage = () => {
      editedImage.value = null
      props.post.image = null
    }

    const cancelEdit = () => {
      showEditModal.value = false
      editedTitle.value = ''
      editedContent.value = ''
      editedImage.value = null
      editError.value = ''
    }

    const saveEdit = async () => {
      try {
        const formData = new FormData()
        formData.append('title', editedTitle.value)
        formData.append('content', editedContent.value)
        if (editedImage.value) {
          formData.append('image', editedImage.value)
        }
        formData.append('_method', 'PUT') // For Laravel to recognize this as a PUT request

        const result = await store.dispatch('posts/updatePost', {
          id: props.post.id,
          formData: formData
        })

        if (result.success) {
          showEditModal.value = false
        } else {
          editError.value = result.error || 'Failed to update post'
        }
      } catch (error) {
        console.error('Error updating post:', error)
        editError.value = error.message || 'An unexpected error occurred'
      }
    }

    const deletePost = async () => {
      if (confirm('Are you sure you want to delete this post?')) {
        try {
          await store.dispatch('posts/deletePost', props.post.id)
          showMoreOptions.value = false
        } catch (error) {
          console.error('Error deleting post:', error)
        }
      }
    }

    const reportPost = () => {
      // Implement report functionality
      console.log('Report post:', props.post.id)
      showMoreOptions.value = false
    }

    const handleShare = () => console.log('Share clicked')


    const toggleBookmark = async () => {
      try {
        if (isBookmarked.value) {
          await store.dispatch('posts/removeBookmark', props.post.id)
        } else {
          await store.dispatch('posts/addBookmark', props.post)
        }
      } catch (error) {
        console.error('Error toggling bookmark:', error)
      }
    }
    // Close dropdown when clicking outside
    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        showMoreOptions.value = false
      }
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
      fetchComments()
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    watch(() => props.post.comments_count, (newCount, oldCount) => {
      if (newCount > oldCount && showComments.value) {
        fetchComments()
      }
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
      displayedComments,
      showAllComments,
      getAvatarUrl,
      getImageUrl,
      formatCommentDate,
      navigateToProfile,
      isCurrentUserPost,
      showMoreOptions,
      showEditModal,
      editedContent,
      toggleMoreOptions,
      editPost,
      cancelEdit,
      saveEdit,
      deletePost,
      reportPost,
      editedTitle,
      editedContent,
      editedImage,
      editError,
      handleEditImageUpload,
      removeImage,
      cancelEdit,
      saveEdit,
      isBookmarked,
      toggleBookmark
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