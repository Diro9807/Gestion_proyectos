<template>
  <div class="Projects-content">
    <h1>Mis Proyectos</h1>

    <input v-model="name" placeholder="Nuevo proyecto" />
    <input v-model="description" placeholder="Descripción del proyecto" />
    <button @click="createProject">Crear</button>

    <ul>
      <li v-for="p in projects" :key="p.id_project">

      <!-- PROYECTO -->
      <div v-if="editingId !== p.id_project">

        <h2 class="project-title">{{ p.name }}</h2>

        <p class="project-description">
          {{ p.description || 'Sin descripción' }}
        </p>

        <!-- 🔥 TASKS -->
        <div class="tasks">

          <!-- CREAR TASK -->
          <div class="task-create">
            <input v-model="newTask[p.id_project]" placeholder="Nueva tarea" />
            <button @click="createTask(p.id_project)">+</button>
          </div>

          <!-- LISTA TASKS -->
          <ul class="task-list">
            <li v-for="t in tasks[p.id_project]" :key="t.id_task">

              <!-- NORMAL -->
              <div v-if="editingTaskId !== t.id_task">
                {{ t.name }}

                <button @click="startEditTask(t)">✏️</button>
                <button @click="deleteTask(t.id_task, p.id_project)">❌</button>
              </div>

              <!-- EDIT -->
              <div v-else>
                <input v-model="editTaskName" />
                <button @click="updateTask(t.id_task, p.id_project)">Guardar</button>
                <button @click="cancelEditTask">❌</button>
              </div>

            </li>
          </ul>

        </div>

        <!-- BOTONES PROYECTO -->
        <button @click="startEdit(p)">✏️</button>
        <button @click="deleteProject(p.id_project)">❌</button>

      </div>

      <!-- EDITAR PROYECTO -->
      <div v-else>
        <input v-model="editName" />
        <input v-model="editDescription" />

        <button @click="updateProject(p.id_project)">Guardar</button>
        <button @click="cancelEdit">❌</button>
      </div>

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
      projects: [],     
      
      editingId: null,
      editName: '',
      editDescription: ''
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

    startEdit(project) {
      this.editingId = project.id_project
      this.editName = project.name
      this.editDescription = project.description
    },

    cancelEdit() {
      this.editingId = null
      this.editName = ''
      this.editDescription = ''
    },

    async updateProject(id) {      
      try {
        await fetch(`http://127.0.0.1:8000/api/projects/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
          body: JSON.stringify({
            name: this.editName,
            description: this.editDescription,
            
          }),          
        })       

        this.editingId = null
        this.loadProjects()

      } catch (error) {
        console.error('Error actualizando proyecto:', error)
      }
    },
  },
}
</script>

<style scoped>
/* CONTENEDOR GENERAL */
.Projects-content {
  background-color: #6d97d6;
  min-height: 100vh;
}

div {
  padding: 30px;
  color: black;
}

/* TÍTULO */
h1 {
  margin-bottom: 20px;
  font-size: 32px;
}

/* INPUTS SUPERIORES */
input {
  padding: 10px;
  margin-right: 10px;
  border-radius: 6px;
  border: none;
  outline: none;
  background: #1e293b;
  color: white;
  transition: 0.2s;
}

input::placeholder {
  color: #94a3b8;
}

input:focus {
  background: #334155;
}

/* BOTONES */
button {
  padding: 10px 14px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: bold;
  transition: 0.2s;
}

/* BOTÓN CREAR */
button:first-of-type {
  background-color: #ef7e15;
  color: white;
}

button:first-of-type:hover {
  background-color: #0f4fcc;
}

/* GRID DE PROYECTOS */
ul {
  margin-top: 25px;
  list-style: none;
  padding: 20px;
  background-color: rgb(188, 188, 188);
  border-radius: 12px;
  display: grid;
  grid-template-columns: repeat(4, 350px); /* 🔥 columnas fijas */
  gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(300px, 350px));
  justify-content: center; /* 🔥 centra todo el grid */
}

/* CARD PROYECTO */
li {
  background: #D9D9D9;
  padding: 20px;
  height: 200px;
  width: 300px;
  border-radius: 12px;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: 0.25s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* HOVER CARD */
li:hover {
  background: #cbd5e1;
  transform: translateY(-4px) scale(1.02);
}

/* TEXTO Y BOTONES DENTRO */
li div {
  display: flex;
  flex-direction: column;
  gap: 8px;
  
}

/* TÍTULO PROYECTO*/
.project-title {
  position: absolute;
  top: 15px;
  left: 15px;
  font-size: 22px;
  font-family: Poppins;
  font-weight: bold;
  margin: 0;
}

/* DESCRIPCIÓN PROYECTO*/
.project-description {
  position: absolute;
  left: 15px;
  right: 15px;
  top: 30%;
  transform: translateY(-50%);
  font-size: 14px;
  color: #475569;
  opacity: 0;
  max-height: 0;
  overflow: hidden;
  transition: all 0.3s ease;
}

li:hover .project-description {
  opacity: 1;
  max-height: 45px;
}

/* OCULTA PARTE DE LA DESCRIPCIÓN */
.project-description {
  display: -webkit-box;
  
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* BOTONES DENTRO DE CARDS */
li button {
  position: absolute;
  background: transparent;
  border: none;
  cursor: pointer;
  font-weight: bold;
  font-size: 14px;
  padding: 5px;
  transition: 0.2s;  

}



/* BOTÓN ELIMINAR (arriba derecha) */
li button:nth-of-type(2) {
  top: 10px;
  right: 10px;
  color: #ef4444;
}

li button:nth-of-type(2):hover {
  transform: scale(1.2);
  color: #dc2626;
}

/* BOTÓN EDITAR (abajo derecha) */
li button:nth-of-type(1) {
  bottom: 10px;
  right: 10px;
  color: #f59e0b;
}

li button:nth-of-type(1):hover {
  transform: scale(1.2);
  color: #d97706;
}

/* EDITAR */
li button:nth-of-type(1) {
  background-color: none;
  color: white;
}

li button:nth-of-type(1):hover {
  background-color: none;
}

/* ELIMINAR */
li button:nth-of-type(2) {
  background-color: #d2a2a2;  
  color: white;
}

li button:nth-of-type(2):hover {
  background-color: #dc2626;
}

/* INPUTS EN EDICIÓN */
li input {
  margin-bottom: 5px;
  background: #0f172a;
}

/* BOTÓN GUARDAR */
li button:first-child {
  background-color: none;
}

li button:first-child:hover {
  background-color: none;
}



</style>