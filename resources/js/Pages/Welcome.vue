<template>
  <AppLayout>
    <div class="welcome-container">
      <h1 class="welcome-title">Welcome to Tascate</h1>
      <p class="welcome-subtitle">Your ultimate tasca management platform</p>
      
      <div class="features-grid">
        <div class="feature-card">
          <i class="pi pi-list feature-icon"></i>
          <h3>Manage Tascas</h3>
          <p>Easily manage and organize your favorite tascas</p>
        </div>
        
        <div class="feature-card">
          <i class="pi pi-calendar feature-icon"></i>
          <h3>Reservations</h3>
          <p>Book tables and manage your reservations</p>
        </div>
        
        <div class="feature-card">
          <i class="pi pi-comments feature-icon"></i>
          <h3>Community</h3>
          <p>Connect with other tasca enthusiasts</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
</script>

<style scoped>
.welcome-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: calc(var(--spacing-unit) * 4);
}

.welcome-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--primary-color);
  margin-bottom: var(--spacing-unit);
  text-align: center;
}

.welcome-subtitle {
  font-size: 1.25rem;
  color: var(--text-color);
  text-align: center;
  margin-bottom: calc(var(--spacing-unit) * 6);
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: calc(var(--spacing-unit) * 4);
  margin-top: calc(var(--spacing-unit) * 6);
}

.feature-card {
  background: white;
  padding: calc(var(--spacing-unit) * 4);
  border-radius: var(--border-radius);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: transform 0.2s ease;
}

.dark .feature-card {
  background: #2D3748;
}

.feature-card:hover {
  transform: translateY(-4px);
}

.feature-icon {
  font-size: 2rem;
  color: var(--primary-color);
  margin-bottom: calc(var(--spacing-unit) * 2);
}

.feature-card h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: var(--spacing-unit);
  color: var(--text-color);
}

.feature-card p {
  color: var(--text-color);
  opacity: 0.8;
}

@media (max-width: 768px) {
  .welcome-container {
    padding: calc(var(--spacing-unit) * 2);
  }
  
  .welcome-title {
    font-size: 2rem;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
  }
}
</style>
