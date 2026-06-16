<template>
  <div class="Projects-content">
    
    <div v-if="!selectedProject">

      <div class="projects-header">

        <h1>/MY-Projects</h1>

        <button
          class="create-project-btn"
          @click="openCreateModal"
        >
          + Nuevo Proyecto
        </button>

      </div>

      <ul @mousemove="handleMouseMove" :style="projects.length ? backgroundStyle : {}" :class="{ 'empty-projects': projects.length === 0 }">
        <p v-if="projects.length === 0" class="no-projects">
          No tienes ningún proyecto
        </p>
        <li 
          v-for="p in projects" :key="p.id_project" @click="openSidebar(p)">

          <!-- NORMAL -->
          <div>
            <h2 class="project-title">{{ p.name }}</h2>

            <div class="total-tareas">
              {{ tasks[p.id_project]?.length || 0 }} tareas
            </div>

            <h3 class="project-users-title">Colaboradores</h3>
            <div class="project-users">
              <span
                v-for="(u, index) in p.users" :key="u.id_user" class="project-user-name">

                {{ u.name }}<span v-if="index !== p.users.length - 1">, </span>
              </span>
            </div>

            <p class="project-description">{{ formatDate(p.created_at) }}</p>
            <button @click.stop="deleteProject(p.id_project)">❌</button>

          </div>
          
        </li>
      </ul>

    </div>

    <!-- VISTA PROYECTO -->
    <div v-else>

      <button @click="closeProject">⬅ Volver</button>

      <h1>{{ selectedProject.name }}</h1>

      <!-- CREAR TASK -->
      <input 
        v-model="newTask[selectedProject.id_project]" 
        placeholder="Nueva tarea" 
      />
      <button @click="createTask(selectedProject.id_project)">+</button>

      <!-- LISTA TASKS -->
      <ul>
        <li v-for="t in tasks[selectedProject.id_project]" :key="t.id_task">

          <!-- NORMAL -->
          <div v-if="editingTaskId !== t.id_task">
            {{ t.name }}

            <button @click="startEditTask(t)">✏️</button>
            <button @click="deleteTask(t.id_task, selectedProject.id_project)">❌</button>
          </div>

          <!-- EDIT -->
          <div v-else>
            <input v-model="editTaskName" />

            <button @click="updateTask(t.id_task, selectedProject.id_project)">
              Guardar
            </button>
            <button @click="cancelEditTask">❌</button>
          </div>

        </li>
      </ul>

    </div>

    <!-- SIDEBAR OVERLAY -->
      <div v-if="sidebarProject" class="sidebar-overlay" @click="closeSidebar">

        <div class="project-sidebar" @click.stop>

          <button class="close-sidebar" @click="closeSidebar">❌</button>

          <input
            v-model="sidebarProject.name"
            class="sidebar-title-input"
            @input="debouncedSaveProject"
            maxlength="20"
          />

          <textarea
            v-model="sidebarProject.description"
            class="sidebar-description-input"
            placeholder="Añadir descripción..."
            @input="debouncedSaveProject"
            maxlength="500"
          ></textarea>

          <div class="sidebar-info">

            <p><strong>Fecha creación:</strong></p>

            <p> {{ formatDate(sidebarProject.created_at) }}</p>

          </div>
          <div class="sidebar-users">
            <h3>Usuarios</h3>
            <div class="add-user-section">
            <select v-model="selectedUserId">

              <option disabled value="">
                Seleccionar usuario
              </option>

              <option
                v-for="u in users"
                :key="u.id_user"
                :value="u.id_user"
              >
                {{ u.name }}
              </option>
            </select>

            <button @click="addUserToProject">
              Añadir
            </button>
          </div>
            <div v-if="sidebarProject.users?.length" class="users-list">
              <div v-for="u in sidebarProject.users" :key="u.id_user" class="user-card">
                <div class="user-avatar">
                  {{ u.name.charAt(0).toUpperCase() }}
                </div>

                <div class="user-data">

                  <p class="user-name">
                    {{ u.name }}
                  </p>

                  <div class="user-role-badge" :class="u.pivot?.role">
                    {{ u.pivot?.role }}
                  </div>

                  

                  <div class="remove-user">
                    <button class="remove-user-btn" @click="removeUserFromProject(u.id_user)">❌</button>
                  </div>

                </div>
                

              </div>
            </div>
            <p v-else>
              Sin usuarios asignados
            </p>
          </div>

          <button
            class="open-project-btn"
            @click="$router.push(`/projects/${sidebarProject.id_project}`)"
          >
            Abrir proyecto
          </button>

        </div>

      </div>
      <!-- CREATE PROJECT MODAL -->
      <div
        v-if="showCreateModal"
        class="modal-overlay"
        @click="closeCreateModal"
      >
        <div class="create-modal" @click.stop>

          <button class="close-modal-btn" @click="closeCreateModal">❌</button>

          <h2>Creando Proyecto...</h2>

          <input maxlength="20" v-model="name"placeholder="Nombre del proyecto" />

          <textarea v-model="description" placeholder="Descripción del proyecto" class="create-description"></textarea>

          <div class="create-users-section">

            <h3>Usuarios del proyecto</h3>

            <select v-model="selectedUserId">
              <option disabled value="">
                Seleccionar usuario
              </option>

              <option
                v-for="u in users.filter(
                  user => Number(user.id_user) !== Number(currentUser?.id_user)
                )"
                :key="u.id_user"
                :value="u.id_user"
              >
                {{ u.name }}
              </option>          

            </select>
            

            <button @click="addUserToNewProject">
              Añadir
            </button>

          </div>

          <div class="selected-users">
            <span
              v-for="u in newProjectUsers"
              :key="u.id_user"
              class="selected-user">
              {{ u.name }}
                <span
                  v-if="u.fixed"
                  class="owner-badge"
                >
                  OWNER
                </span>

              <button
                v-if="!u.fixed"
                @click="removeUserFromNewProject(u.id_user)"
              >
                ❌
              </button>
            </span>
          </div>

          <button class="confirm-create-btn" @click="createProject">
            Crear Proyecto
          </button>

        </div>
      </div>
    <!-- ERROR POPUP -->
    <div v-if="showError" class="error-popup">
      {{ errorMessage }}
    </div>
  </div>
  
