<template>
    <nav class="bg-white shadow-md dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <SvgIcon name="logo" class="w-8 h-8" />
                        <span class="ml-2 text-xl font-bold text-gray-800 dark:text-white">MarocConnect</span>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="flex-1 max-w-xl mx-4 p-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..."
                            class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-full px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:text-white">
                        <button @click="performSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                            <svg-icon name="search" class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                        </button>
                    </div>
                </div>
                <div class="flex items-center">
                    <div v-if="currentUser" class="ml-4 relative">
                        <img :src="getAvatarUrl(currentUser.avatar)" alt="User avatar"
                            class="w-10 h-10 rounded-full cursor-pointer" @click="toggleDropdown" />
                        <div v-if="showDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-10">
                            <a @click="goToHome"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                Home
                            </a>
                            <a @click="goToProfile"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                View Profile
                            </a>
                            <a @click="goToEditProfile"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                Edit Profile
                            </a>
                            <a v-if="isAdmin" @click="goToAdminDashboard"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                Admin Dashboard
                            </a>
                            <a @click="logout"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                Log Out
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <div class="px-4 py-2 flex items-center justify-between">
                                <span class="text-sm text-gray-700 dark:text-gray-200">Dark Mode</span>
                                <button @click="toggleDarkMode"
                                    class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-200 ease-in-out"
                                    :class="{ 'bg-blue-600': isDarkMode, 'bg-gray-200': !isDarkMode }">
                                    <span
                                        class="inline-block w-4 h-4 transform transition-transform duration-200 ease-in-out bg-white rounded-full"
                                        :class="{ 'translate-x-6': isDarkMode, 'translate-x-1': !isDarkMode }"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import SvgIcon from '@/components/UserComponents/SvgIcon.vue';

export default {
    name: 'Navbar',
    components: { SvgIcon },
    setup() {
        const store = useStore();
        const router = useRouter();
        const showDropdown = ref(false);
        const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg';
        const isDarkMode = ref(localStorage.getItem('darkMode') === 'true');

        const currentUser = computed(() => store.getters['auth/currentUser']);
        const isAdmin = computed(() => currentUser.value?.roles.some(role => role.name === 'Admin'));

        const toggleDropdown = () => {
            showDropdown.value = !showDropdown.value;
        };

        const goToHome = () => {
            router.push({ name: 'Home' });
            showDropdown.value = false;
        };

        const goToProfile = () => {
            if (currentUser.value && currentUser.value.id) {
                router.push({ name: 'UserProfile', params: { id: currentUser.value.id } });
                showDropdown.value = false;
            }
        };

        const goToEditProfile = () => {
            router.push({ name: 'EditProfile' });
            showDropdown.value = false;
        };

        const goToAdminDashboard = () => {
            router.push({ name: 'AdminDashboard' });
            showDropdown.value = false;
        };

        const logout = async () => {
            await store.dispatch('auth/logout');
            showDropdown.value = false;
            router.push({ name: 'Login' });
        };

        const toggleDarkMode = () => {
            isDarkMode.value = !isDarkMode.value;
            localStorage.setItem('darkMode', isDarkMode.value);
            document.documentElement.classList.toggle('dark');
        };
        const getAvatarUrl = (avatar) => {
            if (!avatar) return defaultAvatar;
            if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
                return avatar;
            }
            return `${import.meta.env.VITE_API_URL}/storage/${avatar}`;
        };
        return {
            currentUser,
            showDropdown,
            defaultAvatar,
            isDarkMode,
            isAdmin,
            toggleDropdown,
            goToHome,
            goToProfile,
            goToEditProfile,
            goToAdminDashboard,
            logout,
            toggleDarkMode,
            getAvatarUrl
        };
    },
};
</script>