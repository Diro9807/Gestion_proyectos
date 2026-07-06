<template>

  

  <div class="admin-panel">

    <div class="admin-header">

        <h1 class="admin-title">
            /Administración
        </h1>

        

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

                        <select
                            class="role-select"
                            :value="user.roles_id"
                            @change="changeRole(user, $event)"
                        >

                            <option value="1">
                                Administrador
                            </option>

                            <option value="2">
                                Usuario
                            </option>

                        </select>

                    </td>

                    <td>

                        <button
                            class="delete-btn"
                            @click="deleteUser(user)"
                        >
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

    },

    async changeRole(user, event) {

        const newRole = event.target.value

        console.log(
            user.name,
            "=>",
            newRole
        )

    },


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

.role-select{

    padding:8px 14px;

    border-radius:10px;

    border:2px solid #d1d5db;

    background:white;

    font-family:Poppins;

    font-weight:600;

    cursor:pointer;

    transition:.2s;
}

.role-select:hover{

    border-color:#FE9F5B;
}

.role-select:focus{

    outline:none;

    border-color:#FE9F5B;
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



table{

    min-width:700px;
}

}
</style>