<script setup>
import { ref, onMounted } from 'vue'

const boxes = ref([])
const loading = ref(true)
const error = ref('')

async function fetchBoxes() {
  loading.value = true
  const token = localStorage.getItem('token')
  try {
    const res = await fetch('https://back.cesareuh.fr/boxes', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    
    if (!res.ok) {
      const data = await res.json().catch(() => ({}))
      throw new Error(data.error || `Erreur ${res.status}`)
    }
    boxes.value = await res.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

async function validateBox(id) {
  try {
    const res = await fetch(`https://back.cesareuh.fr/boxes/${id}/valider`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (res.ok) {
      const box = boxes.value.find(b => (b.box && b.box.id === id) || b.id === id)
      if (box) {
        if (box.box) box.box.valide = true
        else box.valide = true
      }
    }
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchBoxes)
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <div>
          <h1>Gestion des Box</h1>
          <p class="subtitle">Consultez et validez les compositions générées par l'algorithme.</p>
        </div>
        <router-link to="/back" class="btn btn-secondary">← Dashboard</router-link>
      </div>

      <div v-if="loading" class="loader">
        <div class="spinner"></div>
        Chargement des box...
      </div>

      <div v-else-if="error" class="error-msg">
        {{ error }}
      </div>

      <div v-else class="boxes-grid">
        <div v-for="item in boxes" :key="item.box ? item.box.id : item.id" 
             class="box-card card" 
             :class="{ 'is-valide': item.box ? item.box.valide : item.valide }">
          
          <div class="box-header">
            <h3 class="client-name">{{ item.client_nom }}</h3>
            <span class="tag" :class="(item.box ? item.box.valide : item.valide) ? 'tag-success' : 'tag-warning'">
              {{ (item.box ? item.box.valide : item.valide) ? 'Validée' : 'En attente' }}
            </span>
          </div>

          <div class="box-stats">
            <div class="stat">
              <span class="label">Poids</span>
              <span class="value">{{ (item.box ? item.box.poids : item.poids).toFixed(2) }}kg</span>
            </div>
            <div class="stat">
              <span class="label">Valeur</span>
              <span class="value">{{ (item.box ? item.box.prix : item.prix).toFixed(2) }}€</span>
            </div>
            <div class="stat">
              <span class="label">Score</span>
              <span class="value">{{ item.box ? item.box.score : item.score }} pts</span>
            </div>
          </div>

          <div class="articles-list">
            <h4>Contenu de la box</h4>
            <div v-for="art in (item.box ? item.box.articles : item.articles)" :key="art.id" class="article-item">
              <span class="art-name">{{ art.designation }}</span>
              <span class="art-meta">{{ art.prix }}€ • {{ art.categorie }}</span>
            </div>
          </div>

          <button 
            v-if="!(item.box ? item.box.valide : item.valide)" 
            @click="validateBox(item.box ? item.box.id : item.id)" 
            class="btn btn-primary validate-btn"
          >
            Valider la box
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-wrapper { min-height: 100vh; padding: 2rem; }
.main-card-wide { padding: 3rem; }

.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 3rem; }
.subtitle { color: var(--text-muted); margin-top: 0.25rem; }

.boxes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 2rem;
}

.box-card {
  padding: 2rem;
  background: white;
  border: 1px solid var(--bg);
  display: flex;
  flex-direction: column;
  transition: var(--transition);
}

.box-card:hover {
  transform: translateY(-5px);
  border-color: var(--accent);
}

.box-card.is-valide {
  background: #fcfdfc;
  border-color: #dcfce7;
}

.box-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.client-name { font-size: 1.25rem; font-weight: 800; color: var(--text); }

.box-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  background: var(--bg);
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 2rem;
}

.stat { display: flex; flex-direction: column; align-items: center; }
.stat .label { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
.stat .value { font-weight: 800; font-size: 1rem; color: var(--text); }

.articles-list { flex-grow: 1; margin-bottom: 2rem; }
.articles-list h4 { font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem; letter-spacing: 0.05em; }

.article-item {
  display: flex;
  flex-direction: column;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--bg);
}
.article-item:last-child { border-bottom: none; }
.art-name { font-size: 0.95rem; font-weight: 600; color: var(--text); }
.art-meta { font-size: 0.8rem; color: var(--text-muted); }

.validate-btn { width: 100%; margin-top: auto; padding: 1rem; font-weight: 700; }

.loader { padding: 5rem; text-align: center; color: var(--text-muted); display: flex; flex-direction: column; align-items: center; gap: 1rem; }
.spinner { width: 40px; height: 40px; border: 4px solid var(--bg); border-top-color: var(--accent); border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.tag-success { background: #dcfce7; color: #166534; }
.tag-warning { background: #fef3c7; color: #92400e; }

.error-msg { 
  padding: 2rem; 
  background: #fee2e2; 
  color: #991b1b; 
  border-radius: 12px; 
  text-align: center; 
  font-weight: 600;
  margin-top: 2rem;
}

@media (max-width: 768px) {
  .page-wrapper { padding: 1rem; }
  .main-card-wide { padding: 1.5rem; }
  .page-header { flex-direction: column; gap: 1rem; }
}
</style>
