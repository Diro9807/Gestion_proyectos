<template>
  <div>
    <h1>Mis Proyectos</h1>

    <input v-model="name" placeholder="Nuevo proyecto" />
    <button @click="createProject">Crear</button>

    <ul>
      <li v-for="p in projects" :key="p.id">
        {{ p.name }}
        <button @click="deleteProject(p.id)">X</button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
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
        body: JSON.stringify({ name: this.name }),
      })

      this.name = ''
      this.loadProjects()
    },

    async deleteProject(id) {
      await fetch(`http://127.0.0.1:8000/api/projects/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      })

      this.loadProjects()
    },
  },
}
</script>

<style>

</style>