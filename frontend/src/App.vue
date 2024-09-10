<!-- App.vue -->
<template>
    <nav>
    <router-link to="/">Home</router-link> |
    <router-link to="/login">Login</router-link> |
    <router-link to="/register">Register</router-link>
  </nav>
  <div class="grid grid-cols-[auto,1fr,auto] h-screen bg-gray-100">
    <Sidebar class="w-64" />
    <div class="flex flex-col overflow-hidden">
      <Navbar />
      <main class="flex-1 overflow-y-auto bg-gray-100 px-4 py-6">
        <div class="max-w-2xl mx-auto">
          <router-view></router-view>
        </div>
      </main>
    </div>
    <RightSidebar class="w-64 border-l border-gray-200" />
  </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue'
import Navbar from './components/Navbar.vue'
import RightSidebar from './components/RightSidebar.vue'

export default {
  name: 'App',
  components: {
    Sidebar,
    Navbar,
    RightSidebar
  }
}
</script>