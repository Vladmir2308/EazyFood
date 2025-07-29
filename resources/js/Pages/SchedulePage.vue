<script setup>
import ScheduleDishes from "@/Components/SchedulePage/ScheduleDishes.vue";
import ScheduleGrid from "@/Components/SchedulePage/ScheduleGrid.vue";

defineProps({
    dishes: null,
})
</script>

<template>
    <div class="schedule">
        <ScheduleGrid />

        <ScheduleDishes :items="dishes"/>
    </div>
</template>

<style scoped>

</style>