</template>

<script>
import { API_URL } from '@/config'
export default {
  data() {    
    return {
      name: '',
      description: '',
      projects: [], 

      currentUser: null,
      tasks: {},
      newTask: {},
      editingTaskId: null,
      editTaskName: '',

      selectedProject: null,
      sidebarProject: null,
      saveTimeout: null,

      users: [],      
      selectedUserId: '',

      showCreateModal: false,
      newProjectUsers: [],

      errorMessage: '',
      showError: false,

      mouseX: '50%',
      mouseY: '50%',
    }    
  },

  computed: {

    backgroundStyle() {

      return {
        background: `
          radial-gradient(
            circle 1500px at ${this.mouseX} ${this.mouseY},
            #C8C8C8,
            #A1A1A1,
            transparent 100%
          ),
          linear-gradient(
            135deg,
            #bcbcbc,
            #b2b2b2,
            #C1C1C1
          )
        `
      }
    }
  },

  mounted() {

    const storedUser = localStorage.getItem('auth_user')

    if (storedUser) {
      this.currentUser = JSON.parse(storedUser)
    }

    console.log('CURRENT USER:', this.currentUser)

    this.loadProjects()
    this.loadUsers()
  },

  

  methods: {
    openProject(project) {
      this.selectedProject = project
      this.loadTasks(project.id_project)
    },
    closeProject() {
      this.selectedProject = null
    },
    openCreateModal() {

      this.showCreateModal = true

      const currentUser = this.currentUser

      this.newProjectUsers = [
        {
          id_user: currentUser.id_user,
          name: currentUser.name || currentUser.username,
          fixed: true,
          role: 'owner'
        }
      ]
    },

    //////////////////////////////////////////////////////////////////
    async loadProjects() {

      console.log('Cargando proyectos...')
      const token = localStorage.getItem('auth_token')
      if (!token) {
        console.log("No hay auth token")
        console.error('No auth token found')
        this.$router.push('/login')
        return
      }
      const res = await fetch(`${API_URL}/owned-projects`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      })

      this.projects = await res.json()
      
        this.projects.forEach(p => {
          this.loadTasks(p.id_project)
        })
    },
////////////////////////////////////////////////////////////////////////////////////////////
    async createProject() {

      try {

        console.log('CREANDO PROYECTO')

        const res = await fetch( `${API_URL}/projects`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Authorization:
                `Bearer ${localStorage.getItem('auth_token')}`,
            },

            body: JSON.stringify({
              name: this.name,
              description: this.description,
            }),
           
          }
        )
         console.log('BODY ENVIADO:', {
              name: this.name,
              description: this.description,
            })

        if (!res.ok) {
          const errorText = await res.text()
          console.error('ERROR BACKEND:', errorText)
          throw new Error('Error creando proyecto')
        }

        const project = await res.json();

        console.log(project)
        console.log('USUARIOS AÑADIDOS:', this.newProjectUsers)

        for (const user of this.newProjectUsers.filter(
              u => !u.fixed
            )) {

          await fetch(`${API_URL}/projects/${project.id_project}/users`,
            {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                Authorization:
                  `Bearer ${localStorage.getItem('auth_token')}`,
              },

              body: JSON.stringify({
                user_id: user.id_user,
                role: user.fixed ? 'owner' : 'member',
              }),
            }
          )
        }

        this.closeCreateModal()
        this.loadProjects()

      } catch (error) {
        console.error(error)
      }
    },
