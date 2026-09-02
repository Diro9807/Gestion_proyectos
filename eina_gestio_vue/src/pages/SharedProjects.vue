<template>
  <div class="Projects-content">
    
    <div>

      <div class="projects-header">

        <h1>/Proyectos-Compartidos</h1>

      </div>

      <ul @mousemove="handleMouseMove" :style="projects.length ? backgroundStyle : {}" :class="{ 'empty-projects': projects.length === 0 }">
        <p v-if="projects.length === 0" class="no-projects">
          No te han compartido ningún proyecto
        </p>
        <li 
          v-for="p in projects" :key="p.id_project" @click="openSidebar(p)">

          <!-- NORMAL -->
          <div>
            <h2 class="project-title">{{ p.name }}</h2>

            <div class="total-tareas">
              {{ p.tasks_count }} tareas
            </div>

            <h3 class="project-users-title">Colaboradores</h3>
            <div class="project-users">
              <span
                v-for="(u, index) in p.users" :key="u.id_user" class="project-user-name">

                {{ u.name }}<span v-if="index !== p.users.length - 1">, </span>
              </span>
            </div>

            <p class="project-description">{{ formatDate(p.created_at) }}</p>
            

          </div>
          
        </li>
      </ul>

    </div>
    

    <!-- SIDEBAR OVERLAY -->
      <div v-if="sidebarProject" class="sidebar-overlay" @click="closeSidebar">

        <div class="project-sidebar" @click.stop>

          <button class="close-sidebar" @click="closeSidebar">❌</button>

          <input
            v-model="sidebarProject.name" disabled
            class="sidebar-title-input"
            
          />

          <textarea
            v-model="sidebarProject.description" disabled
            class="sidebar-description-input"
            placeholder="Añadir descripción..."
            
          ></textarea>

          <div class="sidebar-info">

            <p><strong>Fecha creación:</strong></p>

            <p> {{ formatDate(sidebarProject.created_at) }}</p>

          </div>
          <div class="sidebar-users">
            <h3>Usuarios</h3>

            <div v-if="sidebarProject.users?.length" class="users-list">
                <div
                    v-for="u in sidebarProject.users"
                    :key="u.id_user"
                    class="user-card"
                >
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
      
      
  </div>  
  <Popup
    :show="showPopup"
    :message="popupMessage"
    :type="popupType"
  />

  
  
</template>

<script>
import { API_URL } from '@/config'
import Popup from '@/components/ui/Popup.vue'




export default {
  data() {    
    return {        
      projects: [],
      sidebarProject: null,  

      popupMessage: '',
      popupType: 'success',
      showPopup: false,      
      

      mouseX: '50%',
      mouseY: '50%',

    }    
  },

  components:{
    Popup
    
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
    this.loadProjects()
    
  },

  

  methods: {
    /////////////////////////////////////////////////////////////////////////////////////
    async loadProjects() {
      const token = localStorage.getItem('auth_token')

      if (!token) {
        this.$router.push('/login')
        return
      }

      try {
        const response = await fetch(`${API_URL}/shared-projects`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })

        if (!response.ok) {
          throw new Error('No se pudieron cargar los proyectos compartidos')
        }

        this.projects = await response.json()
      } catch (error) {
        console.error('Error cargando proyectos compartidos:', error)

        this.showPopupMessage(
          'No se pudieron cargar los proyectos compartidos',
          'error'
        )
      }
    },



//////////////////////////////////////////////////////////////////////////////////////////// 
    openSidebar(project) {
      this.sidebarProject = project
    },

    closeSidebar() {
      this.sidebarProject = null
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
///////////////////////////////////////////////////////////////////////////////////
    handleMouseMove(e) {

      this.mouseX = `${(e.clientX / window.innerWidth) * 100}%`
      this.mouseY = `${(e.clientY / window.innerHeight) * 100}%`
    },

    showPopupMessage(message, type = "error") {

      this.popupMessage = message
      this.popupType = type
      this.showPopup = true

      setTimeout(() => {

          this.showPopup = false

      },3000)
    }


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




/* GRID DE PROYECTOS */
ul {
  margin-top: 35px;
  list-style: none;
  padding: 20px;
  background-color: #bcbcbc;
  box-shadow: 10px 30px 15px rgba(0,0,0,0.25);
  border-radius: 12px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 350px));
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
  margin-left: 120px;
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





/* ================================= */
/* RESPONSIVE MOVIL */
/* ================================= */

@media (max-width: 768px) {

  .Projects-content {
    padding: 15px;
  }

  .projects-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
    height: auto;
  }

  .projects-header h1 {
    font-size: 28px;
    line-height: 1.2;
    margin: 0;
  }

  ul {
    display: flex;
    flex-direction: column;
    padding: 15px;
    gap: 15px;
  }

  li {
    width: 100%;
    max-width: none;
    margin: 0;
    height: auto;
    min-height: 180px;
  }

  .project-title {
    font-size: 20px;
  }

  .project-sidebar {
    width: 100%;
    height: calc(100vh - 70px);
    margin: 70px 0 0 0;
    border-radius: 12px 12px 0 0;
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


  .open-project-btn {
    position: static;
    width: 100%;
    margin-top: 20px;
  }
}


</style>