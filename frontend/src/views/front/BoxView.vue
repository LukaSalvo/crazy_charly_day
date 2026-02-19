<script setup>
import { ref, onMounted } from 'vue'

const user = JSON.parse(localStorage.getItem('user') || '{}')
const articles = ref([])
const loading = ref(true)
const error = ref('')

async function fetchBox() {
  if (!user.id) {
    error.value = "Utilisateur non connecté."
    loading.value = false
    return
  }

  const token = localStorage.getItem('token')
  try {
    const res = await fetch(`https://back.cesareuh.fr/abonnes/${user.id}/box`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    if (res.status === 401) throw new Error('Session expirée ou non autorisée')
    if (!res.ok) throw new Error('Erreur lors du chargement de la box')
    articles.value = await res.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchBox)
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <div>
          <h1>Ma Box Composition</h1>
          <p class="subtitle">Voici les jouets sélectionnés spécialement pour vous.</p>
        </div>
        <router-link to="/" class="btn btn-secondary">← Retour</router-link>
      </div>

      <div v-if="loading" class="loader">Chargement de votre sélection...</div>
      <div v-else-if="error" class="error-msg">{{ error }}</div>
      <div v-else-if="articles.length === 0" class="empty-state card no-hover">
        <h3>Aucune box pour le moment</h3>
        <p>Votre box sera composée lors de la prochaine campagne !</p>
      </div>
      
      <div v-else class="composition-grid">
        <div v-for="a in articles" :key="a.id" class="article-item card no-hover">
          <div class="article-badge">{{ a.categorie }}</div>
          <div class="article-info">
            <h3>{{ a.designation }}</h3>
            <div class="article-meta">
              <span>{{ a.age }}</span>
              <span class="dot">•</span>
              <span>{{ a.etat }}</span>
            </div>
            <div class="article-footer">
              <span class="price">{{ a.prix }}€</span>
              <span class="weight">{{ a.poids }}kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; }
.subtitle { color: var(--text-muted); margin-top: 0.25rem; font-size: 0.95rem; }

.composition-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-top: 1rem;
}

.article-item {
  position: relative;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border: 1px solid #eee;
}

.article-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: var(--bg);
  color: var(--accent);
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
}

.article-info h3 { font-size: 1.25rem; margin-bottom: 0.5rem; color: var(--blue); }

.article-meta { display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem; }
.dot { font-size: 1.2rem; line-height: 0; }

.article-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid #f5f5f5;
}

.price { font-weight: 700; color: var(--brown); font-size: 1.1rem; }
.weight { color: var(--text-muted); font-size: 0.8rem; }

.loader { padding: 4rem; text-align: center; color: var(--text-muted); font-style: italic; }
.error-msg { padding: 1.5rem; background: #fee2e2; color: #991b1b; border-radius: 8px; }

.empty-state {
  padding: 4rem;
  text-align: center;
  background: #fafafa;
  border: 2px dashed #eee;
}
.empty-state h3 { margin-bottom: 0.5rem; }
.empty-state p { color: var(--text-muted); }

.no-hover:hover { transform: none; box-shadow: var(--shadow); }
</style>
