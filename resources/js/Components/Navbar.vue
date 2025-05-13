<template>
  <nav class="navbar">
    <div class="navbar-content">
      <div class="logo">
<!--        <img src="/logo.svg" alt="Tascate Logo" class="logo-img" />-->
<!--        <span class="logo-text">Tascate</span>-->
          <img
                                              v-if="isDarkMode"
                                              src="/images/tascate-light.png"
                                              alt="Logo Dark Mode"
                                              class="h-12 w-auto"
                                          />
                                          <img
                                              v-else
                                              src="/images/tascate-dark.png"
                                              alt="Logo Light Mode"
                                              class=" h-12 w-auto"
                                          />
      </div>

      <div class="nav-actions">
        <Button label="Login" class="p-button-text" />
        <Button label="Register" class="p-button-raised" />
      </div>
    </div>
  </nav>
</template>

<script setup>
import Button from 'primevue/button';
import {computed} from "vue";

const isDarkMode = computed(() => {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
});
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: var(--navbar-height);
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.dark .navbar {
  background: rgba(31, 41, 55, 0.8);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.navbar-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 calc(var(--spacing-unit) * 2);
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  display: flex;
  align-items: center;
  gap: var(--spacing-unit);
}

.logo-img {
  height: 32px;
  width: auto;
}

.logo-text {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text-color);
}

.nav-actions {
  display: flex;
  gap: calc(var(--spacing-unit) * 2);
}

@media (max-width: 768px) {
  .logo-text {
    display: none;
  }

  .nav-actions {
    gap: var(--spacing-unit);
  }
}
</style>
