<template>
  <footer class="footer">

    <div class="footer-content">

      <div class="footer-left">
        <img
          src="/logo.png"
          alt="Logo"
          class="footer-logo"
        />

        <span class="footer-title">
          rojects
        </span>
      </div>

      <div class="footer-right">
        <span>
          © 2026 Projects App
        </span>

        <span class="footer-separator">
          •
        </span>

        <span>
          Desarrollado por Jordi
        </span>
      </div>

    </div>

  </footer>
</template>

<script>
export default {
  name: 'FooterComponent'
}
</script>

<style scoped>

.footer {
  width: 100%;
  background: #07173f;
  backdrop-filter: none;
  margin-top: 0px;
  box-shadow: none;
  font-family: Poppins;
  
}

.footer-content {
  max-width: 1600px;
  margin: auto;
  padding: 18px 35px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  
}

/* IZQUIERDA */

.footer-left {

  display: flex;
  align-items: center;
  gap: 10px;
}

.footer-logo {
  width: 34px;
  height: 34px;
  object-fit: contain;
}

.footer-title {
  margin-top: 10px;
  margin-left: -5px;
  font-size: 24px;
  font-weight: 700;
  color: rgb(183, 183, 183);
}

/* DERECHA */

.footer-right {

  display: flex;
  align-items: center;
  gap: 10px;
  color: rgb(183, 183, 183);
  font-size: 14px;
  font-weight: 500;
}

.footer-separator {
  opacity: 0.6;
}

/* RESPONSIVE */

@media (max-width: 768px) {

  .footer-content {

    flex-direction: column;
    justify-content: center;
    text-align: center;
    padding: 20px;
  }

  .footer-right {

    flex-wrap: wrap;
    justify-content: center;
  }

  .footer-title {
    font-size: 20px;
  }
}

</style>