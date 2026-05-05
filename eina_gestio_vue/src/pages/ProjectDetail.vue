<template>
  <div class="project-detail">

    <button @click="$router.push('/projects')">⬅ Volver</button>

    <h1>Proyecto {{ projectId }}</h1>

    <!-- CREAR TASK -->
    <div class="create-task">
      <input v-model="newTask" placeholder="Nueva tarea" />
      <button @click="createTask">Crear</button>
    </div>

    <!-- LISTA TASKS -->
    <ul>
      <li v-for="t in tasks" :key="t.id_task">

        <!-- NORMAL -->
        <div v-if="editingTaskId !== t.id_task">
          <span>{{ t.name }}</span>

          <button @click="startEditTask(t)">✏️</button>
          <button @click="deleteTask(t.id_task)">❌</button>
        </div>

        <!-- EDITANDO -->
        <div v-else>
          <input v-model="editTaskName" />

          <button @click="updateTask(t.id_task)">Guardar</button>
          <button @click="cancelEditTask">Cancelar</button>
        </div>

      </li>
    </ul>

  </div>
</template>

<script>
export default {
  props: ['id'],

  data() {
    return {
      projectId: this.id,
      tasks: [],
      newTask: '',

      editingTaskId: null,
      editTaskName: ''
    }
  },

  mounted() {
    this.loadTasks()
  },

  methods: {

    ///////////////////////
    async loadTasks() {
      console.log('Cargando tareas...')

      const res = await fetch(`http://127.0.0.1:8000/api/projects/${this.projectId}/tasks`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
      })

      this.tasks = await res.json()
    },

    ///////////////////////
    async createTask() {
      console.log('Nueva task:', this.newTask)

      await fetch('http://127.0.0.1:8000/api/tasks', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
        body: JSON.stringify({
          name: this.newTask,
          project_task_id: this.projectId
        }),
      })

      this.newTask = ''
      this.loadTasks()
    },

    ///////////////////////
    async deleteTask(id) {
      try {
        await fetch(`http://127.0.0.1:8000/api/tasks/${id}`, {
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
    startEditTask(task) {
      this.editingTaskId = task.id_task
      this.editTaskName = task.name
    },

    cancelEditTask() {
      this.editingTaskId = null
      this.editTaskName = ''
    },

    ///////////////////////
    async updateTask(id) {
      try {
        await fetch(`http://127.0.0.1:8000/api/tasks/${id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
          },
          body: JSON.stringify({
            name: this.editTaskName
          }),
        })

        this.editingTaskId = null
        this.loadTasks()

      } catch (error) {
        console.error('Error actualizando tarea:', error)
      }
    }
  }
}
</script>

<style scoped>
/* CONTENEDOR GENERAL */
.project-detail {
  background-color: #6d97d6;
  min-height: 100vh;
  padding: 30px;
}

/* TÍTULO */
h1 {
  margin-bottom: 20px;
  font-size: 32px;
}

/* BOTÓN VOLVER */
.project-detail > button {
  margin-bottom: 15px;
  background-color: #64748b;
  color: white;
}

.project-detail > button:hover {
  background-color: #475569;
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
  background: #1e293b;
  color: white;
}

.create-task input::placeholder {
  color: #94a3b8;
}

.create-task input:focus {
  background: #334155;
}

/* BOTÓN CREAR */
.create-task button {
  background-color: #ef7e15;
  color: white;
}

.create-task button:hover {
  background-color: #0f4fcc;
}

/* LISTA */
ul {
  list-style: none;
  padding: 20px;
  background-color: rgb(188, 188, 188);
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* ITEM TASK */
li {
  background: #D9D9D9;
  padding: 15px 20px;
  border-radius: 10px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: 0.2s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* HOVER */
li:hover {
  background: #cbd5e1;
  transform: translateY(-2px);
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
  background: #0f172a;
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
</style>