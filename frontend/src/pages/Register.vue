<template>
  <div class="min-h-screen flex items-center justify-center bg-[#f5f8ff] px-4">
    <main class="w-full max-w-[400px] p-6 flex flex-col items-center justify-center bg-white rounded-lg shadow-md">
      <div class="mb-6 cursor-pointer" @click="goToLandingPage">
        <SvgIcon name="logo" class="w-16 h-16" />
      </div>
      <h1 class="text-2xl font-bold text-[#09090b] mb-6">Create your account</h1>
      <form @submit.prevent="handleRegister" class="w-full">
        <div class="mb-4">
          <div class="relative w-[87%]">
            <SvgIcon name="user" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              v-model="username"
              type="text"
              placeholder="Username"
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
              required
            />
          </div>
        </div>
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
        <div class="mb-4">
          <div class="relative w-[80%]">
            <SvgIcon name="lock" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              v-model="confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="Confirm Password"
              class="w-full pl-10 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
              required
            />
            <button
              type="button"
              @click="toggleConfirmPasswordVisibility"
              class="absolute left-[110%] top-1/2 transform -translate-y-1/2"
            >
              <SvgIcon :name="showConfirmPassword ? 'eye-off' : 'eye'" class="text-gray-400 w-5 h-5" />
            </button>
          </div>
        </div>
        <div class="flex items-center mb-4">
          <input v-model="acceptTerms" type="checkbox" id="terms" class="mr-2" required />
          <label for="terms" class="text-sm text-gray-600">
            Accept <span class="text-blue-500 cursor-pointer" @click="showTerms">terms and conditions</span>
          </label>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200"
        >
          Register
        </button>
      </form>
      <p class="mt-4 text-sm text-gray-600">
        You have an account?
        <a @click="goToLogin" class="text-blue-500 hover:underline cursor-pointer">Login now</a>
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
  name: "Register",
  components: { SvgIcon },
  setup() {
    const store = useStore();
    const router = useRouter();

    const username = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const acceptTerms = ref(false);
    const showPassword = ref(false);
    const showConfirmPassword = ref(false);
    const error = ref('');

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };

    const toggleConfirmPasswordVisibility = () => {
      showConfirmPassword.value = !showConfirmPassword.value;
    };

    const handleRegister = async () => {
  if (password.value !== confirmPassword.value) {
    error.value = 'Passwords do not match';
    return;
  }

  try {
    const success = await store.dispatch('auth/register', {
      username: username.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirmPassword.value, // Make sure to include this
    });
    if (success) {
      router.push({ name: 'Home' });
    } else {
      error.value = 'Registration failed. Please try again.';
    }
  } catch (err) {
    console.error('Registration error:', err.response?.data);
    error.value = err.response?.data?.message || 'An error occurred during registration';
  }
};

    const showTerms = () => {
      // Implement show terms functionality
      console.log('Show terms and conditions');
    };

    const goToLogin = () => {
      router.push({ name: 'Login' });
    };

    const goToLandingPage = () => {
      router.push({ name: 'Landing' });
    };

    return {
      username,
      email,
      password,
      confirmPassword,
      acceptTerms,
      showPassword,
      showConfirmPassword,
      error,
      togglePasswordVisibility,
      toggleConfirmPasswordVisibility,
      handleRegister,
      showTerms,
      goToLogin,
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