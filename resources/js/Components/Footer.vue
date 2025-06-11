<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="social-links">
        <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="social-link">
          <i class="pi pi-github"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="social-link">
          <i class="pi pi-linkedin"></i>
        </a>
      </div>
      
      <div class="copyright">
        © {{ new Date().getFullYear() }} Tascate. All rights reserved.
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: var(--footer-height);
  background: var(--background-color);
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  z-index: 900;
}

.dark .footer {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 calc(var(--spacing-unit) * 2);
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.social-links {
  display: flex;
  gap: calc(var(--spacing-unit) * 2);
}

.social-link {
  color: var(--text-color);
  font-size: 1.25rem;
  transition: color 0.2s ease;
}

.social-link:hover {
  color: var(--primary-color);
}

.copyright {
  color: var(--text-color);
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: var(--spacing-unit);
    padding: calc(var(--spacing-unit) * 2);
  }
  
  .social-links {
    order: 2;
  }
  
  .copyright {
    order: 1;
  }
}
</style> 