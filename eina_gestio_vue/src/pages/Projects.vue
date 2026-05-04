<template>
  <div>
    <h1>Mis Proyectos</h1>

    <input v-model="name" placeholder="Nuevo proyecto" />
    <input v-model="description" placeholder="Descripción del proyecto" />
    <button @click="createProject">Crear</button>

    <ul>
      <li v-for="p in projects" :key="p.id_project">
        {{ p.name }}
        {{ p.description || 'Sin descripción' }}
        <button @click="deleteProject(p.id_project)">X</button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      description: '',
      projects: []      
    }
  },

  mounted() {
    this.loadProjects()
  },

  methods: {
    async loadProjects() {

      console.log('Cargando proyectos...')
      const res = await fetch('http://127.0.0.1:8000/api/projects', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      })

      this.projects = await res.json()
    },

    async createProject() {
      console.log('VALOR:', this.name)
      await fetch('http://127.0.0.1:8000/api/projects', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
        body: JSON.stringify({ name: this.name, description: this.description }),
      })

      this.name = ''
      this.description = ''
      this.loadProjects()
    },

   async deleteProject(id) {
        try {
          const response = await fetch(`http://127.0.0.1:8000/api/projects/${id}`, {
            method: 'DELETE',
            headers: {
              Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
              Accept: 'application/json',
            },
          });

          if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Error al eliminar el proyecto');
          }

          // opcional: actualizar UI
          console.log('Proyecto eliminado correctamente');
          this.loadProjects();
        } catch (error) {
          console.error('Error deleting project:', error.message);
        }
      },
  },
}
</script>

<style scoped>

</style>