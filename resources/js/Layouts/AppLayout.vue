<template>
  <div class="app-container" :class="{ 'dark': isDarkMode }">
    <Navbar />
    <div class="main-container">
      <Sidebar />
      <main class="content-area">
        <slot></slot>
      </main>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '../components/Navbar.vue';
import Sidebar from '../components/Sidebar.vue';
import Footer from '../components/Footer.vue';

const isDarkMode = ref(false);
</script>

<style>
:root {
  --primary-color: #10b981;
  --secondary-color: #FAD600;
  --background-color: #F9FAFB;
  --text-color: #1F2937;
  --sidebar-width: 250px;
  --navbar-height: 64px;
  --footer-height: 60px;
  --border-radius: 12px;
  --spacing-unit: 8px;
}

.dark {
  --background-color: #1F2937;
  --text-color: #F9FAFB;
}

.app-container {
  min-height: 100vh;
  background-color: var(--background-color);
  color: var(--text-color);
}

.main-container {
  display: flex;
  min-height: calc(100vh - var(--navbar-height) - var(--footer-height));
}

.content-area {
  flex: 1;
  padding: calc(var(--spacing-unit) * 3);
  margin-left: var(--sidebar-width);
  transition: margin-left 0.3s ease;
}

@media (max-width: 768px) {
  .content-area {
    margin-left: 0;
  }
}
</style> 