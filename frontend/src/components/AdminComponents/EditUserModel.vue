<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Edit User</h3>
          <form class="mt-2 text-left" @submit.prevent="saveUser">
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                Name
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600" id="name" type="text" v-model="editedUser.name">
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                Email
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700 dark:border-gray-600" id="email" type="email" v-model="editedUser.email">
            </div>
            <!-- Add more fields as needed -->
            <div class="flex items-center justify-between mt-8">
              <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Save
              </button>
              <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" @click="$emit('close')">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  
  export default {
    name: 'EditUserModal',
    props: {
      user: Object
    },
    setup(props, { emit }) {
      const editedUser = ref({ ...props.user })
  
      const saveUser = () => {
        emit('save', editedUser.value)
      }
  
      return {
        editedUser,
        saveUser
      }
    }
  }
  </script>