////////////////////////////////////////////////////////////////////////////////////////////
    async deleteProject(id) {
      const confirmed = confirm(
          '¿Seguro que quieres eliminar este proyecto?'
        )

        if (!confirmed) return

      try {
        const response = await fetch(`${API_URL}/projects/${id}`, {
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

    

////////////////////////////////////////////////////////////////////////////////////////////  
    async loadTasks(projectId) {

      console.log('Cargando tareas...')
        const res = await fetch(`${API_URL}/projects/${projectId}/tasks`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        })

      this.tasks[projectId] = await res.json()
    },
////////////////////////////////////////////////////////////////////////////////////////////
    async createTask(projectId) {
      console.log('VALOR TASK:', this.newTask[projectId])

      await fetch(`${API_URL}/tasks`,{
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
        body: JSON.stringify({
          name: this.newTask[projectId],
          project_task_id: projectId
        }),
      })

      this.newTask[projectId] = ''
      this.loadTasks(projectId)
    },
////////////////////////////////////////////////////////////////////////////////////////////    
    async deleteTask(id, projectId) {
      try {
        const response = await fetch(`${API_URL}/tasks/${id}`, {
          method: 'DELETE',
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
            Accept: 'application/json',
          },
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Error al eliminar la tarea');
        }

        console.log('Tarea eliminada correctamente');
        this.loadTasks(projectId);

      } catch (error) {
        console.error('Error deleting task:', error.message);
      }
    },
////////////////////////////////////////////////////////////////////////////////////////////  
    startEditTask(task) {
      this.editingTaskId = task.id_task
      this.editTaskName = task.name
    },
    cancelEditTask() {
      this.editingTaskId = null
      this.editTaskName = ''
    },
////////////////////////////////////////////////////////////////////////////////////////////  
    async updateTask(id, projectId) {      
      try {
        await fetch(`${API_URL}/tasks/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
          body: JSON.stringify({
            name: this.editTaskName,
          }),          
        })       

        this.editingTaskId = null
        this.loadTasks(projectId)

      } catch (error) {
        console.error('Error actualizando tarea:', error)
      }
    },
////////////////////////////////////SIDEBAR//////////////////////////////////////////////////////// 
    openSidebar(project) {
      this.sidebarProject = project
    },

    closeSidebar() {
      this.sidebarProject = null
    },
    debouncedSaveProject() {
    clearTimeout(this.saveTimeout)
    this.saveTimeout = setTimeout(() => {
      this.autoSaveProject()
    }, 700)
},
///////////////////////////////////////////////////////////////////////
    async autoSaveProject() {
      try {
        await fetch(`${API_URL}/projects/${this.sidebarProject.id_project}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },

          body: JSON.stringify({
            name: this.sidebarProject.name,
            description: this.sidebarProject.description,
          }),
        })

        const index = this.projects.findIndex(
          p => p.id_project === this.sidebarProject.id_project
        )

        if (index !== -1) {
          this.projects[index] = {
            ...this.sidebarProject
          }
        }

      } catch (error) {
        console.error('Error autoguardando proyecto:', error)
      }
    },
/////////////////////////////////////////////////////////////////////////////
      async loadUsers() {
        const res = await fetch(`${API_URL}/users`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },

      })
      this.users = await res.json()
    },
