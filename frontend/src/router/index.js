import { createRouter, createWebHistory } from 'vue-router'

// Front-office
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/front/RegisterView.vue'
import LoginView from '../views/front/LoginView.vue'
import BoxView from '../views/front/BoxView.vue'
import PreferencesView from '../views/front/PreferencesView.vue'

// Back-office
import BackDashboardView from '../views/back/DashboardView.vue'
import CatalogueView from '../views/back/CatalogueView.vue'
import SubscribersView from '../views/back/SubscribersView.vue'
import CampaignView from '../views/back/CampaignView.vue'
import BoxesView from '../views/back/BoxesView.vue'

const routes = [
  { path: '/', component: HomeView },
  { path: '/front/inscription', component: RegisterView },
  { path: '/front/connexion', component: LoginView },
  { path: '/front/ma-box', component: BoxView, meta: { requiresAuth: true } },
  { path: '/front/preferences', component: PreferencesView, meta: { requiresAuth: true } },

  { 
    path: '/back', 
    component: BackDashboardView, 
    meta: { requiresAuth: true, requiresAdmin: true } 
  },
  { 
    path: '/back/catalogue', 
    component: CatalogueView, 
    meta: { requiresAuth: true, requiresAdmin: true } 
  },
  { 
    path: '/back/abonnes', 
    component: SubscribersView, 
    meta: { requiresAuth: true, requiresAdmin: true } 
  },
  { 
    path: '/back/campagne', 
    component: CampaignView, 
    meta: { requiresAuth: true, requiresAdmin: true } 
  },
  { 
    path: '/back/boxes', 
    component: BoxesView, 
    meta: { requiresAuth: true, requiresAdmin: true } 
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || 'null')

  if (to.meta.requiresAuth && !token) {
    next('/front/connexion')
  } else if (to.meta.requiresAdmin && (!user || user.role !== 'admin')) {
    next('/')
  } else {
    next()
  }
})

export default router
