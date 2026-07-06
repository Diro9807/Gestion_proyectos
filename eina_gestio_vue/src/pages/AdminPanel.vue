<template>

  

  <div class="admin-panel">

    <div class="admin-header">

        <h1 class="admin-title">
            /Administración
        </h1>

        <div class="admin-tools">

            <input
                v-model="search"
                type="text"
                placeholder="Buscar usuario..."
                class="search-input"
            >

            <select
                v-model="roleFilter"
                class="filter-select"
            >

                <option value="all">
                    Todos
                </option>

                <option value="1">
                    Administradores
                </option>

                <option value="2">
                    Usuarios
                </option>

            </select>

        </div>

        

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
                    v-for="user in paginatedUsers"
                    :key="user.id_user"
                >

                    <td>{{ user.name }}</td>

                    <td>{{ user.email }}</td>

                    <td>

                        <select
                            class="role-select"
                            :class="user.roles_id == 1 ? 'admin-role' : 'user-role'"
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

    <div
    v-if="totalPages > 1"
    class="pagination"
>

    <button
        class="page-btn"
        @click="currentPage--"
        :disabled="currentPage === 1"
    >
        ←
    </button>

    <button
        v-for="page in visiblePages"
        :key="page"
        class="page-number"
        :class="{
            active: currentPage === page,
            dots: page === '...'
        }"
        @click="page !== '...' && (currentPage = page)"
    >
        {{ page }}
    </button>

    <button
        class="page-btn"
        @click="currentPage++"
        :disabled="currentPage === totalPages"
    >
        →
    </button>

</div>




</div>

</template>

<script>


import { API_URL } from '@/config'

export default {

 
  data() {

    return {

      users: [],
      search: '',
      roleFilter: 'all',
      currentPage: 1,
      usersPerPage: 8

    }

  },

  mounted() {

    this.loadUsers()

  },

  computed: {

        filteredUsers() {

            let result = this.users

            // Buscar por nombre
            if (this.search.trim() !== '') {

                result = result.filter(user =>
                    user.name
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                )

            }

            // Filtrar por rol
            if (this.roleFilter !== 'all') {

                result = result.filter(
                    user => user.roles_id == this.roleFilter
                )

            }

            return result

        },

        paginatedUsers() {

            const start = (this.currentPage - 1) * this.usersPerPage

            return this.filteredUsers.slice(
                start,
                start + this.usersPerPage
            )

        },

        totalPages() {

            return Math.ceil(
                this.filteredUsers.length / this.usersPerPage
            )

        },

        // Muestra una cantidad reducida de paginas en el paginado 
        visiblePages() {

        const pages = []

        const maxVisible = 5

        if (this.totalPages <= maxVisible) {

            for (let i = 1; i <= this.totalPages; i++) {
                pages.push(i)
            }

            return pages

        }

        pages.push(1)

        let start = Math.max(2, this.currentPage - 1)
        let end = Math.min(this.totalPages - 1, this.currentPage + 1)

        if (this.currentPage <= 3) {

            start = 2
            end = 4

        }

        if (this.currentPage >= this.totalPages - 2) {

            start = this.totalPages - 3
            end = this.totalPages - 1

        }

        if (start > 2) {

            pages.push("...")

        }

        for (let i = start; i <= end; i++) {

            pages.push(i)

        }

        if (end < this.totalPages - 1) {

            pages.push("...")

        }

        pages.push(this.totalPages)

        return pages

    }

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

        const newRole = Number(event.target.value)

        try {

            const response = await fetch(
                `${API_URL}/admin/users/${user.id_user}/role`,
                {
                    method: 'PUT',

                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },

                    body: JSON.stringify({
                        roles_id: newRole
                    })

                }
            )

            const data = await response.json()

            if (!response.ok) {

                alert(data.message)

                event.target.value = user.roles_id

                return

            }

            user.roles_id = newRole

            user.role.type_rol = newRole === 1
                ? 'admin'
                : 'user'

        } catch (error) {

            console.error(error)

            event.target.value = user.roles_id

        }

    },

    async deleteUser(user) {

        const confirmDelete = confirm(
            `Vas a eliminar definitivamente al usuario "${user.name}". Esta acción no se puede deshacer.
            ¿Continuar?`
        )

        if (!confirmDelete) {

            return

        }

        try {

            const response = await fetch(
                `${API_URL}/admin/users/${user.id_user}`,
                {

                    method: 'DELETE',

                    headers:{

                        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
                        Accept: 'application/json'

                    }

                }
            )

           const data = await response.json()

            if (!response.ok) {
                alert(data.message)
                return
            }

            this.users = this.users.filter(
                u => u.id_user !== user.id_user
            )

            alert(data.message)

        }

        catch(error){

            console.error(error)

            alert("Error eliminando usuario.")

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

/* FILTRO */

.admin-tools{

    display:flex;

    gap:15px;

    margin:25px 0;

    align-items:center;

}

.search-input{

    flex:1;

    padding:12px 18px;

    border:none;

    border-radius:12px;

    font-family:Poppins;

    font-size:15px;

}

.filter-select{

    padding:12px 18px;

    border:none;

    border-radius:12px;

    font-family:Poppins;

    cursor:pointer;

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

    padding:10px 16px;

    border:none;

    border-radius:999px;

    font-family:Poppins;

    font-weight:700;

    color:white;

    cursor:pointer;

    transition:.25s;

    appearance:none;

    -webkit-appearance:none;

    -moz-appearance:none;

    min-width:145px;

    text-align:center;
}

.role-select:hover{

    transform:translateY(-2px);

    box-shadow:0 5px 15px rgba(0,0,0,.18);
}

.role-select:focus{

    outline:none;
}

.admin-role{

    background:#ef4444;
}

.user-role{

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

/* PAGINACIÓN */


.pagination{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:12px;

    margin-top:35px;

    flex-wrap:wrap;

}

.page-btn,
.page-number{

    width:46px;

    height:46px;

    border:none;

    border-radius:12px;

    font-family:Poppins;

    font-size:16px;

    font-weight:600;

    cursor:pointer;

    transition:.25s;
}

/* Flechas */

.page-btn{

    background:#FE9F5B;

    color:white;

    box-shadow:0 8px 18px rgba(0,0,0,.18);

}

.page-btn:hover:not(:disabled){

    transform:translateY(-2px);

    background:#f1873c;

}

.page-btn:disabled{

    background:#d1d5db;

    color:#9ca3af;

    cursor:not-allowed;

    box-shadow:none;

}

/* Números */

.page-number{

    background:#6d97d6;

    color:#374151;

    border:2px solid transparent;

}

.page-number:hover{

    border-color:#FE9F5B;

    transform:translateY(-2px);

}

.page-number.active{

    background:#FE9F5B;

    color:white;

    box-shadow:0 8px 18px rgba(0,0,0,.18);

}

.page-number.dots{

    background:transparent;

    box-shadow:none;

    cursor:default;

    color:white;

    border:none;

}

.page-number.dots:hover{

    transform:none;

    border:none;

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