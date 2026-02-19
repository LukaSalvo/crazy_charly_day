<script setup>
// Vue simplifiée du catalogue
const articles = [
  { id: 1, designation: 'Puzzle Animaux', categorie: 'Jeux', age: '3-5 ans', poids: '400g' },
  { id: 2, designation: 'Livre de Contes', categorie: 'Livres', age: '6-8 ans', poids: '300g' }
]
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <h1>Catalogue des Articles</h1>
        <router-link to="/back" class="btn btn-secondary">Retour</router-link>
      </div>

      <div class="table-wrapper card" style="margin-top: 20px;">
        <table>
          <thead>
            <tr>
              <th>Désignation</th>
              <th>Catégorie</th>
              <th>Tranche d'âge</th>
              <th>Poids</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in articles" :key="a.id">
              <td>{{ a.designation }}</td>
              <td>{{ a.categorie }}</td>
              <td>{{ a.age }}</td>
              <td>{{ a.poids }}</td>
              <td>
                <button class="btn btn-secondary btn-sm">Gérer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: center; }
.btn-sm { padding: 5px 10px; font-size: 0.8rem; }
</style>
