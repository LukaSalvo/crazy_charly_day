<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="dashboard">
      <div class="dash-header card">
        <h1>Back-office</h1>
        <RouterLink to="/" class="btn btn-secondary">Accueil</RouterLink>
      </div>

      <div class="nav-grid">
        <RouterLink to="/back/catalogue" class="card nav-card">
          <h2>Catalogue</h2>
        </RouterLink>
        <RouterLink to="/back/abonnes" class="card nav-card">
          <h2>Abonnés</h2>
        </RouterLink>
        <RouterLink to="/back/campagne" class="card nav-card">
          <h2>Campagnes</h2>
        </RouterLink>
        <RouterLink to="/back/boxes" class="card nav-card">
          <h2>Gestion des Box</h2>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-wrapper { min-height: 100vh; padding: 2rem; display: flex; justify-content: center; }
.dashboard { max-width: 800px; width: 100%; display: flex; flex-direction: column; gap: 1.5rem; }
.dash-header { display: flex; align-items: center; justify-content: space-between; }
.nav-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.nav-card { text-align: center; padding: 2rem; }
@media (max-width: 600px) { .nav-grid { grid-template-columns: 1fr; } }

</style>
