<script setup>
import TheHeader from "@/Partials/TheHeader.vue";
import TheFooter from "@/Partials/TheFooter.vue";
import TheSideBar from "@/Partials/TheSideBar.vue";
</script>

<template>
    <TheSideBar />

    <TheHeader />

    <main class="main-content">
        <slot></slot>
    </main>

</template>

<style scoped>

</style>
