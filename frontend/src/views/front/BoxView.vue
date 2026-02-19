<script setup>
// Vue simple pour consulter sa box
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card">
      <router-link to="/" class="back-link">← Retour</router-link>
      <h1>Ma Box</h1>
      <p>Consultez la composition de votre box actuelle.</p>

      <div class="form-group" style="margin-top: 20px;">
        <label>Email de l'abonné</label>
        <div style="display: flex; gap: 10px;">
          <input type="email" placeholder="votre@email.com" style="flex: 1;" />
          <button class="btn btn-primary">Consulter</button>
        </div>
      </div>

      <div class="box-preview card" style="margin-top: 20px; padding: 40px; text-align: center; border-style: dashed;">
        <p>Entrez votre email pour voir votre box.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.back-link { font-size: 0.9rem; color: var(--text-muted); text-decoration: none; margin-bottom: 15px; display: inline-block; }
.main-card { max-width: 600px; width: 100%; margin: 0 auto; }
</style>
