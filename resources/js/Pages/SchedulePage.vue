<script setup>
import ScheduleDishes from "@/Components/SchedulePage/ScheduleDishes.vue";
import ScheduleGrid from "@/Components/SchedulePage/ScheduleGrid.vue";
import {Head} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    dishes: null,
    types: null,
    scheduleData: null
})
</script>

<template>
    <MainLayout>
        <Head title="Расписание"/>

        <div class="schedule">
            <ScheduleGrid :types="types" :scheduleItems="scheduleData"/>

            <ScheduleDishes :items="dishes"/>
        </div>

    </MainLayout>

</template>

<style scoped>

</style>
