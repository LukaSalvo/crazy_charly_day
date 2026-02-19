<script setup>
import { ref } from 'vue'

const loading = ref(false)
const message = ref('')
const campaignParams = ref({
  poids_max: 2000,
  prix_min: 50,
  prix_max: 150
})

async function launchCampaign() {
  loading.value = true
  message.value = ''
  const token = localStorage.getItem('token')
  try {
    const res = await fetch('http://localhost:8082/campagnes', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(campaignParams.value)
    })
    
    if (res.status === 401 || res.status === 403) throw new Error('Accès non autorisé')
    if (!res.ok) throw new Error('Erreur lors du lancement de la campagne')
    
    message.value = 'Campagne lancée avec succès ! Les box ont été générées.'
  } catch (err) {
    message.value = `Erreur: ${err.message}`
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card">
      <div class="page-header">
        <div>
          <h1>Campagne de Box</h1>
          <p class="subtitle">Lancez l'optimisation automatique des compositions.</p>
        </div>
        <router-link to="/back" class="btn btn-secondary">← Retour</router-link>
      </div>

      <div class="campaign-form card no-hover">
        <h2>Paramètres de la campagne</h2>
        <div class="form-grid">
          <div class="form-group">
            <label>Poids max par box (g)</label>
            <input v-model.number="campaignParams.poids_max" type="number" />
          </div>
          <div class="form-group">
            <label>Prix minimum (€)</label>
            <input v-model.number="campaignParams.prix_min" type="number" />
          </div>
          <div class="form-group">
            <label>Prix maximum (€)</label>
            <input v-model.number="campaignParams.prix_max" type="number" />
          </div>
        </div>
        
        <button @click="launchCampaign" :disabled="loading" class="btn btn-primary full-width">
          {{ loading ? 'Optimisation en cours...' : 'Lancer l\'Optimisation' }}
        </button>

        <p v-if="message" :class="['status-msg', { error: message.startsWith('Erreur') }]">
          {{ message }}
        </p>
      </div>

      <div class="info-card card no-hover">
        <h3>Comment ça marche ?</h3>
        <p>L'algorithme de ToyBoxing analyse les préférences de chaque abonné (catégories, âge) et sélectionne les meilleurs articles disponibles dans le catalogue pour composer une box optimisée.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; }
.subtitle { color: var(--text-muted); margin-top: 0.25rem; font-size: 0.95rem; }

.campaign-form { background: #f0f9ff; border: 1px solid #bae6fd; padding: 2rem; margin-top: 1rem; }
.form-grid { display: grid; gap: 1rem; margin: 1.5rem 0; }

.full-width { width: 100%; padding: 1rem; margin-top: 1rem; font-size: 1.1rem; }

.status-msg { margin-top: 1.5rem; padding: 1rem; border-radius: 8px; background: #dcfce7; color: #166534; font-weight: 600; text-align: center; }
.status-msg.error { background: #fee2e2; color: #991b1b; }

.info-card { margin-top: 2rem; padding: 1.5rem; background: #fafafa; }
.info-card h3 { margin-bottom: 0.5rem; font-size: 1.1rem; }
.info-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; }

.no-hover:hover { transform: none; box-shadow: var(--shadow); }
</style>
