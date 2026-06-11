<template>
  <div class="register-container" @mousemove="handleMouseMove" :style="backgroundStyle">

    <div class="left-panel">
      <div
        class="logo-container"
        @click="showCard = !showCard"
      >
        <img
          src="/logo.png"
          alt="Logo"
          class="logo"
        />
      </div>  

      <h1>Welcome to Projects</h1>
      
    </div>

    <div class="right-panel">
      <form v-if="!isMobile || showCard"
        class="register-card"
        @submit.prevent="loginUser">

        <h2>Iniciar Sesión</h2>

        <div class="form-group">
          <label>Correo</label>
          <input v-model="user.email" type="email" />
        </div>

        <div class="form-group">
          <label>Password</label>
          <input v-model="user.password" type="password" />
        </div>

        <p class="error" v-if="error">{{ error }}</p>

        <button class="btn primary" type="submit" :disabled="sending">
          {{ sending ? 'Entrando...' : 'Iniciar Sesión' }}
        </button>

        <button class="btn secondary" type="button" @click="$router.push('/register')">
          Crear cuenta
        </button>

      </form>
    </div>

  </div>
</template>

<script>

import { API_URL } from '@/config'

export default {
  name: 'LoginComponent',

  data() {
    return {
      user: {
        email: '',
        password: '',
      },
      sending: false,
      error: '',
      showCard: false,
      isMobile: window.innerWidth <= 768,

      mouseX: '50%',
      mouseY: '50%',
    }
  },
  computed: {

    backgroundStyle() {

      return {
        background: `
          radial-gradient(
            circle 3000px at ${this.mouseX} ${this.mouseY},
            rgba(109,151,214,0.55),
            rgba(53,92,155,0.15),
            transparent 80%
          ),
          linear-gradient(
            135deg,
            #07173f,
            #0f2f70,
            #07173f
          )
        `
      }
    }
  },

  mounted() {

    window.addEventListener(
      'resize',
      this.checkScreen
    )

    this.checkScreen()
  },

  beforeUnmount() {

    window.removeEventListener(
      'resize',
      this.checkScreen
    )
  },

  methods: {
    checkScreen() {

      this.isMobile =
        window.innerWidth <= 768

      if (!this.isMobile) {

        this.showCard = true
      }
    },

    async loginUser() {
      if (!this.user.email || !this.user.password) {
        this.error = 'Completa todos los campos'
        return
      }

      this.sending = true
      this.error = ''

      try {
        const response = await fetch(`${API_URL}/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify(this.user),
        })

        const data = await response.json()
        console.log('DATA COMPLETA:', data)

        if (!response.ok) {
          throw new Error(data.message || 'Error en el login')
        }

        localStorage.setItem('auth_token', data.access_token)
        localStorage.setItem('auth_user', JSON.stringify(data.user))

        window.dispatchEvent(new Event('auth-changed'))

        this.$router.push('/projects')

      } catch (error) {
        this.error = error.message || 'Error de conexión'
      } finally {
        this.sending = false
      }
    },

    handleMouseMove(e) {

      this.mouseX = `${(e.clientX / window.innerWidth) * 100}%`
      this.mouseY = `${(e.clientY / window.innerHeight) * 100}%`
    },
  },
}
</script>

<style>


.register-container {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: Poppins;  
  transition: background 0.08s linear;
}

/* IZQUIERDA */
.left-panel {
  flex: 1;  
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 15px;
  
}

.left-panel h1{
  font-size: 70px;
}

.logo {
  width: 350px;
}

/* DERECHA */
.right-panel {
  flex: 0.95;
  display: flex;
  justify-content: center;
  align-items: center;
  
  
}

/* CARD */
.register-card {
  background: #d9d9d9;

  backdrop-filter: blur(10px);

  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
  padding: 35px;
  border-radius: 12px;
  width: 450px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 15px;
  animation: fadeCard 0.35s ease;
}

@keyframes fadeCard {

  from {
    opacity: 0;
    transform: translateY(20px) scale(0.96);
  }

  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.logo-container {
  cursor: pointer;

  transition: transform 0.25s ease;
}

.logo-container:hover {
  transform: scale(1.05);
}

.register-card:hover {
  transform: translateY(-8px);

  box-shadow:
    0 25px 45px rgba(0,0,0,0.55);
}

.register-card h2 {
  text-align: center;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group input {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.btn {
  padding: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

.btn.primary {
  background-color: #156cef;
  color: white;
}

.btn.secondary {
  background-color: #f0f0f0;
}

.error {
  color: red;
  text-align: center;
}

@media (max-width: 768px) {

  .register-container {
    flex-direction: column;
    padding: 20px;
    overflow-y: auto;
  }

  .left-panel {
    flex: unset;
    margin-top: 40px;
    gap: 5px;
  }

  .left-panel h1 {
    font-size: 36px;
    text-align: center;
  }

  .logo {
    width: 180px;
  }

  .right-panel {
    width: 100%;
    flex: unset;
    margin-top: 25px;
  }

  .register-card {
    width: 100%;
    max-width: 400px;
    padding: 25px;
    margin-bottom: 30px;
  }

  .form-group input {
    font-size: 16px;
  }

  .btn {
    width: 100%;
  }
}
</style>