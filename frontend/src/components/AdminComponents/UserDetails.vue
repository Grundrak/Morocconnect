<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white text-center">User Details</h3>
          <div class="mt-2">
            <img :src="user.avatar_url || defaultAvatar" alt="User Avatar" class="w-24 h-24 rounded-full mx-auto mb-4">
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Name:</span> {{ user.name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Email:</span> {{ user.email }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Username:</span> {{ user.username }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Location:</span> {{ user.location }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Reputation:</span> {{ user.reputation }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-bold">Roles:</span> {{ user.roles.map(role => role.name).join(', ') }}</p>
          </div>
          <div class="flex justify-center mt-4">
            <button @click="$emit('close')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'UserDetailsModal',
    props: {
      user: Object
    },
    setup() {
      const defaultAvatar = 'https://res.cloudinary.com/dgjynovaj/image/upload/v1727130788/defaultAvatar_lszkxq.svg'
  
      return {
        defaultAvatar
      }
    }
  }
  </script>