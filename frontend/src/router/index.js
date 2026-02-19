import { createRouter, createWebHistory } from 'vue-router'

// Front-office
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/front/RegisterView.vue'
import BoxView from '../views/front/BoxView.vue'
import PreferencesView from '../views/front/PreferencesView.vue'

// Back-office
import BackDashboardView from '../views/back/DashboardView.vue'
import CatalogueView from '../views/back/CatalogueView.vue'
import SubscribersView from '../views/back/SubscribersView.vue'
import CampaignView from '../views/back/CampaignView.vue'

const routes = [
  // Accueil
  { path: '/', component: HomeView },

  // Front-office (abonnés)
  { path: '/front/inscription', component: RegisterView },
  { path: '/front/ma-box', component: BoxView },
  { path: '/front/preferences', component: PreferencesView },

  // Back-office (gestion)
  { path: '/back', component: BackDashboardView },
  { path: '/back/catalogue', component: CatalogueView },
  { path: '/back/abonnes', component: SubscribersView },
  { path: '/back/campagne', component: CampaignView },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})
