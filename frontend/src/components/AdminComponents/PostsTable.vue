
<template>
  <div :class="{ 'bg-gray-100 text-gray-900': !isDarkMode, 'bg-gray-900 text-white': isDarkMode }" class="min-h-screen p-4">
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead :class="{ 'bg-gray-50': !isDarkMode, 'bg-gray-700': isDarkMode }">
                <tr>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Author</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Created At</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody :class="{ 'bg-white divide-y divide-gray-200': !isDarkMode, 'bg-gray-800 divide-y divide-gray-700': isDarkMode }">
                <tr v-for="post in paginatedPosts" :key="post.id">
                  <td class="px-4 py-2 whitespace-nowrap text-sm">{{ post.title }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm">{{ post.user.name }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm">{{ new Date(post.created_at).toLocaleString() }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm">
                    <span :class="{'bg-green-100 text-green-800': post.is_verified, 'bg-yellow-100 text-yellow-800': !post.is_verified}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ post.is_verified ? 'Verified' : 'Pending' }}
                    </span>
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                    <button @click="editPost(post)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </button>
                    <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-900">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Pagination -->
      <div class="mt-4 flex justify-between items-center">
        <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm disabled:opacity-50">
          Previous
        </button>
        <div class="flex space-x-2">
          <button v-for="page in displayedPages" :key="page" @click="goToPage(page)" 
                  :class="['px-3 py-1 rounded-md text-sm', currentPage === page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700']">
            {{ page }}
          </button>
        </div>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm disabled:opacity-50">
          Next
        </button>
      </div>
    </div>

    <!-- Edit Post Modal -->
    <EditPostModal v-if="showEditModal" :post="selectedPost" @close="closeEditModal" @save="savePost" />
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import EditPost from '@/components/AdminComponents/EditPost.vue';

export default {
  name: 'PostsTable',
  components: {
    EditPost
  },
  data() {
    return {
      isDarkMode: false,
      currentPage: 1,
      perPage: 10,
      showEditModal: false,
      selectedPost: null,
    };
  },
  computed: {
    ...mapState('admin', ['posts']),
    totalPages() {
      return Math.ceil(this.posts.length / this.perPage);
    },
    paginatedPosts() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.posts.slice(start, end);
    },
    displayedPages() {
      const range = 2;
      const pages = [];
      for (let i = Math.max(1, this.currentPage - range); i <= Math.min(this.totalPages, this.currentPage + range); i++) {
        pages.push(i);
      }
      return pages;
    },
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    ...mapActions('admin', ['fetchPosts']),
    editPost(post) {
      console.log('Editing post:', post);
      this.selectedPost = post;
      this.showEditModal = true;
    },
    deletePost(postId) {
      console.log('Deleting post with ID:', postId);
      // Implement delete post logic
    },
    closeEditModal() {
      this.showEditModal = false;
      this.selectedPost = null;
    },
    savePost(updatedPost) {
      console.log('Saving updated post:', updatedPost);
      // Implement save post logic
      this.closeEditModal();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    goToPage(page) {
      this.currentPage = page;
    },
  },
  watch: {
    posts: {
      immediate: true,
      handler(newPosts) {
        console.log('Posts data:', newPosts);
      }
    }
  }
};
</script>