<script setup>
defineProps({
    icon: null
})
</script>

<template>
    <button>
        <component :is="icon" class="icon-button-svg"/>
    </button>
</template>

<style scoped>

</style>
