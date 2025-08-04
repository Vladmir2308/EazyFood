<script setup>
import draggable from 'vuedraggable'
import {reactive, ref} from "vue";
import {getCurrentWeekDates} from "@/functions.js";
import {useForm} from "@inertiajs/vue3";
import DishNumberCircle from "@/Components/SchedulePage/DishNumberCircle.vue";
import {map} from "lodash/collection.js";

const props = defineProps({
    types: null,
    scheduleItems: null
})

/* Add Dish in Grid*/
const days = getCurrentWeekDates();
const currentDate = new Date().toISOString().slice(0, 10)
const mealTypes = props.types

console.log(props.scheduleItems)

const gridData = reactive(
    days.map(() => mealTypes.map(() => []))
)

console.log(gridData)

const putDishInGrid = (date, meal_id, event) => {
    const newDish = event.item.__draggable_context.element
    const currentGridData = useForm({
        dish_id: newDish.id,
        date: date,
        meal_type_id: meal_id
    })

    console.log(currentGridData)
    currentGridData.post(route('schedule.store'))
}
/* ... */
</script>

<template>
    <div class="schedule-grid">
        <div class="schedule-grid__inner">
            <!-- Верхний левый угол пустой -->
            <div class="header-cell empty"></div>
            <!-- Горизонтальные заголовки -->
            <div class="header-cell" v-for="meal in mealTypes" :key="meal">{{ meal.name }}</div>

            <template v-for="(day, dayIndex) in days" :key="day">
                <div class="day-cell"
                     :class="{ 'active': day.date === currentDate }"
                >{{ day.name }}</div>

                <template v-for="(meal, mealIndex) in mealTypes" :key="meal">
                    <draggable
                        :list="gridData[dayIndex][mealIndex]"
                        group="meals"
                        item-key="id"
                        class="cell"
                        @add="(event) => putDishInGrid(day.date, meal.id, event)"
                    >
                        <template #item="{ element }">
                            <DishNumberCircle :dish="element"/>
                        </template>
                    </draggable>
                </template>
            </template>
        </div>

    </div>
</template>

<style scoped>

</style>
