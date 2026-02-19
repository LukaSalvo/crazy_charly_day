<script setup>
import { ref, onMounted } from 'vue'

const articles = ref([])
const loading  = ref(true)
const error    = ref('')

const newArticle = ref({
  designation: '',
  categorie: 1,
  age: 1,
  etat: 1,
  prix: 0,
  poids: 0
})

const categories = [
  { id: 1, libelle: 'SOC' },
  { id: 2, libelle: 'FIG' },
  { id: 3, libelle: 'CON' },
  { id: 4, libelle: 'EXT' },
  { id: 5, libelle: 'EVL' },
  { id: 6, libelle: 'LIV' }
]

const ages = [
  { id: 1, libelle: 'BB' },
  { id: 2, libelle: 'PE' },
  { id: 3, libelle: 'EN' },
  { id: 4, libelle: 'AD' }
]

const etats = [
  { id: 1, libelle: 'N' },
  { id: 2, libelle: 'TB' },
  { id: 3, libelle: 'B' }
]

async function fetchArticles() {
  loading.value = true
  try {
    const res = await fetch('https://back.cesareuh.fr/articles')
    if (!res.ok) throw new Error('Erreur lors du chargement des articles')
    articles.value = await res.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

async function addArticle() {
  const token = localStorage.getItem('token')
  try {
    const res = await fetch('https://back.cesareuh.fr/articles', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(newArticle.value)
    })
    
    if (res.status === 401 || res.status === 403) throw new Error('Accès non autorisé')
    if (!res.ok) throw new Error('Erreur lors de l\'ajout')
    
    newArticle.value = { designation: '', categorie: 1, age: 1, etat: 1, prix: 0, poids: 0 }
    await fetchArticles()
  } catch (err) {
    alert(err.message)
  }
}

async function deleteArticle(id) {
  if (!confirm('Voulez-vous vraiment supprimer cet article ?')) return
  
  const token = localStorage.getItem('token')
  try {
    const res = await fetch(`https://back.cesareuh.fr/articles/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    
    if (res.status === 401 || res.status === 403) throw new Error('Accès non autorisé')
    if (!res.ok) throw new Error('Erreur lors de la suppression')
    await fetchArticles()
  } catch (err) {
    alert(err.message)
  }
}

onMounted(fetchArticles)
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <div>
          <h1>Catalogue d'Articles</h1>
          <p class="subtitle">Gérez l'inventaire des jouets reconditionnés.</p>
        </div>
        <router-link to="/back" class="btn btn-secondary">← Dashboard</router-link>
      </div>

      <section class="add-section card">
        <h3>Ajouter un article</h3>
        <form @submit.prevent="addArticle" class="add-form">
          <div class="form-grid">
            <div class="form-group">
              <label>Désignation</label>
              <input v-model="newArticle.designation" required placeholder="Nom du jouet..." />
            </div>
            <div class="form-group">
              <label>Catégorie</label>
              <select v-model="newArticle.categorie" class="custom-select">
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.libelle }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>Tranche d'âge</label>
              <select v-model="newArticle.age" class="custom-select">
                <option v-for="a in ages" :key="a.id" :value="a.id">{{ a.libelle }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>État</label>
              <select v-model="newArticle.etat" class="custom-select">
                <option v-for="e in etats" :key="e.id" :value="e.id">{{ e.libelle }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>Prix (€)</label>
              <input v-model.number="newArticle.prix" type="number" step="0.01" required />
            </div>
            <div class="form-group">
              <label>Poids (kg)</label>
              <input v-model.number="newArticle.poids" type="number" step="0.001" required />
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Ajouter au catalogue</button>
        </form>
      </section>

      <div v-if="loading" class="loader">Chargement...</div>
      <div v-else-if="error" class="error-msg">{{ error }}</div>
      <div v-else class="table-wrapper card no-hover">
        <table>
          <thead>
            <tr>
              <th>Désignation</th>
              <th>Catégorie</th>
              <th>Âge</th>
              <th>État</th>
              <th>Prix</th>
              <th>Poids</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in articles" :key="a.id">
              <td><strong>{{ a.designation }}</strong></td>
              <td><span class="tag">{{ a.categorie }}</span></td>
              <td><span class="tag tag-alt">{{ a.age }}</span></td>
              <td>{{ a.etat }}</td>
              <td>{{ a.prix }}€</td>
              <td>{{ a.poids }}kg</td>
              <td style="text-align: right;">
                <button @click="deleteArticle(a.id)" class="btn-icon delete-btn" title="Supprimer">
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

.add-section { margin-bottom: 3rem; background: #fafafa; border: 1px dashed var(--border); padding: 2rem; }
.add-section h3 { margin-bottom: 1.5rem; }

.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }

.custom-select {
  width: 100%;
  padding: 0.8rem 1rem;
  border: 1px solid #eee;
  border-radius: 8px;
  background: white;
  font-size: 1rem;
  cursor: pointer;
}

.table-wrapper { margin-top: 1rem; padding: 0; overflow-x: auto; }
.no-hover:hover { transform: none; box-shadow: var(--shadow); }

table { width: 100%; border-collapse: collapse; text-align: left; }
th { padding: 1.25rem 1.5rem; border-bottom: 2px solid var(--bg); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--bg); vertical-align: middle; }

.tag { background: var(--bg); padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; color: var(--accent); }
.tag-alt { color: var(--border); }

.btn-icon { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); transition: var(--transition); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
.delete-btn:hover { background: #fee2e2; color: #e74c3c; }

.loader { padding: 3rem; text-align: center; color: var(--text-muted); }
.error-msg { padding: 1.5rem; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-top: 1rem; }
</style>
