<script setup>
defineProps({
    title: null
})
</script>

<template>
    <div class="fields-standart__block">
        <h2 v-if="title" class="fields-standart__block-title">{{ title }}</h2>
        <slot />
    </div>
</template>

<style scoped>

</style>
