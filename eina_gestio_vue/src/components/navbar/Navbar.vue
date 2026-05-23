<template>  
  <nav class="navbar">

  <!-- IZQUIERDA -->
  <div class="logo-section">

    <div
      class="logo-wrapper"
      @click="$router.push('/projects')"
    >
      <img
        src="/logo.png"
        alt="Logo"
        class="logo-image"
      />

      <span class="logo-text">
        rojects
      </span>
    </div>

    <!-- USER SOLO MÓVIL -->
    <div class="navbar-user mobile-user">

      <div class="navbar-avatar">
        {{ userInitial }}
      </div>

      <span class="navbar-username">
        {{ userName }}
      </span>

    </div>

    <!-- HAMBURGUESA -->
    <button
      class="mobile-menu-btn"
      @click="mobileMenuOpen = !mobileMenuOpen"
    >
      ☰
    </button>

  </div>

  <!-- LINKS -->
  <div
    class="links"
    :class="{ open: mobileMenuOpen }"
  >

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

    <!-- USER DESKTOP -->
    <div class="navbar-user desktop-user">

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
      user: null,   
      mobileMenuOpen: false,   
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

.logo-wrapper {

  display: flex;
  align-items: center;
  gap: 0;
  cursor: pointer;
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
  margin-top: 7px;
  margin-left: 5px;
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

.mobile-user {
  display: none;
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

.mobile-menu-btn {
  display: none;

  background: rgba(255,255,255,0.45);

  border: none;

  width: 42px;

  height: 42px;

  border-radius: 10px;

  font-size: 24px;

  cursor: pointer;

  color: #374151;

  align-items: center;

  justify-content: center;

  transition: 0.25s;
}

.mobile-menu-btn:hover {

  background: rgba(255,255,255,0.75);
}

/* ========================= */
/* MOBILE NAVBAR */
/* ========================= */

@media (max-width: 768px) {

  .navbar {

    margin: 0;
    border-radius: 0;
    padding: 0 18px;
    height: 74px;
    position: sticky;
    overflow: visible;    
  }

  .logo-section {
    width: 100%;
    display: flex;
    align-items: center;    
    gap: 12px;
  }

  .logo-image {
    width: 34px;
    height: 34px;
  }

  .logo-text {
    position: static;
    font-size: 24px;
    margin-left: 5px;
  }

  .mobile-menu-btn {
    display: flex;
    flex-shrink: 0;
  }

  .links-btn,
  .logout-btn {

    width: 100%;
  }

  .links {
    position: absolute;
    top: 85px;
    right: 18px;
    width: 260px;
    display: none;
    flex-direction: column;
    gap: 12px;
    padding: 18px;
    border-radius: 16px;
    background: rgba(186,186,186,0.97);
    backdrop-filter: blur(12px);
    box-shadow:
      0 20px 35px rgba(0,0,0,0.25);
    z-index: 99999;
  }

  .links.open {

    display: flex;
  }

  
  /* USER */

  .desktop-user {

    display: none;
  }

  .mobile-user {

    display: flex;

    margin-left: auto;
  }

  .navbar-user {

    padding: 5px 12px 5px 8px;
  }

  .navbar-username {

    font-size: 14px;
  }
}

</style>