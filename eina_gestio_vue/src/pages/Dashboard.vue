<template>
  <div>
    <div class="dashboard-content">
      <h1>Bienvenido 👋</h1>
      <p>Aquí irá tu gestión de proyectos</p>
    </div>
  </div>
</template>

<script>
import Navbar from '../components/navbar/Navbar.vue'

export default {
  components: {
    Navbar,
  },
}
</script>

<style>
.dashboard-content {  
  height: 150vh;
    background: linear-gradient(160deg, #6d97d6, #07173f);
  color: white;
  border-radius: 5px;
}

</style>