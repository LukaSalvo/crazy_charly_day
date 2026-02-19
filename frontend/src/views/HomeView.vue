<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user   = ref(null)

onMounted(() => {
  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
})

function logout() {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  user.value = null
  router.push('/front/connexion')
}
</script>

<template>
  <div class="home-wrapper fade-up">
    <!-- Header with User info -->
    <header class="top-nav">
      <div class="container">
        <div class="logo">ToyBoxing</div>
        <div v-if="user" class="user-control">
          <span class="user-info">{{ user.email }} <span class="role-tag">{{ user.role }}</span></span>
          <button @click="logout" class="btn-logout">Déconnexion</button>
        </div>
        <div v-else class="user-control">
          <router-link to="/front/connexion" class="nav-link">Connexion</router-link>
        </div>
      </div>
    </header>

    <main class="main-content">
      <div class="hero">
        <h1>ToyBoxing</h1>
        <p class="subtitle">Donnez une seconde vie aux jouets, une box à la fois.</p>
      </div>

      <div class="sections-grid">
        <!-- Guest Section -->
        <section v-if="!user" class="card action-section">
          <h2>Prêt à commencer ?</h2>
          <p>Rejoignez notre communauté de parents engagés.</p>
          <div class="btn-stack">
            <router-link to="/front/inscription" class="btn btn-primary">Créer mon compte</router-link>
            <router-link to="/front/connexion" class="btn btn-secondary">Se connecter</router-link>
          </div>
        </section>

        <!-- Subscriber Section -->
        <section v-if="user && (user.role === 'abonne' || user.role === 'admin')" class="card action-section">
          <h2>Espace Abonné</h2>
          <p>Gérez vos préférences et suivez vos box.</p>
          <div class="btn-group-row">
            <router-link to="/front/ma-box" class="btn btn-primary">Ma Box</router-link>
            <router-link to="/front/preferences" class="btn btn-secondary">Préférences</router-link>
          </div>
        </section>

        <!-- Admin Section -->
        <section v-if="user && user.role === 'admin'" class="card action-section admin-special">
          <h2>Gestion Boutique</h2>
          <p>Accès aux outils d'administration et catalogue.</p>
          <div class="btn-group-row">
            <router-link to="/back" class="btn btn-primary">Tableau de bord</router-link>
            <router-link to="/back/catalogue" class="btn btn-secondary">Catalogue</router-link>
          </div>
        </section>
      </div>
    </main>

    <footer class="footer">
      <p>&copy; 2026 ToyBoxing - Reconditionné avec amour.</p>
    </footer>
  </div>
</template>

<style scoped>
.home-wrapper { min-height: 100vh; display: flex; flex-direction: column; background: var(--bg); }

.top-nav { background: white; padding: 1rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 2rem; }
.top-nav .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: flex; justify-content: space-between; align-items: center; }
.logo { font-size: 1.5rem; font-weight: 800; color: var(--accent); letter-spacing: -1px; }

.user-control { display: flex; align-items: center; gap: 1.5rem; font-size: 0.9rem; }
.role-tag { background: var(--bg); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; color: var(--border); text-transform: uppercase; }
.btn-logout { background: none; border: none; font-weight: 600; color: #e74c3c; cursor: pointer; border-bottom: 1px solid transparent; transition: var(--transition); }
.btn-logout:hover { border-bottom-color: #e74c3c; }
.nav-link { text-decoration: none; color: var(--text); font-weight: 600; }

.main-content { flex: 1; max-width: 1100px; width: 100%; margin: 0 auto; padding: 0 2rem 4rem; text-align: center; }

.hero { margin: 4rem 0 6rem; }
h1 { font-size: 4rem; margin-bottom: 0.5rem; line-height: 1; }
.subtitle { font-size: 1.25rem; color: var(--text-muted); max-width: 600px; margin: 0 auto; }

.sections-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; justify-content: center; }
.action-section { text-align: left; display: flex; flex-direction: column; height: 100%; }
.action-section h2 { margin-bottom: 0.75rem; font-size: 1.5rem; }
.action-section p { margin-bottom: 2rem; color: var(--text-muted); flex: 1; }

.admin-special { border-top: 4px solid var(--accent); }

.btn-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.btn-stack { display: flex; flex-direction: column; gap: 0.75rem; }

.footer { padding: 4rem 2rem; text-align: center; font-size: 0.875rem; color: var(--text-muted); border-top: 1px solid rgba(0,0,0,0.03); }

@media (max-width: 768px) {
  h1 { font-size: 3rem; }
  .sections-grid { grid-template-columns: 1fr; }
}
</style>