/////////////////////////////////////////////////////////////////////////////
      closeCreateModal() {
        this.showCreateModal = false
        this.name = ''
        this.description = ''
        this.newProjectUsers = []
        this.selectedUserId = ''
      },

/////////////////////////////////////////////////////////////////////
      addUserToNewProject() {

        if (!this.selectedUserId) return

        const user = this.users.find(
          u => Number(u.id_user) === Number(this.selectedUserId)
        )

        // SI NO EXISTE EL USER
        if (!user) {
          console.error('Usuario no encontrado')
          return
        }

        const exists = this.newProjectUsers.some(
          u => u.id_user === user.id_user
        )

        const currentUser = JSON.parse(localStorage.getItem('auth_user') || 'null')

        if (currentUser?.id_user === user.id_user) {
          return
        }

        if (!exists) {
          this.newProjectUsers.push(user)
        }

        this.selectedUserId = ''
      },
//////////////////////////////////////////////////////////////////////////////
      removeUserFromNewProject(userId) {
        this.newProjectUsers =
          this.newProjectUsers.filter(
            u => u.id_user !== userId
          )
      },

/////////////////////////////////////////////////////////////////////////////
      async addUserToProject() {

        if (!this.selectedUserId) return

        try {

          const response = await fetch(`${API_URL}/projects/${this.sidebarProject.id_project}/users`,
            {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
              },

              body: JSON.stringify({
                user_id: this.selectedUserId,
                role: 'member'
              }),
            }
          )

          const data = await response.json()

          console.log(data)

          if (!response.ok) {
            this.showErrorPopup(data.message)
            return
          }

          // RECARGAR PROYECTOS
          await this.loadProjects()

          // RECARGAR SIDEBAR
          const updatedProject = this.projects.find(
            p => p.id_project === this.sidebarProject.id_project
          )

          if (updatedProject) {
            this.sidebarProject = updatedProject
          }

          // RESET SELECT
          this.selectedUserId = ''

        } catch (error) {

          console.error(error)

          this.showErrorPopup('Error inesperado')
        }
      },
///////////////////////////////////////////////////////////////
    async removeUserFromProject(userId) {

      try {

        const response = await fetch(`${API_URL}/projects/${this.sidebarProject.id_project}/users/${userId}`,
          {
            method: 'DELETE',
            headers: {
              Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
            },
          }
        )

        if (!response.ok) {

          const error = await response.json()

          this.showErrorPopup(error.message)

          return
        }

        this.sidebarProject.users =
          this.sidebarProject.users.filter(
            u => u.id_user !== userId
          )

      } catch (error) {

        this.showErrorPopup('Error inesperado')
      }
    },  
/////////////////////////////////////////////////////////////////////////////
    async changeRole(userId, event) {
      await fetch(`${API_URL}/projects/${this.sidebarProject.id_project}/users/${userId}/role`,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },

          body: JSON.stringify({
            role: event.target.value
          }),
        }
      )

      this.loadProjects()
    },
////////////////////////////////////////////////////////////////////////////
    formatDate(date) {

      if (!date) return 'Sin fecha'

      return new Date(date).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
      })
    },
////////////////////////////////////////////////////////////////////////////////////
    showErrorPopup(message) {

      this.errorMessage = message
      this.showError = true

      setTimeout(() => {
        this.showError = false
      }, 3000)
    },

    handleMouseMove(e) {

      this.mouseX = `${(e.clientX / window.innerWidth) * 100}%`
      this.mouseY = `${(e.clientY / window.innerHeight) * 100}%`
    },


  },
}
</script>

