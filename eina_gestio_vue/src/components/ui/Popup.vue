<template>

<div
    v-if="show"
    class="popup"
    :class="type"
>

    {{ message }}

</div>

</template>

<script>

export default{

    props:{

        show:Boolean,

        message:String,

        type:{
            type:String,
            default:"success"
        }

    }

}

</script>

<style scoped>

.popup{

    position:fixed;

    right:30px;
    bottom:30px;

    padding:15px 22px;

    border-radius:12px;

    color:white;

    font-family:Poppins;

    font-weight:600;

    box-shadow:0 10px 25px rgba(0,0,0,.25);

    animation:popupFade .25s ease;

    z-index:999999;

}

.success{

    background:#22c55e;

}

.error{

    background:#ef4444;

}

.warning{

    background:#f59e0b;

}

.info{

    background:#3b82f6;

}

@keyframes popupFade{

from{

    opacity:0;
    transform:translateY(20px);

}

to{

    opacity:1;
    transform:translateY(0);

}

}

</style>