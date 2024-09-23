<template>
  <div class="min-h-screen flex items-center justify-center bg-[#f5f8ff] px-4">
    <main class="w-full max-w-[400px] p-6 flex flex-col items-center justify-center bg-white rounded-lg shadow-md">
      <div class="mb-6 cursor-pointer" @click="goToLandingPage">
        <SvgIcon name="logo" class="w-16 h-16" />
      </div>
      <h1 class="text-2xl font-bold text-[#09090b] mb-6">Login to your Account</h1>
      <form @submit.prevent="handleLogin" class="w-full">
        <div class="mb-4">
          <div class="relative w-[87%]">
            <SvgIcon name="email" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              v-model="email"
              type="email"
              placeholder="Email"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
              required
            />
          </div>
        </div>
        <div class="mb-4">
          <div class="relative w-[80%]">
            <SvgIcon name="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Password"
              class="w-full pl-10 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
              required
            />
            <button
              type="button"
              @click="togglePasswordVisibility"
              class="absolute left-[110%] top-1/2 transform -translate-y-1/2"
            >
              <SvgIcon :name="showPassword ? 'eye-off' : 'eye'" class="text-gray-400 w-5 h-5" />
            </button>
          </div>
        </div>
        <div class="flex items-center justify-between mb-4">
          <label class="flex items-center">
            <input v-model="rememberMe" type="checkbox" class="mr-2" />
            <span class="text-sm text-gray-600">Remember me</span>
          </label>
          <a @click="forgotPassword" class="text-sm text-blue-500 hover:underline cursor-pointer">Forgot Password?</a>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200"
        >
          LOG IN
        </button>
      </form>
      <p class="mt-4 text-sm text-gray-600">
        Don't have an account?
        <a @click="goToRegister" class="text-blue-500 hover:underline cursor-pointer">Create an account</a>
      </p>
      <p v-if="error" class="mt-4 text-sm text-red-500">{{ error }}</p>
    </main>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import SvgIcon from '@/components/UserComponents/SvgIcon.vue';

export default defineComponent({
  name: "Login",
  components: { SvgIcon },
  setup() {
    const store = useStore();
    const router = useRouter();

    const email = ref('');
    const password = ref('');
    const rememberMe = ref(false);
    const showPassword = ref(false);
    const error = ref('');

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };

    const handleLogin = async () => {
      try {
        const success = await store.dispatch('auth/login', {
          email: email.value,
          password: password.value,
          remember: rememberMe.value
        });
        if (success) {
          router.push({ name: 'Home' });
        } else {
          error.value = 'Invalid credentials. Please try again.';
        }
      } catch (err) {
        console.error('Login error:', err);
        error.value = err.response?.data?.message || 'An error occurred during login';
      }
    };

    const forgotPassword = () => {
      // Implement forgot password functionality
      console.log('Forgot password clicked');
    };

    const goToRegister = () => {
      router.push({ name: 'Register' });
    };

    const goToLandingPage = () => {
      router.push({ name: 'Landing' }); // Ensure you have a route named 'Landing' for your landing page
    };

    return {
      email,
      password,
      rememberMe,
      showPassword,
      error,
      togglePasswordVisibility,
      handleLogin,
      forgotPassword,
      goToRegister,
      goToLandingPage
    };
  }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
  font-family: 'Inter', sans-serif;
}
</style>