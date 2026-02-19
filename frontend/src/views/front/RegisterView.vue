<script setup>
import { ref } from 'vue'

const email    = ref('')
const password = ref('')
const error    = ref('')
const success  = ref('')

async function submit() {
  error.value   = ''
  success.value = ''

  const res = await fetch('http://localhost:8082/auth/register', {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ email: email.value, password: password.value }),
  })

  const data = await res.json()

  if (!res.ok) {
    error.value = data.error ?? 'Erreur lors de l\'inscription'
    return
  }

  // Stocker le token pour les prochaines requêtes
  localStorage.setItem('token', data.token)
  success.value = 'Inscription réussie ! Bienvenue.'
}
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card form-card">
      <router-link to="/" class="back-link">← Retour</router-link>
      <h1>Inscription</h1>
      <p>Créez votre compte abonné.</p>

      <div v-if="error"   style="color: red;   margin-bottom: 1rem;">{{ error }}</div>
      <div v-if="success" style="color: green; margin-bottom: 1rem;">{{ success }}</div>

      <form @submit.prevent="submit">
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" type="email" required placeholder="votre@email.com" />
        </div>
        <div class="form-group">
          <label>Mot de passe</label>
          <input v-model="password" type="password" required placeholder="••••••••" />
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
          S'inscrire
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.page-wrapper { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
.form-card { max-width: 420px; width: 100%; }
.back-link { font-size: 0.875rem; color: var(--text-muted); display: inline-block; margin-bottom: 1rem; }
</style>
