import { createRouter, createWebHistory } from 'vue-router'

// Importa tus páginas
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import Projects from '../pages/Projects.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/projects', component: Projects },

  {
    path: '/projects/:id',
    component: () => import('../pages/ProjectDetail.vue'),
    props: true
  },

  {
    path: '/shared-projects',
    name: 'SharedProjects',
    component: () => import('../pages/SharedProjects.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Cuando loggeas esto te redirige al Dashboard
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('auth_token')

  if (to.path === '/dashboard' && !isAuthenticated) {
    next('/login')
  } else if (
    (to.path === '/login' || to.path === '/register')
    && isAuthenticated
  ) {
    next('/projects')
  } else {
    next()
  }
})

export default router