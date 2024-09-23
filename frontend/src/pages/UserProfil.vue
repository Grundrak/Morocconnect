<template>
  <div class="bg-gray-100 min-h-screen">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
      <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex justify-center items-center h-screen">
      <div class="text-red-500 text-xl">{{ error }}</div>
    </div>

    <!-- User Profile -->
    <div v-else-if="user" class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Cover Photo -->
        <div class="h-60 w-full bg-cover bg-center" :style="coverPhotoStyle"></div>
        
        <div class="px-4 py-5 sm:px-6 relative">
          <!-- Profile Photo -->
          <img 
            :src="user.avatar || defaultAvatar" 
            :alt="user.name"
            class="w-32 h-32 rounded-full border-4 border-white absolute -mt-20 shadow-lg"
          >
          
          <div class="ml-40">
            <h3 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              {{ user.name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">@{{ user.username }}</p>
          </div>
          
          <!-- Follow Button (if not current user) -->
          <button 
            v-if="!isCurrentUser"
            @click="toggleFollow" 
            class="absolute top-5 right-5 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full"
          >
            {{ isFollowing ? 'Unfollow' : 'Follow' }}
          </button>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
          <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Bio</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.bio || 'No bio provided' }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Location</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.location || 'Not specified' }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Website</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                <a v-if="user.website" :href="user.website" target="_blank" class="text-blue-500 hover:underline">{{ user.website }}</a>
                <span v-else>Not provided</span>
              </dd>
            </div>
          </dl>
        </div>
      </div>
      
      <!-- Stats -->
      <div class="mt-6 grid grid-cols-3 gap-6 text-center">
        <div class="bg-white p-4 rounded-lg shadow">
          <p class="text-2xl font-bold text-gray-900">{{ user.posts_count || 0 }}</p>
          <p class="text-base font-medium text-gray-500">Posts</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <p class="text-2xl font-bold text-gray-900">{{ user.followers_count || 0 }}</p>
          <p class="text-base font-medium text-gray-500">Followers</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <p class="text-2xl font-bold text-gray-900">{{ user.following_count || 0 }}</p>
          <p class="text-base font-medium text-gray-500">Following</p>
        </div>
      </div>
      
      <!-- User's Posts -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Recent Posts</h2>
        <!-- Add your PostCard component or list of posts here -->
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import { onUnmounted } from 'vue';

export default {
  name: 'UserProfile',
  setup() {
    const store = useStore();
    const route = useRoute();

    const user = computed(() => store.getters['user/profileUser']);
    const currentUser = computed(() => store.getters['auth/currentUser']);
    const isLoading = computed(() => store.getters['user/isLoading']);
    const error = ref(null);

    const isCurrentUser = computed(() => user.value?.id === currentUser.value?.id);
    const isFollowing = ref(false);

    const defaultAvatar = '/path/to/default/avatar.png';

    const coverPhotoStyle = computed(() => ({
      backgroundImage: user.value?.cover_photo 
        ? `url(${user.value.cover_photo})` 
        : 'url(/path/to/default/cover.jpg)'
    }));

    const fetchUserProfile = async (userId) => {
      if (userId) {
        try {
          await store.dispatch('user/fetchUserProfile', userId);
          isFollowing.value = user.value?.is_followed_by_current_user || false;
        } catch (err) {
          console.error('Error fetching user profile:', err);
          error.value = 'Failed to load user profile';
        }
      } else {
        error.value = 'User ID is missing';
      }
    };

    const toggleFollow = async () => {
      try {
        const action = isFollowing.value ? 'user/unfollowUser' : 'user/followUser';
        await store.dispatch(action, user.value.id);
        isFollowing.value = !isFollowing.value;
        if (user.value) {
          user.value.followers_count += isFollowing.value ? 1 : -1;
        }
      } catch (err) {
        console.error('Error toggling follow:', err);
        // You might want to show an error message to the user here
      }
    };

    onMounted(() => {
      fetchUserProfile(route.params.id);
    });

    watch(() => route.params.id, (newId) => {
      fetchUserProfile(newId);
    });

    return {
      user,
      isLoading,
      error,
      isCurrentUser,
      isFollowing,
      defaultAvatar,
      coverPhotoStyle,
      toggleFollow
    };
  }
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>