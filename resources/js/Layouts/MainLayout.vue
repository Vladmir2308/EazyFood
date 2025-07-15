<script setup>
import TheHeader from "@/Partials/TheHeader.vue";
import TheFooter from "@/Partials/TheFooter.vue";
import TheSideBar from "@/Partials/TheSideBar.vue";
</script>

<template>
    <TheSideBar />

    <TheHeader />

    <main>
        <slot></slot>
    </main>

</template>

<style scoped>

</style>
