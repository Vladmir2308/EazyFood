<script setup>
import TheHeader from "@/Partials/TheHeader.vue";
import TheFooter from "@/Partials/TheFooter.vue";
</script>

<template>
    <TheHeader />

    <main>
        <slot></slot>
    </main>

    <TheFooter />
</template>

<style scoped>

</style>
