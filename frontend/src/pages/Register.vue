<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center"> <!-- Added text-center class here -->
          <img class="mx-auto h-12 w-auto" src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="MarocConnect" />
          <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Create your account</h2>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
          <div class="rounded-md shadow-sm space-y-4">
            <div>
              <label for="name" class="sr-only">Full name</label>
              <input id="name" name="name" type="text" autocomplete="name" required
                     v-model="name"
                     class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                     placeholder="Full name" />
            </div>
            <div>
              <label for="email" class="sr-only">Email address</label>
              <input id="email" name="email" type="email" autocomplete="email" required
                     v-model="email"
                     class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                     placeholder="Email address" />
            </div>
            <div>
              <label for="password" class="sr-only">Password</label>
              <input :type="visible ? 'text' : 'password'" id="password" name="password" autocomplete="new-password" required
                     v-model="password"
                     class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                     placeholder="Password" />
            </div>
          </div>
  
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="terms" name="terms" type="checkbox" required
                     v-model="agreedToTerms"
                     class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
              <label for="terms" class="ml-2 block text-sm text-gray-900">
                I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a>
              </label>
            </div>
          </div>
  
          <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Sign up
            </button>
          </div>
        </form>
  
        <div class="text-center">
          <p class="mt-2 text-sm text-gray-600">
            Already have an account?
            <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
              Sign in now
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
    name: 'Register',
    setup() {
      const name = ref('')
      const email = ref('')
      const password = ref('')
      const agreedToTerms = ref(false)
      const visible = ref(false)
      const router = useRouter()
  
      const handleRegister = () => {
        // This is a mock registration. Replace with actual registration logic when API is ready.
        console.log('Registration attempt', { 
          name: name.value, 
          email: email.value, 
          password: password.value, 
          agreedToTerms: agreedToTerms.value 
        })
        
        // For now, just redirect to home page
        router.push('/home')
      }
  
      return {
        name,
        email,
        password,
        agreedToTerms,
        visible,
        handleRegister
      }
    }
  }
  </script>