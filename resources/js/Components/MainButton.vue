<script setup>
defineProps({
    title: null,
    customClasses: null,
    icon: null
})
</script>

<template>
    <button :class="'btn ' + customClasses">
        <component :is="icon" class="btn-icon"/>

        {{ title }}
    </button>
</template>

<style scoped>

</style>
