<!-- PAGINA TAREAS -->
 
<template>
  <div class="project-detail">

    <button class="back-btn" @click="$router.push('/projects')">
      <span>⬅</span>
      Volver
    </button>

    <div class="project-header">

      <h1 class="project-title">
        /{{ projectName || `Proyecto ${projectId}` }}
      </h1>

      <div class="tasks-topbar">
        
        <button
          class="create-task-btn"
          @click="showCreateTaskModal = true"
        >
          + Nueva tarea
        </button>
      </div>

    </div>

    
    <div class="complet-grid">
      <div class="table-wrapper">

        <!-- LISTA TASKS -->
        <div class="tasks-header">

          <div class="col-name">Nombre</div>

          <div class="col-description">Descripción</div>

          <div class="col-user">Usuario</div>

          <div class="col-date">Inicio</div>

          <div class="col-date">Fin</div>

          <div class="col-date">Límite</div>

          <div class="col-status">Estado</div>

        </div>
          <ul>
            <draggable
              v-model="tasks"
              item-key="id_task"
              @end="saveTaskOrder"
              animation="200"
              ghost-class="sortable-ghost"
              drag-class="sortable-drag"
              chosen-class="sortable-chosen"
            >
              <template #item="{ element: t }">

                <li :class="t.status">

                  <div class="task-content">

                    <!-- NOMBRE -->
                    <input
                      v-model="t.name"
                      class="task-title-input"
                      @change="updateTask(t)"
                    />

                    <!-- DESCRIPCIÓN -->
                    <textarea
                      v-model="t.description"
                      placeholder="Descripción"
                      class="task-description"
                      @change="updateTask(t)"
                    ></textarea>

                    <!-- USER -->
                    <select
                      v-model="t.user_id"
                      @change="updateTask(t)"
                    >
                      <option :value="null">Sin asignar</option>

                      <option
                        v-for="u in users"
                        :key="u.id_user"
                        :value="u.id_user"
                      >
                        {{ u.name }}
                      </option>
                    </select>

                    <!-- FECHAS -->
                    <input
                      type="date"
                      v-model="t.start_date"
                      class="task-date-input"
                      @change="updateTask(t)"
                      :min="minDate"
                    />

                    <input
                      type="date"
                      v-model="t.end_date"
                      class="task-date-input"
                      @change="updateTask(t)"
                      :min="minDate"
                    />

                    <input
                      type="date"
                      v-model="t.due_date"
                      class="task-date-input"
                      @change="updateTask(t)"
                      :min="minDate"
                    />

                    <!-- ESTADO -->
                    <select
                      v-model="t.status"
                      @change="updateTask(t)"
                      :class="t.status"
                    >
                      <option value="none">-</option>
                      <option value="pending">Pendiente</option>
                      <option value="in_progress">En progreso</option>
                      <option value="review">En revisión</option>
                      <option value="approved">Aprobada</option>
                      <option value="completed">Completada</option>
                    </select>

                    <!-- BOTONES -->
                    <div class="task-actions">

                      <button @click="deleteTask(t.id_task)">
                        ❌
                      </button>

                    </div>
                </div>
              </li>
            </template>
          </draggable>
        </ul>
      </div> 
    </div>
  </div>

  <!-- CREATE TASK MODAL -->
  <div
    v-if="showCreateTaskModal"
    class="modal-overlay"
    @click="closeTaskModal"
  >
    <div class="create-modal" @click.stop>

      <button
        class="close-modal-btn"
        @click="closeTaskModal"
      >
        ❌
      </button>

      <h2>Creando tarea...</h2>

      <input
        v-model="newTask"
        placeholder="Nombre de la tarea"
      />

      <textarea
        v-model="newDescription"
        placeholder="Descripción"
        class="create-description"
      ></textarea>

      <div class="dates-grid">

        <div>
          <label>Inicio</label>

          <input
            type="date"
            v-model="newStartDate"
            :min="minDate"
          />
        </div>

        <div>
          <label>Fin</label>

          <input
            type="date"
            v-model="newEndDate"
            :min="minDate"
          />
        </div>

        <div>
          <label>Límite</label>

          <input
            type="date"
            v-model="newDueDate"
            :min="minDate"
          />
        </div>

      </div>

      <select v-model="newStatus">

        <option value="none">-</option>
        <option value="pending">Pendiente</option>
        <option value="in_progress">En progreso</option>
        <option value="review">En revisión</option>
        <option value="approved">Aprobada</option>
        <option value="completed">Completada</option>

      </select>

      <!-- USER -->
      <select v-model="newUserId">

        <option :value="null">
          Sin asignar
        </option>

        <option
          v-for="u in users"
          :key="u.id_user"
          :value="u.id_user"
        >
          {{ u.name }}
        </option>

      </select>

      <button
        class="confirm-create-btn"
        @click="createTask"
      >
        Crear tarea
      </button>

    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import { API_URL } from '@/config'

