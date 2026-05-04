<template>
  <div>
    <navbarPerez v-if="isLogged" />

    <router-view />

    <footerPerez />
  </div>
</template>

<script>
import footerPerez from './components/footer/Footer.vue'
import navbarPerez from './components/navbar/Navbar.vue'

export default {
  name: 'App',

  components: {
    footerPerez,
    navbarPerez,
  },

  //comprueba el estado de la sesión para hacer desaparecer el navbar si es false
  computed: {
    isLogged() {
      return !!localStorage.getItem('auth_token')
    },
  },
}
</script>