<style scoped>
/* CONTENEDOR GENERAL */
.Projects-content {
  background: linear-gradient(180deg, #6d97d6, #07173f);
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
  background: white;
  color: black;
  transition: 0.2s;
}

input::placeholder {
  color: #94a3b8;
}

input:focus {
  background: white;
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

.projects-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 0px;
  margin-bottom: 20px;
  height: 80px;
}

.projects-header h1{
  font-family: Poppins;
  color: white;
  font-size: 50px;
}

/* BOTÓN CREAR */
.create-project-btn {
  background: #FE9F5B;
  font-family: Poppins;
  color: white !important; 
  padding: 12px 18px;
  border-radius: 10px;
  
  
}

.create-project-btn:hover {
  background: #f1873c;
}



/* GRID DE PROYECTOS */
ul {
  margin-top: 35px;
  list-style: none;
  padding: 20px;

  background-color: #bcbcbc;
  box-shadow: 10px 30px 15px rgba(0,0,0,0.25);
  border-radius: 12px;

  display: grid;

  grid-template-columns: repeat(auto-fill, 320px);

  gap: 25px;

  justify-content: start;
}

/* CUANDO NO HAY PROYECTOS */

ul.empty-projects {
  background: transparent;
  box-shadow: none;
  padding: 0;
  min-height: auto;
}

.no-projects {
  margin-top: 40px;
  text-align: center;
  font-family: Poppins;
  font-size: 24px;
  font-weight: 600;
  color: white;
  opacity: 0.8;
}

/* CARD PROYECTO */
li {
  background: #D9D9D9;
  margin-top: 20px;
  margin-bottom: 20px;
  margin-left: 0;
  width: 100%;
  max-width: 330px;
  padding: 20px 25px;
  height: 200px;
  width: 330px;
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
  background: #6d97d6;
  
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

.total-tareas {
  position: absolute;
  top: 40px;
  left: -8px;
  font-family: Poppins;
  font-size: 14px;
  
  color: #64748b;
}

li:hover .total-tareas {
  color: white;
}

.project-users-title{
  position: absolute;
  top: 98px;
  left: 23px;
  right: 15px;
  font-family: Poppins;
  font-size: 14px;
}

/* USERS EN CARD */
.project-users {
  position: absolute;
  top: 100px;
  left: -8px;
  right: 15px;
  font-size: 13px;
  font-family: Poppins;
  color: #475569;
  line-height: 1.5;
  display: block !important;
  white-space: normal;
  word-break: break-word;
}


/* FECHA PROYECTO */
.project-description {
  position: absolute;
  top: 150px;
  left: 15px;
  font-size: 13px;
  font-family: Poppins;
  color: #64748b;
  opacity: 0;
  transition: opacity 0.25s ease;
}



li:hover .project-description, li:hover .project-users, li:hover .project-users-title, li:hover .project-title   {
  opacity: 1;
  max-height: 45px;
  color: white;
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
  top: 10px;
  right: 10px;
  color: #ef4444;
  background-color: #d2a2a2;
}

li button:hover {
  transform: scale(1.2);
  color: #dc2626;
  background-color: #dc2626;
}


/* INPUTS EN EDICIÓN */
li input {
  margin-bottom: 5px;
  background: white;
}

/* BOTÓN GUARDAR */
li button:first-child {
  background-color: none;
}

li button:first-child:hover {
  background-color: none;
}

/* ========================= */
/* OVERLAY */
/* ========================= */

.sidebar-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.35);
  z-index: 999;
  display: flex;
  justify-content: flex-end;  
}

/* ========================= */
/* SIDEBAR */
/* ========================= */

.project-sidebar {
  width: 700px;
  border-radius: 12px;
  height: 85vh;
  max-height: 100vh;
  margin-top: 70px;
  margin-right: 32px;
  background: #d9d9d9;
  padding: 35px;
  position: relative;
  box-shadow: -10px 0 30px rgba(0,0,0,0.25);
  animation: slideIn 0.25s ease;
  overflow-y: auto;
  overflow-x: hidden;
}

.project-sidebar::-webkit-scrollbar {
  width: 8px;
}

.project-sidebar::-webkit-scrollbar-track {
  background: transparent;
}

.project-sidebar::-webkit-scrollbar-thumb {
  background: rgba(100, 116, 139, 0.5);
  border-radius: 999px;
}

.project-sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(71, 85, 105, 0.8);
}

/* BOTÓN CERRAR */

