<script setup>
// Vue simple pour lancer une campagne
const launchCampaign = () => {
  alert('Campagne lancée ! (Appel API PHP à venir)')
}
</script>

<template>
  <div class="page-wrapper fade-up">
    <div class="card main-card">
      <div class="page-header">
        <h1>Campagne de Box</h1>
        <router-link to="/back" class="btn btn-secondary">Retour</router-link>
      </div>

      <div class="campaign-form card" style="margin-top: 20px;">
        <h2>Démarrer une nouvelle campagne</h2>
        <p>Paramétrez et lancez l'optimisation de la composition des box.</p>
        
        <div class="form-group" style="margin-top: 20px;">
          <label>Poids maximum par box (g)</label>
          <input type="number" value="2000" />
        </div>

        <button @click="launchCampaign" class="btn btn-primary" style="width: 100%;">
          Lancer l'Optimisation
        </button>
      </div>

      <div class="history card" style="margin-top: 20px;">
        <h3>Historique des campagnes</h3>
        <p>Aucune campagne passée pour le moment.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: center; }
.campaign-form { background: rgba(56, 189, 248, 0.05); }
</style>
