<script setup>
const imgUrl = new URL('@public/img/xcelerate-logo-transparent.png', import.meta.url).href


defineProps({
  darkColor: {
    type: String,
    default: 'rgba(var(--color-primary-500), var(--tw-text-opacity))',
  },
  lightColor: {
    type: String,
    default: 'rgba(var(--color-primary-400), var(--tw-text-opacity))',
  },
})
</script>
<template>
        <div class="universal-logo">
                <img id="logo-img-url" :src="imgUrl">
        </div>
</template>
