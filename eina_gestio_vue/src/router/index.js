import { createRouter, createWebHistory } from 'vue-router'

// Importa tus páginas
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import Projects from '../pages/Projects.vue'
import Profile from '../pages/Profile.vue'
import AdminPanel from '../pages/AdminPanel.vue'

const routes = [

  { 
    path: '/', 
    redirect: '/login' 
  },

  { 
    path: '/login', 
    component: Login 
  },

  { 
    path: '/register', 
    component: Register 
  },

  { 
    path: '/dashboard', 
    component: Dashboard,
    meta: { requiresAuth: true }
  },

  { 
    path: '/projects', 
    component: Projects,
    meta: { requiresAuth: true }
  },

  { 
    path: '/profile', 
    component: Profile,
    meta: { requiresAuth: true }
  },

  {
    path: '/projects/:id',
    component: () => import('../pages/ProjectDetail.vue'),
    props: true,
    meta: { requiresAuth: true }
  },

  {
    path: '/shared-projects',
    name: 'SharedProjects',
    component: () => import('../pages/SharedProjects.vue'),
    meta: { requiresAuth: true }
  },

  {
    path: '/admin',
    component: AdminPanel,
    meta: { 
      requiresAuth: true,
      requiresAdmin: true
    }
  }

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Cuando loggeas esto te redirige al Dashboard
router.beforeEach((to, from, next) => {

  const token = localStorage.getItem('auth_token')
  const userData = localStorage.getItem('auth_user')

  const isAuthenticated = !!token

  let user = null

  if (userData) {
    try {
      user = JSON.parse(userData)
    } catch {
      user = null
    }
  }

  // Rutas privadas
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
    return
  }

  // Si ya está logueado, no puede volver a login/register
  if (
    (to.path === '/login' || to.path === '/register') &&
    isAuthenticated
  ) {
    next('/projects')
    return
  }

  // Rutas exclusivas para administradores
  if (to.meta.requiresAdmin && user?.roles_id !== 1) {
    next('/projects')
    return
  }

  next()
})

export default router