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
            maxlength="20"
          />

        </div>

        <!-- PASSWORD -->
        <div class="form-group">

          <label>Nueva contraseña</label>

          <input
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            maxlength="15"
          />

        </div>

        <!-- CONFIRM -->
        <div class="form-group">

          <label>Confirmar contraseña</label>

          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="••••••••"
            maxlength="15"
          />

        </div>

        

        <!-- SAVE -->
        <button
          class="save-btn"
          @click="updateProfile"
          :disabled="saving"
        >
          {{ saving ? 'Guardando...' : 'Guardar cambios' }}
      </button>

      </div>

    </div>

    <Popup
      :show="showPopup"
      :message="popupMessage"
      :type="popupType"
    />

  </div>

</template>

<script>

import { API_URL } from '@/config'
import Popup from '@/components/ui/Popup.vue'

export default {

  name: 'ProfilePage',

  components: {
    Popup
  },

  data() {

    return {

      user: JSON.parse(
        localStorage.getItem('auth_user') || '{}'
      ),

      form: {
        name: '',
        password: '',
        password_confirmation: '',
      },

      

      // POPUP
      showPopup: false,
      popupMessage: '',
      popupType: 'success',
      saving: false,
    }
  },

  mounted() {

    this.form.name = this.user.name || ''
  },

  methods: {

    async updateProfile() {

      if (this.saving) return

      const token = localStorage.getItem('auth_token')

      if (!token) {
          this.$router.push('/login')
          return
      }

      this.saving = true

      try {

        const response = await fetch(`${API_URL}/profile`, {
            method: 'PUT',

            headers: {
              'Content-Type': 'application/json',
              Authorization: `Bearer ${token}`
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

        this.showPopupMessage(
          'Perfil actualizado correctamente',
          'success'
        )

        this.form.password = ''
        this.form.password_confirmation = ''

      } catch (error) {

        const errorData =
          error.response?.data

        if (errorData?.errors?.name) {

          this.showPopupMessage(
            'Nombre de usuario en uso',
            'error'
          )

        } else {

          this.showPopupMessage(
            error.message || 'Error',
            'error'
          )

        }        

      } finally {

        this.saving = false

      }
    },
///////////////////////////////////////////////////////////////////////////////
    showPopupMessage(message, type = 'success') {

      this.popupMessage = message
      this.popupType = type
      this.showPopup = true

      setTimeout(() => {

        this.showPopup = false

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