<template>
  <div class="register-container" @mousemove="handleMouseMove" :style="backgroundStyle">
    <form class="register-card" @submit.prevent="registerUser">

      <h2>Crear Cuenta</h2>

      <div class="form-group">
        <label>Nombre</label>
        <input v-model="user.name" type="text" />
      </div>

      <div class="form-group">
        <label>Correo</label>
        <input v-model="user.email" type="email" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="user.password" type="password" />
      </div>

      <div class="form-group">
        <label>Confirmar Password</label>
        <input v-model="user.password_confirmation" type="password" />
      </div>

      <p class="error" v-if="error">{{ error }}</p>
      <p class="success" v-if="success">{{ success }}</p>

      <button class="btn primary" type="submit" :disabled="sending">
        {{ sending ? 'Creando...' : 'Crear Cuenta' }}
      </button>

      <button class="btn secondary" type="button" @click="$router.push('/login')">
        Cancelar
      </button>

    </form>
  </div>
</template>

<script>
export default {
  name: 'RegisterComponent',

  data() {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      sending: false,
      error: '',
      success: '',

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

  methods: {
    async registerUser() {
      // Validación básica
      if (
        !this.user.name ||
        !this.user.email ||
        !this.user.password ||
        !this.user.password_confirmation
        ) {
        this.error = 'Completa todos los campos'
        return
      }

      if (this.user.password !== this.user.password_confirmation) {
        this.error = 'Las contraseñas no coinciden'
        return
      }

      this.sending = true
      this.error = ''
      this.success = ''

      try {
        console.log('📝 [REGISTER] Enviando datos...')

        const response = await fetch('http://127.0.0.1:8000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify(this.user),
        })

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Error en el registro')
        }

        console.log('✅ Registro OK:', data)

        // Guardar token si viene
        // if (data.access_token) {
        //   localStorage.setItem('auth_token', data.access_token)
        //   localStorage.setItem('auth_user', JSON.stringify(data.user))
        // }

        this.success = 'Registro exitoso '

        // Redirigir al login o dashboard
        setTimeout(() => {
          this.$router.push('/login')
        }, 1000)
      } catch (error) {
        console.error(' Error:', error)
      
        this.error = error.message || 'Error de conexión con el servidor'
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
/* Fondo general */

.register-container::before {
  content: '';

  position: absolute;
  inset: 0;

  background:
    radial-gradient(
      circle at top left,
      rgba(255,255,255,0.15),
      transparent 40%
    );

  pointer-events: none;
}

.register-container {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #6d97d6, #07173f);
  position: relative;
  overflow: hidden;
}



/* Tarjeta */
.register-card {
  background: #d9d9d9;

  backdrop-filter: blur(10px);

  transition:
    transform 0.35s ease,
    box-shadow 0.35s ease;
  padding: 35px;
  border-radius: 12px;
  width: 400px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.register-card:hover {
  transform: translateY(-8px);

  box-shadow:
    0 25px 45px rgba(0,0,0,0.55);
}

/* Título */
.register-card h2 {
  text-align: center;
  margin-bottom: 10px;
    font-family: Poppins;

}

/* Inputs */
.form-group {
  display: flex;
  flex-direction: column;
  font-family: Poppins;
  font-weight: 550;

}

.form-group label {
  font-size: 14px;
  margin-bottom: 5px;
  font-family: Poppins;
}

.form-group input {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  transition: 0.2s;
  font-weight: 400;
  font-family: Poppins;
}

.form-group input:focus {
  border-color: #156cef;
  outline: none;
}

/* Botones */
.btn {
  padding: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.2s;
}

.btn.primary {
  background-color: #156cef;
  color: white;
}

.btn.primary:hover {
  background-color: #6d97d6;
}

.btn.secondary {
  background-color: #f0f0f0;
}

.btn.secondary:hover {
  background-color: #a6aaaf;
}

/* Mensajes */
.error {
  color: red;
  font-size: 14px;
  text-align: center;
}

.success {
  color: green;
  font-size: 14px;
  text-align: center;
}
</style>