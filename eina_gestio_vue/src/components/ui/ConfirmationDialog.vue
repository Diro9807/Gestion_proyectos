<template>

    <transition name="fade">

        <div
            v-if="show"
            class="dialog-overlay"
        >

            <div class="dialog">

                <div class="dialog-icon">
                    ⚠️
                </div>

                <h2 class="dialog-title">
                    {{ title }}
                </h2>

                <p class="dialog-message">
                    {{ message }}
                </p>

                <div class="dialog-buttons">

                    <button
                        class="cancel-btn"
                        @click="$emit('cancel')"
                    >
                        Cancelar
                    </button>

                    <button
                        class="confirm-btn"
                        @click="$emit('confirm')"
                    >
                        Confirmar
                    </button>

                </div>

            </div>

        </div>

    </transition>

</template>

<script>

export default {

    name: 'ConfirmationDialog',

    props: {

        show: Boolean,

        title: String,

        message: String

    },

    emits: [

        'confirm',
        'cancel'

    ]

}

</script>

<style scoped>

/* Fondo oscuro */

.dialog-overlay{

    position:fixed;

    inset:0;

    background:rgba(0,0,0,.45);

    display:flex;

    justify-content:center;

    align-items:center;

    z-index:999999;

    backdrop-filter:blur(4px);

}

/* Ventana */

.dialog{

    width:420px;

    max-width:90%;

    background:white;

    border-radius:18px;

    padding:35px;

    text-align:center;

    box-shadow:0 25px 60px rgba(0,0,0,.35);

}

/* Icono */

.dialog-icon{

    font-size:48px;

    margin-bottom:15px;

}

.dialog-title{

    font-family:Poppins;

    margin-bottom:15px;

    color:#1f2937;

}

.dialog-message{

    font-family:Poppins;

    color:#6b7280;

    line-height:1.6;

}

/* Botones */

.dialog-buttons{

    display:flex;

    justify-content:center;

    gap:15px;

    margin-top:30px;

}

.cancel-btn,
.confirm-btn{

    border:none;

    padding:12px 22px;

    border-radius:10px;

    cursor:pointer;

    font-family:Poppins;

    font-weight:600;

    transition:.2s;

}

.cancel-btn{

    background:#d1d5db;

}

.cancel-btn:hover{

    background:#bfc5cd;

}

.confirm-btn{

    background:#ef4444;

    color:white;

}

.confirm-btn:hover{

    background:#dc2626;

}

/* Animación */

.fade-enter-active,
.fade-leave-active{

    transition:.2s;

}

.fade-enter-from,
.fade-leave-to{

    opacity:0;

}

.fade-enter-from .dialog,
.fade-leave-to .dialog{

    transform:scale(.9);

}

</style>