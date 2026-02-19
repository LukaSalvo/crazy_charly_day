<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router   = useRouter()
const email    = ref('')
const password = ref('')
const error    = ref('')
const success  = ref('')

async function submit() {
  error.value   = ''
  success.value = ''

  try {
    const res = await fetch('https://back.cesareuh.fr/auth/login', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ email: email.value, password: password.value }),
    })

    const data = await res.json()

    if (!res.ok) {
      error.value = data.error ?? 'Identifiants incorrects'
      return
    }

    localStorage.setItem('token', data.token)
    localStorage.setItem('user',  JSON.stringify(data.user))
    
    success.value = 'Connexion réussie !'
    
    setTimeout(() => {
      router.push('/')
    }, 1000)
  } catch (err) {
    error.value = "Erreur de connexion au serveur"
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="card auth-card fade-up">
      <router-link to="/" class="back-link">← Retour à l'accueil</router-link>
      <div class="auth-header">
        <h1>Bon retour !</h1>
        <p>Entrez vos identifiants pour accéder à votre box.</p>
      </div>

      <div v-if="error"   class="alert alert-error">{{ error }}</div>
      <div v-if="success" class="alert alert-success">{{ success }}</div>

      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label>Email professionnel ou personnel</label>
          <input v-model="email" type="email" required placeholder="votre@email.com" />
        </div>
        <div class="form-group">
          <label>Mot de passe</label>
          <input v-model="password" type="password" required placeholder="••••••••" />
        </div>

        <button type="submit" class="btn btn-primary w-full">
          Se connecter
        </button>
      </form>
      
      <div class="auth-footer">
        Pas encore de compte ? 
        <router-link to="/front/inscription">Créer un compte</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-page { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; background: var(--bg); }
.auth-card { max-width: 480px; width: 100%; }
.auth-header { margin-bottom: 2.5rem; text-align: center; }
.auth-header h1 { font-size: 2rem; margin-bottom: 0.5rem; }
.auth-header p { color: var(--text-muted); }

.back-link { font-size: 0.875rem; color: var(--accent); text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 2rem; transition: var(--transition); }
.back-link:hover { transform: translateX(-5px); }

.alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600; text-align: center; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert-success { background: #dcfce7; color: #166534; }

.w-full { width: 100%; }

.auth-footer { margin-top: 2rem; text-align: center; font-size: 0.9rem; color: var(--text-muted); border-top: 1px solid #f5f5f5; padding-top: 1.5rem; }
.auth-footer a { color: var(--accent); font-weight: 700; text-decoration: none; }
</style>
