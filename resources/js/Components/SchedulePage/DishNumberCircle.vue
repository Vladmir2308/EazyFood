<script setup>
defineProps({
    dish: null
})
</script>

<template>
    <div class="schedule-dish__item-number"
         :style="{ background: dish.color }"
    >{{ dish.display_number }}</div>
</template>

<style scoped>

</style>
