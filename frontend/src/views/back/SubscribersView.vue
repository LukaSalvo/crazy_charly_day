<script setup>
// Vue simple pour la liste des abonnés
const subscribers = [
  { id: 1, prenom: 'Jean', nom: 'Dupont', email: 'jean@dupont.fr', age: '3-5 ans' },
  { id: 2, prenom: 'Marie', nom: 'Curie', email: 'marie@curie.fr', age: '6-8 ans' }
]
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card-wide">
      <div class="page-header">
        <h1>Gestion des Abonnés</h1>
        <router-link to="/back" class="btn btn-secondary">Retour</router-link>
      </div>

      <div class="table-wrapper card" style="margin-top: 20px;">
        <table>
          <thead>
            <tr>
              <th>Nom / Prénom</th>
              <th>Email</th>
              <th>Tranche d'âge</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in subscribers" :key="s.id">
              <td>{{ s.nom }} {{ s.prenom }}</td>
              <td>{{ s.email }}</td>
              <td>{{ s.age }}</td>
              <td>
                <button class="btn btn-secondary btn-sm">Editer</button>
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
