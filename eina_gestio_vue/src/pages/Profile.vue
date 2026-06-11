<template>
  <div class="profile-page">

    <div class="profile-container">

      <!-- BACK -->
      <button
        class="back-btn"
        @click="$router.back()"
      >
        ⬅ Volver
      </button>

      <!-- CARD -->
      <div class="profile-card">

        <h1>
          Mi Perfil
        </h1>

        <!-- EMAIL -->
        <div class="form-group">

          <label>Correo</label>

          <input
            :value="user.email"
            disabled
          />

        </div>

        <!-- USERNAME -->
        <div class="form-group">

          <label>Nombre de usuario</label>

          <input
            v-model="form.name"
            type="text"
          />

        </div>

        <!-- PASSWORD -->
        <div class="form-group">

          <label>Nueva contraseña</label>

          <input
            v-model="form.password"
            type="password"
            placeholder="••••••••"
          />

        </div>

        <!-- CONFIRM -->
        <div class="form-group">

          <label>Confirmar contraseña</label>

          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="••••••••"
          />

        </div>

        <!-- ERROR -->
        <p
          v-if="error"
          class="error"
        >
          {{ error }}
        </p>

        <!-- SUCCESS -->
        <p
          v-if="success"
          class="success"
        >
          {{ success }}
        </p>

        <!-- SAVE -->
        <button
          class="save-btn"
          @click="updateProfile"
        >
          Guardar cambios
        </button>

      </div>

    </div>

    <div
    v-if="showError"
    class="error-popup"
    >
    {{ errorMessage }}
    </div>

  </div>

</template>

<script>

import { API_URL } from '@/config'

export default {

  name: 'ProfilePage',

  data() {

    return {

      user: JSON.parse(
        localStorage.getItem('auth_user')
      ),

      form: {
        name: '',
        password: '',
        password_confirmation: '',
      },

      error: '',
      success: '',
      errorMessage: '',
      showError: false,
    }
  },

  mounted() {

    this.form.name = this.user.name
  },

  methods: {

    async updateProfile() {

      this.error = ''
      this.success = ''

      try {

        const response = await fetch(`${API_URL}/profile`, {
            method: 'PUT',

            headers: {
              'Content-Type': 'application/json',
              Authorization:
                `Bearer ${localStorage.getItem('auth_token')}`
            },

            body: JSON.stringify(this.form)
          }
        )

        const data = await response.json()

        if (!response.ok) {

        throw {

            message:
            data.message || 'Error',

            response: {
            data
            }
        }
        }

        localStorage.setItem(
          'auth_user',
          JSON.stringify(data.user)
        )

        window.dispatchEvent(
          new Event('auth-changed')
        )

        this.success =
          'Perfil actualizado correctamente'

        this.form.password = ''
        this.form.password_confirmation = ''

      } catch (error) {

            const errorData =
                error.response?.data

            if (errorData?.errors?.name) {

                this.showErrorPopup(
                'Nombre de usuario en uso'
                )

            } else {

                this.showErrorPopup(
                error.message || 'Error'
                )
            }
        }
    },

    showErrorPopup(message) {

        this.errorMessage = message
        this.showError = true

        setTimeout(() => {

            this.showError = false

        }, 3000)
    }

  }
}
</script>

<style scoped>

.profile-page {

  min-height: 100vh;
  padding: 50px;
  font-family: Poppins;
  background: linear-gradient(180deg, #6d97d6, #07173f);
}

.profile-container {

  max-width: 700px;

  margin: auto;
}

.back-btn {

  background: transparent;

  border: none;

  color: white;

  font-size: 18px;

  font-weight: 600;

  margin-bottom: 20px;

  cursor: pointer;
}

.profile-card {

  background: rgba(217,217,217,0.92);

  backdrop-filter: blur(10px);

  padding: 40px;

  border-radius: 20px;

  box-shadow:
    0 15px 40px rgba(0,0,0,0.3);

  display: flex;

  flex-direction: column;

  gap: 20px;
}

.profile-card h1 {

  margin: 0;

  font-size: 40px;
}

.form-group {

  display: flex;

  flex-direction: column;

  gap: 8px;
}

.form-group label {

  font-weight: 600;
}

.form-group input {

  padding: 12px;

  border-radius: 10px;

  border: none;

  font-family: Poppins;
}

.save-btn {

  border: none;

  background: #156cef;

  color: white;

  padding: 14px;

  border-radius: 12px;

  font-weight: 700;

  cursor: pointer;

  transition: 0.25s;
}

.save-btn:hover {

  transform: translateY(-2px);

  background: #0f5cd1;
}

.error {

  color: red;

  font-weight: 600;
}

.success {

  color: green;

  font-weight: 600;
}

.error-popup {

  position: fixed;

  bottom: 30px;
  right: 30px;

  background: #ef4444;

  color: white;

  padding: 14px 20px;

  border-radius: 12px;

  font-family: Poppins;

  font-weight: 600;

  box-shadow:
    0 8px 25px rgba(0,0,0,0.25);

  z-index: 999999;

  animation:
    popupFade 0.3s ease;
}

@keyframes popupFade {

  from {

    opacity: 0;

    transform: translateY(15px);
  }

  to {

    opacity: 1;

    transform: translateY(0);
  }
}

/* MOBILE */

@media (max-width: 768px) {

  .profile-page {

    padding: 20px;
  }

  .profile-card {

    padding: 25px;
  }

  .profile-card h1 {

    font-size: 30px;
  }
}

</style>