export default {
  
  components: {
    draggable
  },

  props: ['id'],
  


  data() {
    return {
      projectId: this.id,
      projectName: '',
      tasks: [],
      users: [],

      newTask: '',
      newDescription: '',
      newStartDate: '',
      newEndDate: '',
      newDueDate: '',
      newStatus: 'none',

      showCreateTaskModal: false,
      newUserId: null,

      minDate: new Date().toISOString().split('T')[0],

    }
  },

  mounted() {
    this.loadTasks()
    this.loadProjectUsers()
  },

  methods: {

    ///////////////////////////////////////////////////////////////
    async loadTasks() {

      console.log('Cargando tareas...')

      const token = localStorage.getItem('auth_token')

      // SI NO HAY TOKEN → LOGIN
      if (!token) {

        this.$router.replace('/login')
        return
      }

      const res = await fetch(`${API_URL}/projects/${this.projectId}/tasks`, {

          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      )

      // TOKEN INVÁLIDO / SESIÓN CADUCADA
      if (res.status === 401 || res.status === 403) {

        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')

        this.$router.replace('/login')
        return
      }

      this.tasks = await res.json()
    },

//////////////////////////////////////////////////////////////
    async createTask() {

      console.log('Nueva task:', this.newTask)

      await fetch(`${API_URL}/tasks`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },

        body: JSON.stringify({
          name: this.newTask,
          description: this.newDescription,
          start_date: this.newStartDate,
          end_date: this.newEndDate,
          due_date: this.newDueDate,
          status: this.newStatus,
          project_task_id: this.projectId,
          user_id: this.newUserId
        }),
      })

      this.closeTaskModal()

      this.loadTasks()
    },

    //////////////////////////////////////////////////////////////////////
    async deleteTask(id) {
      const confirmed = confirm(
          '¿Seguro que quieres eliminar esta tarea?'
        )

        if (!confirmed) return
      try {

        await fetch(`${API_URL}/tasks/${id}`, {
          method: 'DELETE',
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
        })

        this.loadTasks()

      } catch (error) {
        console.error('Error eliminando tarea:', error)
      }
    },

    ///////////////////////
    // startEditTask(task) {
    //   this.editingTaskId = task.id_task
    //   this.editTaskName = task.name
    // },

    // cancelEditTask() {
    //   this.editingTaskId = null
    //   this.editTaskName = ''
    // },

    /////////////////////////////////////////////////////////////////////////
    async updateTask(task) {
      try {

        await fetch(`${API_URL}/tasks/${task.id_task}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },

          body: JSON.stringify({
            name: task.name,
            description: task.description,
            start_date: task.start_date,
            end_date: task.end_date,
            due_date: task.due_date,
            status: task.status,
            user_id: task.user_id
          }),
        })

        this.loadTasks()

      } catch (error) {
        console.error('Error actualizando tarea:', error)
      }
    },
/////////////////////////////////////////////////////////
    async loadProjectUsers() {

      try {

        const res = await fetch(`${API_URL}/projects`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
            },
          }
        )

        const projects = await res.json()

        const project = projects.find(
          p => p.id_project == this.projectId
        )

        this.users = project?.users || []
        this.projectName = project?.name || ''

      } catch (error) {

        console.error('Error cargando usuarios:', error)

      }
    },
///////////////////////////////////////////////////////////////////////////
    closeTaskModal() {

      this.showCreateTaskModal = false

      this.newTask = ''
      this.newDescription = ''
      this.newStartDate = ''
      this.newEndDate = ''
      this.newDueDate = ''
      this.newStatus = 'none'
      this.newUserId = null
    },

/////////////////////////////////////////////////////////////////////////
    async saveTaskOrder() {

      try {

        const orderedTasks = this.tasks.map((task, index) => ({
          id_task: task.id_task,
          position: index
        }))

        await fetch(`${API_URL}/tasks/reorder`,
          {
            method: 'POST',

            headers: {
              'Content-Type': 'application/json',
              Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
            },

            body: JSON.stringify({
              tasks: orderedTasks
            }),
          }
        )

      } catch (error) {

        console.error('Error guardando orden:', error)

      }
    }

  }
}
</script>

<style scoped>
/* CONTENEDOR GENERAL */
.project-detail {
  background: linear-gradient(180deg, #6d97d6, #07173f);
  min-height: 100vh;
  padding: 30px;
}

/* ========================= */
/* HEADER PROYECTO */
/* ========================= */

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  
  margin: 60px 80px 20px 80px;
}

.project-title {
  font-family: Poppins;
  font-size: 48px;
  font-weight: bold;
  color: white;

  margin: 0;

  letter-spacing: -1px;
}

.tasks-topbar {
  display: flex;
  align-items: center;
}


/* ========================= */
/* BOTONES GENERALES */
/* ========================= */

button {
  padding: 10px 16px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-weight: bold;
  font-family: Poppins;
  transition: 0.2s;
}

/* BOTÓN VOLVER */

.back-btn {

  display: flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  border: none;
  box-shadow: none;
  color: white;
  padding: 12px 18px;
  font-family: Poppins;
  font-size: 25px;
  font-weight: 600;  
  margin-bottom: 10px;
  margin-left: 65px;
}

.back-btn:hover { 
  transform: translateY(-2px); 
 
}




/* INPUT CREAR */
.create-task {
  margin-bottom: 20px;
}

.create-task input {
  padding: 10px;
  margin-right: 10px;
  border-radius: 6px;
  border: none;
  outline: none;
  background: white;
  color: black;
}

.create-task input::placeholder {
  color: #94a3b8;
}

.create-task input:focus {
  background: rgb(177, 198, 209);
}

/* BOTÓN CREAR */
.create-task button {
  background-color: #ef7e15;
  color: white;
 
}

.create-task button:hover {
  background-color: #0f4fcc;
}
.complet-grid{
  background-color: #eaeaea;
  border-radius: 12px;
  margin: 0px 80px;
  overflow-x: auto;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
  border-radius: 12px;
}


/* HEADER TABLA */
.tasks-header {
  display: grid;

  grid-template-columns:
    180px
    260px
    140px
    130px
    130px
    130px
    140px
    60px;

  gap: 20px;

  padding: 15px 20px;

  font-weight: bold;
  font-family: Poppins;
  color: #0f172a;

  width: max-content;
  min-width: 100%;
}

.task-content {
  display: grid;

  grid-template-columns:
    180px
    260px
    140px
    130px
    130px
    130px
    140px
    60px;

  gap: 20px;

  align-items: center;

  width: max-content;
  min-width: 100%;
}

.tasks-header,
.task-content {
  box-sizing: border-box;
}

/* INPUT NOMBRE */
.task-title-input {
  width: 100%;
  min-width: 0;
  font-weight: bold;
  font-size: 20px;
  background: none;
  color: #0f172a;
}

/* DESCRIPCIÓN */
.task-description {
  width: 100%;
  min-width: 0;
  height: 40px;
  resize: none;
  padding: 8px;
  border-radius: 6px;
  border: none;
  background: white;
  color: #0f172a;
}

/* FECHAS */
.task-date-input {
  width: 100%;
  min-width: 0;
  background: #f1f0f0;
  color: #0f172a;
  border: none;
  border-radius: 6px;
  padding: 8px;
}

/* SELECT STATUS */
select {
  width: 100%;
  min-width: 0;
  background: #f1f0f0;
  color: #0f172a;
  border: none;
  border-radius: 6px;
  padding: 8px;
  
}

/* BOTONES */
.task-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
}

/* BOTÓN ELIMINAR */
.task-actions button {
  background: transparent;
  border: none;
  font-size: 18px;
  cursor: pointer;
  color: #ef4444;
  transition: 0.2s;
}

.task-actions button:hover {
  transform: scale(1.2);
  color: #dc2626;
}

/* ITEM TASK */
ul{
  list-style: none;
  padding: 0;
  margin: 0;
}
li {
  width: max-content;
  min-width: 100%;
  background: #D9D9D9;
  padding: 15px 20px;
  border-radius: 10px;
  transition: 0.2s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.task-description {
  height: 40px;
  resize: none;
}

/* HOVER */
li:hover {
  transform: translateY(-2px);
  filter: brightness(0.90);
}

/* TEXTO */
li span {
  font-size: 16px;
  font-weight: 500;
}

/* BOTONES ICONO */
li button {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 16px;
  padding: 5px;
  transition: 0.2s;
}

/* EDITAR */
li button:nth-of-type(1) {
  color: #f59e0b;
}

li button:nth-of-type(1):hover {
  transform: scale(1.2);
  color: #d97706;
}

/* ELIMINAR */
li button:nth-of-type(2) {
  color: #ef4444;
}

li button:nth-of-type(2):hover {
  transform: scale(1.2);
  color: #dc2626;
}

/* MODO EDICIÓN */
li input {  
  color: white;
  padding: 8px;
  border-radius: 6px;
  border: none;
}

/* BOTÓN GUARDAR */
li button:first-child {
  color: #22c55e;
}

li button:first-child:hover {
  color: #16a34a;
}

/* BOTÓN CANCELAR */
li button:last-child {
  color: #64748b;
}

li button:last-child:hover {
  color: #475569;
}
/* ========================= */
/* ESTADOS SELECT */
/* ========================= */

/* SIN ESTADO */
select.none {
  background: #d1d5db;
  color: #111827;
}

/* PENDIENTE */
select.pending {
  background: #ef4444;
  color: white;
}

/* EN PROGRESO */
select.in_progress {
  background: #f59e0b;
  color: white;
}

/* EN REVISIÓN */
select.review {
  background: #3b82f6;
  color: white;
}

/* APROBADA */
select.approved {
  background: #8b5cf6;
  color: white;
}

/* COMPLETADA */
select.completed {
  background: #22c55e;
  color: white;
}

/* ========================= */
/* COLOR DE FONDO POR ESTADO */
/* ========================= */

/* SIN ESTADO */
li.none {
  background: #f3f4f6;
}

/* PENDIENTE */
li.pending {
  background: #fee2e2;
}

/* EN PROGRESO */
li.in_progress {
  background: #fef3c7;
}

/* EN REVISIÓN */
li.review {
  background: #dbeafe;
}

/* APROBADA */
li.approved {
  background: #ede9fe;
}

/* COMPLETADA */
li.completed {
  background: #dcfce7;
}


.tasks-topbar {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 25px;
}

.create-task-btn {
  background: #FE9F5B;
  color: white;
  font-family: Poppins;
  border-radius: 12px;
  padding: 14px 22px;
  font-weight: 600;
  font-size: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.create-task-btn:hover {
  background: #f1873c;
}

/* MODAL */

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.65);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.create-modal {
  width: 550px;
  background: #d9d9d9;
  border-radius: 14px;
  padding: 35px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
}

.create-modal h2 {
  font-family: Poppins;
}

.create-modal input,
.create-modal textarea,
.create-modal select {
  border: none;
  border-radius: 10px;
  padding: 12px;
  background: white;
  color: #0f172a;
}

.create-description {
  min-height: 120px;
  resize: vertical;
}

.close-modal-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #d4b0b0;
}

.confirm-create-btn {
  background: #16a34a;
  color: white;
  margin: 10px 150px;
  font-family: Poppins;
  border-radius: 12px;
  

}

.confirm-create-btn:hover {
  background: #15803d;
}

.dates-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.dates-grid div {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding: 0;
}

.dates-grid label {
  font-weight: bold;
  font-family: Poppins;
}

/* ========================= */
        /* DRAG&DROP */
/* ========================= */


.sortable-ghost {
  opacity: 0.4;
}

.sortable-drag {
  transform: rotate(2deg);
}

.sortable-chosen {
  cursor: grabbing;
}
</style>