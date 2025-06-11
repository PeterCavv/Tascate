<template>
  <aside class="sidebar" :class="{ 'collapsed': isCollapsed }">
    <div class="sidebar-header">
      <Button
        icon="pi pi-bars"
        class="p-button-text toggle-btn"
        @click="toggleSidebar"
      />
    </div>

    <nav class="sidebar-nav">
      <div v-for="item in menuItems" :key="item.label" class="nav-item">
        <Button
          :icon="item.icon"
          :label="isCollapsed ? '' : item.label"
          class="p-button-text nav-button"
          :class="{ 'active': item.active }"
        />
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from 'vue';
import Button from 'primevue/button';

const isCollapsed = ref(false);
const menuItems = ref([
  { icon: '/images/lemon.ico', label: 'Tascas', active: false },
  { icon: 'pi pi-home', label: 'Home', active: false },
  { icon: 'pi pi-comments', label: 'Messages', active: false },
  { icon: 'pi pi-calendar', label: 'Reservations', active: false },
  { icon: 'pi pi-user', label: 'Profile', active: false },
  { icon: 'pi pi-cog', label: 'Configuration', active: false },
]);

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};
</script>

<style scoped>
.sidebar {
  position: fixed;
  left: 0;
  top: var(--navbar-height);
  bottom: var(--footer-height);
  width: var(--sidebar-width);
  background: var(--background-color);
  border-right: 1px solid rgba(0, 0, 0, 0.1);
  transition: width 0.3s ease;
  z-index: 900;
}

.dark .sidebar {
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar.collapsed {
  width: 64px;
}

.sidebar-header {
  padding: calc(var(--spacing-unit) * 2);
  display: flex;
  justify-content: flex-end;
}

.sidebar-nav {
  padding: calc(var(--spacing-unit) * 2);
  display: flex;
  flex-direction: column;
  gap: var(--spacing-unit);
}

.nav-item {
  width: 100%;
}

.nav-button {
  width: 100%;
  justify-content: flex-start;
  padding: calc(var(--spacing-unit) * 1.5);
  border-radius: var(--border-radius);
}

.nav-button.active {
  background: var(--primary-color);
  color: white;
}

.nav-button:hover:not(.active) {
  background: rgba(0, 0, 0, 0.05);
}

.dark .nav-button:hover:not(.active) {
  background: rgba(255, 255, 255, 0.05);
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar.collapsed {
    transform: translateX(0);
    width: 64px;
  }
}
</style>
