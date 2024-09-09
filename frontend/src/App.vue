<!-- App.vue -->
<template>
  <div class="flex h-screen bg-gray-100">
    <Sidebar class="w-64 flex-shrink-0" />
    <div class="flex-1 flex flex-col overflow-hidden">
      <Navbar />
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <router-view></router-view>
      </main>
    </div>
    <RightSidebar class="w-64 flex-shrink-0" />
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