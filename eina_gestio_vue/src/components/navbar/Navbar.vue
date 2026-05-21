<template>
  <nav class="navbar">

    <!-- LOGO -->
    <div class="logo-section" @click="$router.push('/projects')">

      <!-- CAMBIA LA RUTA POR TU IMAGEN -->
      <img
        src="/logo.png"
        alt="Logo"
        class="logo-image"
      />

      <span class="logo-text">
        rojects
      </span>

    </div>

    <!-- LINKS -->    
  <div class="links">

    <button
      class="links-btn"
      @click="$router.push('/projects')"
    >
      Mis proyectos
    </button>

    <button
      class="links-btn"
      @click="$router.push('/shared-projects')"
    >
      Compartidos
    </button>

    <!-- USER TAG -->
    <div class="navbar-user">

      <div class="navbar-avatar">
        {{ userInitial }}
      </div>

      <span class="navbar-username">
        {{ userName }}
      </span>

    </div>

    <button
      class="logout-btn"
      @click="logout"
    >
      Logout
    </button>

  </div>

  </nav>
</template>

<script>

export default {
  name: 'NavbarComponent',

    created() {

    window.addEventListener('auth-changed', this.loadUser)

    this.loadUser()
  },

  data() {
    
    return {
      user: null      
    }
  },

  mounted() {
    this.loadUser()

    window.addEventListener('storage', this.loadUser)
  },

  beforeUnmount() {
    window.removeEventListener('storage', this.loadUser)
  },

  computed: {
    userName() {
      return this.user?.name || 'Usuario'
    },

    userInitial() {
      return this.userName.charAt(0).toUpperCase()
    }
  },

  methods: {
    loadUser() {

      const user = localStorage.getItem('auth_user')

      this.user = user
        ? JSON.parse(user)
        : null
    },

    logout() {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')

      window.dispatchEvent(new Event('auth-changed'))

      this.$router.push('/login')
    },


  },
}

</script>

<style scoped>

.navbar {
  margin: 0px 62px 0px 62px;
  height: 65px;
  background: rgba(186, 186, 186, 0.92);
  backdrop-filter: blur(10px);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 35px;
  border-radius: 0px 0px 12px 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.18);
  position: sticky;
  top: 0;
  z-index: 9999;
  transition:
    background 0.25s ease,
    box-shadow 0.25s ease,
    transform 0.25s ease;
}

/* ========================= */
/* LOGO */
/* ========================= */

.logo-section {
  display: flex;
  align-items: center;
  gap: 14px;
  cursor: pointer;
  transition: 0.2s;
}

.logo-section:hover {
  transform: scale(1.02);
}

.logo-image {
  width: 42px;
  height: 42px;
  object-fit: contain;
}

.logo-text {
  font-size: 34px;
  font-weight: 700;
  font-family: Poppins;
  color: #4c4c4c;
  margin-left: -10px;
  letter-spacing: 0.5px;
}

/* ========================= */
/* LINKS */
/* ========================= */

.links {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* BOTONES */

.links-btn,
.logout-btn {
  border: none;

  padding: 10px 18px;

  border-radius: 10px;

  cursor: pointer;

  font-family: Poppins;
  font-size: 14px;
  font-weight: 600;

  transition: 0.25s;
}

/* BOTÓN NORMAL */

.links-btn {
  background: #FE9F5B;
  color: white;
}

.links-btn:hover {
  background: #f1873c;

  transform: translateY(-2px);
}

/* LOGOUT */

.logout-btn {
  background: #ef4444;
  color: white;
}

.logout-btn:hover {
  background: #dc2626;

  transform: translateY(-2px);
}

/* ========================= */
/* RESPONSIVE */
/* ========================= */

@media (max-width: 768px) {

  .navbar {
    padding: 0 20px;
  }

  .logo-text {
    font-size: 24px;
  }

  .logo-image {
    width: 34px;
    height: 34px;
  }

  .nav-btn,
  .logout-btn {
    padding: 8px 12px;
    font-size: 13px;
  }
}

.navbar-user {
  display: flex;
  align-items: center;
  gap: 10px;

  background: rgba(255, 255, 255, 0.765);

  padding: 5px 15px 5px 8px;

  border-radius: 999px;

  font-family: Poppins;
}

.navbar-avatar {
  width: 32px;
  height: 32px;

  border-radius: 50%;

  background: linear-gradient(135deg, #3b82f6, #1d4ed8);

  display: flex;
  align-items: center;
  justify-content: center;

  color: white;

  font-weight: bold;

  font-size: 14px;
}

.navbar-username {
  font-weight: 600;
  color: #374151;
}

</style>