<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center">
          <img class="mx-auto h-12 w-auto" src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="MarocConnect" />
          <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
          <div class="rounded-md shadow-sm space-y-4"> <!-- Changed to space-y-4 for spacing -->
            <div>
              <label for="email" class="">Email address</label>
              <input id="email" name="email" type="email" autocomplete="email" required
                     v-model="email"
                     class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                     placeholder="Email address" />
            </div>
            <div>
              <label for="password" class="">Password</label>
              <input :type="visible ? 'text' : 'password'" id="password" name="password" autocomplete="current-password" required
                     v-model="password"
                     class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                     placeholder="Password" />
            </div>
          </div>
  
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" name="remember-me" type="checkbox"
                     v-model="rememberMe"
                     class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
              <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>
  
            <div class="text-sm">
              <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
            </div>
          </div>
  
          <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Sign in
            </button>
          </div>
        </form>
  
        <div class="text-center">
          <p class="mt-2 text-sm text-gray-600">
            Don't have an account?
            <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
              Sign up now
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  
  export default {
    name: 'Login',
    setup() {
      const email = ref('')
      const password = ref('')
      const rememberMe = ref(false)
      const visible = ref(false)
      const router = useRouter()
  
      const handleLogin = () => {
        // This is a mock login. Replace with actual authentication logic when API is ready.
        console.log('Login attempt', { email: email.value, password: password.value, rememberMe: rememberMe.value })
        
        // For now, just redirect to home page
        router.push('/home')
      }
  
      return {
        email,
        password,
        rememberMe,
        visible,
        handleLogin
      }
    }
  }
  </script>