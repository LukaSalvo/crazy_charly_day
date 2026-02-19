<script setup>
// Vue simple pour modifier ses préférences
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card">
      <router-link to="/" class="back-link">← Retour</router-link>
      <h1>Mes Préférences</h1>
      <p>Modifiez l'ordre de vos catégories préférées.</p>

      <div class="form-group" style="margin-top: 20px;">
        <label>Email de l'abonné</label>
        <div style="display: flex; gap: 10px;">
          <input type="email" placeholder="votre@email.com" style="flex: 1;" />
          <button class="btn btn-primary">Charger</button>
        </div>
      </div>

      <div class="pref-placeholder card" style="margin-top: 20px; padding: 40px; text-align: center; background: rgba(255,255,255,0.05);">
        [ Liste ordonnable chargée après email ]
      </div>
    </div>
  </div>
</template>

<style scoped>
.back-link { font-size: 0.9rem; color: var(--text-muted); text-decoration: none; margin-bottom: 15px; display: inline-block; }
.main-card { max-width: 600px; width: 100%; margin: 0 auto; }
</style>
