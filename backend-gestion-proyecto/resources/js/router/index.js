import { createRouter, createWebHistory } from 'vue-router'

// IMPORTAMOS COMPONENTES
import Projects from '../components/Projects.vue'
import ProjectDetail from '../components/ProjectDetail.vue'

// DEFINIMOS RUTAS
const routes = [
  {
    path: '/projects',
    component: Projects
  },
  {
    path: '/projects/:id',
    component: ProjectDetail,
    props: true
  }
]

// CREAR ROUTER
const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
