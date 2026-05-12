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

      <!-- QUITADO DASHBOARD -->
      <button
        class="nav-btn"
        @click="$router.push('/projects')"
      >
        Proyectos
      </button>

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

<style scoped>

.navbar {
  height: 72px;
  background: rgba(186, 186, 186, 0.92);
  backdrop-filter: blur(10px);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 35px;
  border-radius: 0 0 14px 14px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);  
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

.nav-btn,
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

.nav-btn {
  background: #FE9F5B;
  color: white;
}

.nav-btn:hover {
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

</style>