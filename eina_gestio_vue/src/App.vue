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

  data() {

    return {
      isLogged: false
    }
  },

  created() {

    this.checkAuth()

    window.addEventListener(
      'auth-changed',
      this.checkAuth
    )
  },

  beforeUnmount() {

    window.removeEventListener(
      'auth-changed',
      this.checkAuth
    )
  },

  methods: {

    checkAuth() {

      this.isLogged =
        !!localStorage.getItem('auth_token')
    }
  }
}
</script>