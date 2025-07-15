<script setup>
defineProps({
    title: null,
    inputId: null
})
</script>

<template>
    <label class="input-standart__label" :for="inputId">{{ title }}</label>
</template>

<style scoped>

</style>
