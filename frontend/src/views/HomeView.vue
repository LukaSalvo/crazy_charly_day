<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="home-wrapper fade-up">
    <div class="card home-card">
      <h1>ToyBoxing</h1>
      <p>Gestion de box de jouets reconditionnés.</p>

      <div class="home-sections">
        <div class="section-block card">
          <h2>Espace Abonnés</h2>
          <div class="btn-group">
            <router-link to="/front/inscription" class="btn btn-primary">Inscription</router-link>
            <router-link to="/front/ma-box" class="btn btn-secondary">Ma Box</router-link>
          </div>
        </div>

        <div class="section-block card">
          <h2>Espace Gestion</h2>
          <div class="btn-group">
            <router-link to="/back" class="btn btn-primary">Dashboard</router-link>
            <router-link to="/back/catalogue" class="btn btn-secondary">Catalogue</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home-wrapper { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
.home-card { max-width: 800px; width: 100%; text-align: center; }
p { margin-bottom: 2rem; }
.home-sections { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; text-align: left; }
.btn-group { display: flex; flex-direction: column; gap: 0.5rem; }
@media (max-width: 600px) { .home-sections { grid-template-columns: 1fr; } }
</style>
