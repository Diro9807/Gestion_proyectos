<template>

  

  <div class="admin-panel">

    <div class="admin-header">

        <h1 class="admin-title">
            /Administración
        </h1>

        <button class="create-user-btn">
            + Nuevo usuario
        </button>

    </div>

    <div class="table-container">

        <table>

            <thead>

                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="user in users"
                    :key="user.id_user"
                >

                    <td>{{ user.name }}</td>

                    <td>{{ user.email }}</td>

                    <td>

                        <span
                            class="role-badge"
                            :class="user.role?.type_rol"
                        >
                            {{ user.role?.type_rol }}
                        </span>

                    </td>

                    <td>

                        <button class="edit-btn">
                            ✏️
                        </button>

                        <button class="delete-btn">
                            ❌
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

</template>

<script>


import { API_URL } from '@/config'

export default {

 
  data() {

    return {

      users: []

    }

  },

  mounted() {

    this.loadUsers()

  },

  methods: {

    async loadUsers() {

      try {

        const response = await fetch(`${API_URL}/admin/users`, {

          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
            Accept: 'application/json'
          }

        })

        if (!response.ok) {

          throw new Error('Error cargando usuarios')

        }

        this.users = await response.json()

      } catch (error) {

        console.error(error)

      }

    }

  }

}

</script>

<style scoped>

.admin-panel{
    min-height:100vh;
    padding:40px 80px;
    background:linear-gradient(180deg,#6d97d6,#07173f);
}

/* HEADER */

.admin-header{

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:30px;
}

.admin-title{

    color:white;
    font-size:48px;
    font-family:Poppins;
    font-weight:700;
    margin:0;
}

.create-user-btn{

    background:#FE9F5B;
    color:white;

    border:none;
    border-radius:12px;

    padding:14px 24px;

    font-family:Poppins;
    font-weight:600;

    cursor:pointer;

    transition:.2s;
}

.create-user-btn:hover{

    background:#f1873c;
}

/* CONTENEDOR TABLA */

.table-container{

    background:#e5e5e5;

    border-radius:15px;

    padding:30px;

    box-shadow:0 15px 35px rgba(0,0,0,.20);

    overflow-x:auto;
}

/* TABLA */

table{

    width:100%;

    border-collapse:collapse;

    font-family:Poppins;
}

thead{

    background:#d3d3d3;
}

th{

    padding:18px;

    font-size:15px;

    text-align:left;

    color:#1f2937;
}

td{

    padding:18px;

    border-bottom:1px solid #d1d5db;
}

tbody tr{

    transition:.2s;
}

tbody tr:hover{

    background:#f5f5f5;
}

/* ROLES */

.role-badge{

    display:inline-block;

    padding:6px 14px;

    border-radius:999px;

    font-size:13px;

    font-weight:600;

    color:white;
}

.role-badge.admin{

    background:#ef4444;
}

.role-badge.user{

    background:#3b82f6;
}

/* BOTONES */

.edit-btn,
.delete-btn{

    border:none;

    background:none;

    font-size:18px;

    cursor:pointer;

    transition:.2s;
}

.edit-btn:hover{

    transform:scale(1.2);
}

.delete-btn:hover{

    transform:scale(1.2);
}

@media(max-width:768px){

.admin-panel{

    padding:20px;
}

.admin-header{

    flex-direction:column;
    align-items:flex-start;
    gap:20px;
}

.admin-title{

    font-size:30px;
}

.create-user-btn{

    width:100%;
}

table{

    min-width:700px;
}

}
</style>