<template>
  <div :class="{ 'bg-gray-100 text-gray-900': !isDarkMode, 'bg-gray-900 text-white': isDarkMode }" class="min-h-screen p-4">
    <div class="flex flex-col">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead :class="{ 'bg-gray-50': !isDarkMode, 'bg-gray-700': isDarkMode }">
                <tr>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">User</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Location</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Rep</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Role</th>
                  <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody :class="{ 'bg-white divide-y divide-gray-200': !isDarkMode, 'bg-gray-800 divide-y divide-gray-700': isDarkMode }">
                <tr v-for="user in paginatedUsers" :key="user.id">
                  <td class="px-4 py-2 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8">
                        <img v-if="user.avatar_url" :src="user.avatar_url" alt="" class="h-8 w-8 rounded-full">
                        <div v-else class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-xs text-gray-700">{{ user.initials }}</div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium">{{ user.name }}</div>
                        <div class="text-xs text-gray-500">{{ user.username }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ user.email }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ user.location }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">{{ user.reputation }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      {{ user.roles.map(role => role.name).join(', ') }}
                    </span>
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-xs font-medium">
                    <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 mr-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                      </svg>
                    </button>
                    <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                    <button @click="viewUserDetails(user)" class="text-blue-600 hover:text-blue-900 ml-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
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

    <!-- User Details Modal -->
    <UserDetailsModal v-if="showDetailsModal" :user="selectedUser" @close="closeDetailsModal" />

    <!-- Edit User Modal -->
    <EditUserModal v-if="showEditModal" :user="selectedUser" @close="closeEditModal" @save="saveUser" />
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import UserDetailsModal from '@/components/AdminComponents/UserDetails.vue';
import EditUserModal from '@/components/AdminComponents/EditUserModel.vue';

export default {
  name: 'UsersTable',
  components: {
    UserDetailsModal,
    EditUserModal
  },
  data() {
    return {
      isDarkMode: false,
      currentPage: 1,
      perPage: 10,
      showDetailsModal: false,
      showEditModal: false,
      selectedUser: null,
    };
  },
  computed: {
    ...mapState('admin', ['users']),
    totalPages() {
      return Math.ceil(this.users.length / this.perPage);
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.users.slice(start, end);
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
    this.fetchUsers();
  },
  methods: {
    ...mapActions('admin', ['fetchUsers']),
    editUser(user) {
      console.log('Editing user:', user);
      this.selectedUser = user;
      this.showEditModal = true;
    },
    deleteUser(userId) {
      console.log('Deleting user with ID:', userId);
      // Implement delete user logic
    },
    viewUserDetails(user) {
      console.log('Viewing details for user:', user);
      this.selectedUser = user;
      this.showDetailsModal = true;
    },
    closeDetailsModal() {
      this.showDetailsModal = false;
      this.selectedUser = null;
    },
    closeEditModal() {
      this.showEditModal = false;
      this.selectedUser = null;
    },
    saveUser(updatedUser) {
      console.log('Saving updated user:', updatedUser);
      // Implement save user logic
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
    users: {
      immediate: true,
      handler(newUsers) {
        console.log('Users data:', newUsers);
      }
    }
  }
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>