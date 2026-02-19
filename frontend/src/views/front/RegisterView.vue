<script setup>
// Vue simple pour l'inscription
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card form-card">
      <router-link to="/" class="back-link">← Retour</router-link>
      <h1>Inscription</h1>
      <p>Créez votre compte abonné et définissez vos préférences.</p>

      <form @submit.prevent="alert('Inscription envoyée !')">
        <div class="form-group">
          <label>Email</label>
          <input type="email" required placeholder="votre@email.com" />
        </div>
        <div class="form-group">
          <label>Tranche d'âge de l'enfant</label>
          <select required>
            <option value="">-- Choisir --</option>
            <option>0-2 ans</option>
            <option>3-5 ans</option>
            <option>6-8 ans</option>
            <option>9-12 ans</option>
          </select>
        </div>
        
        <h3 style="margin-top: 20px;">Catégories préférées (1 à 6)</h3>
        <p>Définissez l'ordre de vos préférences.</p>
        
        <div class="pref-placeholder card" style="margin-top: 10px; padding: 20px; text-align: center; background: rgba(255,255,255,0.05);">
          [ Liste des catégories ordonnable ici ]
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 20px; width: 100%;">
          S'inscrire
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.back-link { font-size: 0.9rem; color: var(--text-muted); text-decoration: none; margin-bottom: 15px; display: inline-block; }
.form-card { max-width: 500px; width: 100%; margin: 0 auto; }
</style>
