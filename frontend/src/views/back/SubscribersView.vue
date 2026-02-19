<script setup>
import { ref, onMounted } from 'vue'

const subscribers = ref([])
const loading = ref(true)
const error = ref('')

async function fetchSubscribers() {
  const token = localStorage.getItem('token')
  try {
    const res = await fetch('https://back.cesareuh.fr/abonnes', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    if (res.status === 401 || res.status === 403) throw new Error('Accès non autorisé')
    if (!res.ok) throw new Error('Erreur lors du chargement des abonnés')
    subscribers.value = await res.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

async function deleteSubscriber(id) {
  if (!confirm('Voulez-vous vraiment supprimer cet abonné ?')) return
  
  const token = localStorage.getItem('token')
  try {
    const res = await fetch(`https://back.cesareuh.fr/abonnes/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    })
    if (!res.ok) throw new Error('Erreur lors de la suppression')
    await fetchSubscribers()
  } catch (err) {
    alert(err.message)
  }
}

onMounted(fetchSubscribers)
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <div>
          <h1>Gestion des Abonnés</h1>
          <p class="subtitle">Consultez et gérez la liste des parents inscrits.</p>
        </div>
        <router-link to="/back" class="btn btn-secondary">← Dashboard</router-link>
      </div>

      <div v-if="loading" class="loader">Chargement des abonnés...</div>
      <div v-else-if="error" class="error-msg">{{ error }}</div>
      <div v-else class="table-wrapper card no-hover">
        <table>
          <thead>
            <tr>
              <th>Nom / Prénom</th>
              <th>Email</th>
              <th>Préférences</th>
              <th>Âge</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in subscribers" :key="s.id">
              <td>
                <div class="user-cell">
                  <strong>{{ s.nom }}</strong>
                  <span>{{ s.prenom }}</span>
                </div>
              </td>
              <td class="email-td">{{ s.email }}</td>
              <td>
                <div class="tags-row">
                  <span v-for="(c, i) in s.categories" :key="i" v-show="c" class="tag">{{ c }}</span>
                </div>
              </td>
              <td><span class="age-badge">{{ s.age }}</span></td>
              <td style="text-align: right;">
                <button @click="deleteSubscriber(s.id)" class="btn-icon delete-btn" title="Supprimer">
                  &times;
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; }
.subtitle { color: var(--text-muted); margin-top: 0.25rem; font-size: 0.95rem; }

.table-wrapper { margin-top: 1rem; padding: 0; overflow-x: auto; }
.no-hover:hover { transform: none; box-shadow: var(--shadow); }

table { width: 100%; border-collapse: collapse; text-align: left; }
th { padding: 1.25rem 1.5rem; border-bottom: 2px solid var(--bg); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--bg); vertical-align: middle; }

.user-cell { display: flex; flex-direction: column; }
.user-cell strong { color: var(--blue); }
.user-cell span { font-size: 0.8rem; color: var(--text-muted); }

.email-td { color: var(--text-muted); font-size: 0.9rem; }

.tags-row { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.tag { background: #f0f7ff; color: #0284c7; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 700; }

.age-badge { background: #fef3c7; color: #92400e; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }

.btn-icon { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); transition: var(--transition); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
.delete-btn:hover { background: #fee2e2; color: #e74c3c; }

.loader { padding: 4rem; text-align: center; color: var(--text-muted); }
.error-msg { padding: 1.5rem; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-top: 1rem; }
</style>
