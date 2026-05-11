<template>
  <div class="register-container">

    <div class="left-panel">
      <div class="logo-container">
        <img src="/logo.png" alt="Logo" class="logo" />
      </div>      
      <h1>Welcome to Projects</h1>
      
    </div>

    <div class="right-panel">
      <form class="register-card" @submit.prevent="loginUser">

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
    }
  },

  methods: {
    async loginUser() {
      if (!this.user.email || !this.user.password) {
        this.error = 'Completa todos los campos'
        return
      }

      this.sending = true
      this.error = ''

      try {
        const response = await fetch('http://127.0.0.1:8000/api/login', {
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
        
        this.$router.push('/projects')

      } catch (error) {
        this.error = error.message || 'Error de conexión'
      } finally {
        this.sending = false
      }
    },
  },
}
</script>

<style>
.register-container {
  height: 100vh;
  display: flex;
  font-family: Arial, sans-serif;
  
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
  background: white;
  padding: 35px;
  border-radius: 12px;
  width: 450px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 15px;
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
</style>