.close-sidebar {
  position: absolute;
  top: 30px;
  right: 20px;
  background: transparent;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #64748b;
  border-radius: 12px;
}

.close-sidebar:hover {
  color: #ef4444;
}

/* TITULO */

.project-sidebar h2 {
  font-size: 34px;
  margin-bottom: 25px;
  color: #0f172a;
}

/* DESCRIPCIÓN */

.sidebar-description {
  color: #475569;
  line-height: 1.7;
  margin-bottom: 35px;
  white-space: pre-wrap;
  font-family: Poppins;
}

/* INFO */

.sidebar-info {
  background: #e2e8f0;
  padding: 0px 20px;
  border-radius: 12px;
  margin-bottom: 20px;
  gap: 20px;
  font-family: Poppins;
  display: flex;
  
}

/* BOTÓN ABRIR */

.open-project-btn {
  width: 30%;
  background: #ef7e15;
  color: white;
  padding: 14px;
  border-radius: 12px;
  font-size: 16px;
  font-weight: bold;
  position: absolute;
  top: 85%;
  left: 35%;
  
}

.open-project-btn:hover {
  background: #d96d09;
}

/* ANIMACIÓN */

@keyframes slideIn {
  from {
    transform: translateX(100%);
  }
  to {
    transform: translateX(0);
  }
}
/* INPUT TITULO SIDEBAR */

.sidebar-title-input {
  width: 90%;
  font-size: 34px;
  font-weight: bold;
  border: none;
  outline: none;
  background: transparent;
  color: #0f172a;
  margin-bottom: 25px;
}

/* TEXTAREA DESCRIPCIÓN */

.sidebar-description-input {
  width: 100%;
  min-height: 120px;
  resize: vertical;
  border: none;
  outline: none;
  background: #e2e8f0;
  border-radius: 12px;
  padding: 18px;
  font-size: 15px;
  line-height: 1.7;
  color: #334155;
  margin-bottom: 30px;
}

.sidebar-description-input:focus {
  background: #dbeafe;
}

/* ========================= */
/* USERS */
/* ========================= */

.sidebar-users {
  margin-top: 0px;
  margin-left: -25px;
  padding-top: 0px;
  font-family: Poppins;
}

.sidebar-users h3{
  font-family: Poppins;
  margin: 0px;
}

.sidebar-users h3 {
  margin-bottom: 20px;
  color: #0f172a;
  font-size: 22px;
}

.users-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  width: 620px;
  padding: 5px 0;
}

/* CARD USER */

.user-card {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #e2e8f0;
  padding: 30px 14px;
  border-radius: 14px;
  height: 15px;
  min-width: 195px;
  max-width: 50px;
  position: relative;
  transition: 0.2s;
  text-align: center;
}
.user-card:hover {
  transform: translateY(-2px);
  background: #dbeafe;
}

/* AVATAR */

.user-avatar {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-family: Poppins;
  font-size: 18px; 
  padding: 8px; 
}

/* USER ROL */

.user-role-badge {
  margin-top: 1px;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 8px;
  font-weight: bold;
  width: fit-content;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: Poppins;
}

.user-role-badge.owner {
  background: #facc15;
  color: #713f12;
}

.user-role-badge.admin {
  background: #60a5fa;
  color: #1e3a8a;
}

.user-role-badge.member {
  background: #d1d5db;
  color: #374151;
}

/* INFO */

.user-data {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 0px;  
}

.user-name {
  margin: 0;
  font-weight: bold;
  color: #0f172a;
}

.user-role {
  font-size: 13px;
  color: #64748b;
}

/* ADD USER */

.add-user-section {
  display: flex;  
  margin-bottom: 20px;
  padding: 0px 30px 0px 0px;
  margin-right: -60px;
  gap: 15px;
  
}

.add-user-section select {
  flex: 1;
  padding: 10px;
  border-radius: 10px;
  border: none;
  background: #e2e8f0;
}

.add-user-section button {
  background: #3b82f6;
  color: white;
  padding: 10px 16px;
  border-radius: 10px;
}

.add-user-section button:hover {
  background: #2563eb;
}

.remove-user{
  position:absolute;
  top: -20%;
  left: 65%;
}

