<template>
  <nav class="navbar">
    <div class="logo">Projects</div>

    <div class="links">
      <button @click="$router.push('/dashboard')">Inicio</button>
      <button @click="$router.push('/projects')">Proyectos</button>
      <button @click="logout">Logout</button>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'NavbarComponent',

  methods: {
    logout() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      this.$router.push('/login')
      window.location.reload()
    },
  },
}
</script>

<style>
.navbar {
  height: 60px;
  background: #D9D9D9;
  color: rgb(76, 76, 76);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 30px;
  border-radius: 5px;
}

.logo {
  font-weight: bold;
  font-size: 45px;
  font-family: Poppins;
}

.links button {
  margin-left: 10px;
  padding: 8px 12px;
  border: none;
  background: #FE9F5B;
  color: white;
  border-radius: 5px;
  cursor: pointer;
  font-family: Poppins;
  font-size: 15px;
}

.links button:hover {
  background: #6d97d6;
}
</style>