.remove-user-btn {
  background: transparent !important;
  border: none;
  cursor: pointer;
  color: #ef4444;
  font-size: 12px;
  padding: 0;
  margin-left: auto;
  transition: 0.2s;
}

.remove-user-btn:hover {
  transform: scale(1.2);
  color: #dc2626;
}

/* ========================= */
/* CREAR PROYECTO */
/* ========================= */



.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.65);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.create-modal {
  width: 550px;
  background: #d9d9d9;
  border-radius: 12px;
  padding: 35px;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.create-modal h2, h3{
  font-family: Poppins;
}


.create-modal input{
  font-size: 30px;
  padding: 5px;
  margin-right: 0px;
  margin-top: 5px;
  font-family: Poppins;
}

.close-modal-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  background-color: rgb(190, 162, 162);
}

.create-description {
  min-height: 120px;
  resize: vertical;
  border: none;
  border-radius: 12px;
  padding: 15px;
  font-family: Poppins;
}

.create-users-section {
  display: flex;
  gap: 15px;
  padding: 0px;
  align-items: center;
  flex-wrap: wrap;
}

.create-users-section select {
  flex: 1;
  padding: 10px;
  border-radius: 10px;
}

.create-users-section button{
  background-color: #FE9F5B;
  color: white;
  font-family: Poppins;
  border-radius: 12px;
}

.selected-users {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  padding: 0px;
  font-family: Poppins;
  font-weight: bold;
}

.selected-user {
  background: #c9c9c9;
  padding-left: 15px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
}

.selected-user button {
  background: transparent;
  border: none;
  cursor: pointer;
}

.confirm-create-btn {
  background: #16a34a;
  color: white;
  margin: 10px 150px;
  font-family: Poppins;
}

.confirm-create-btn:hover {
  background: #15803d;
}

.error-popup {
  position: fixed;

  bottom: 30px;
  right: 30px;

  background: #ef4444;
  color: white;

  padding: 14px 20px;

  border-radius: 12px;

  font-family: Poppins;
  font-weight: 600;

  box-shadow: 0 8px 25px rgba(0,0,0,0.25);

  z-index: 999999;

  animation: popupFade 0.3s ease;
}

@keyframes popupFade {

  from {
    opacity: 0;
    transform: translateY(15px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.owner-badge {
  background: #facc15;
  color: #713f12;
  padding: 3px 8px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: bold;
}


/* ===================================== */
/* RESPONSIVE MOVIL */
/* ===================================== */

@media (max-width: 768px) {

  .Projects-content {
    padding: 10px;
  }

  .projects-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
    height: auto;
  }

  .projects-header h1 {
    font-size: 40px;
    line-height: 1.1;
  }

  .create-project-btn {
    width: 100%;
  }

  ul {
    padding: 15px;
    grid-template-columns: 1fr;
    gap: 15px;
    justify-content: center;
  }

  li {
    width: 100%;
    max-width: 100%;
    margin-left: 0px;
    margin-top: 0;
    margin-bottom: 0;
    height: 200px;
    box-sizing: border-box;
  }

  

  .project-sidebar {
    width: 100%;
    height: calc(100vh - 80px);
    margin-top: 80px;
    margin-right: 0;
    border-radius: 20px 20px 0 0;
    padding: 20px;
  }

  .close-sidebar {
    top: 15px;
    right: 15px;
  }

  .sidebar-info {
    flex-direction: column;
    gap: 10px;
  }

  .sidebar-users {
    padding: 20px 0px 10px 25px;
  }


  .users-list {
    width: 100%;
  }

  .user-card {
    width: 100%;
    max-width: 100%;
    min-width: unset;
  }

  .remove-user{
    position:absolute;
    top: -20%;
    left: 80%;
  }


  .add-user-section {
    flex-direction: column;
    margin-right: 0;
    padding-right: 0;
  }

  .add-user-section button {
    width: 100%;
  }

  .open-project-btn {
    position: static;
    width: 100%;
    margin-top: 20px;
  }

  .create-modal {
    width: 95%;
    padding: 20px;
  }

  .confirm-create-btn {
    margin: 0;
    width: 100%;
  }

  .create-users-section {
    flex-direction: column;
    align-items: stretch;
  }

  .create-users-section button {
    width: 100%;
  }

}

</style>