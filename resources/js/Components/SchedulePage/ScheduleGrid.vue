<script setup>
import draggable from 'vuedraggable'
import {reactive, ref} from "vue";

const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']
const mealTypes = ['Завтрак', 'Обед', 'Ужин']

const gridData = ref(
    days.map(() => mealTypes.map(() => []))
)

console.log(gridData.value)
</script>

<template>
    <div class="schedule-grid">
        <div class="schedule-grid__inner">
            <!-- Верхний левый угол пустой -->
            <div class="header-cell empty"></div>
            <!-- Горизонтальные заголовки -->
            <div class="header-cell" v-for="meal in mealTypes" :key="meal">{{ meal }}</div>

            <template v-for="(day, dayIndex) in days" :key="day">
                <div class="day-cell">{{ day }}</div>

                <template v-for="(meal, mealIndex) in mealTypes" :key="meal">
                    <draggable
                        :list="gridData[dayIndex][mealIndex]"
                        group="meals"
                        item-key="id"
                        class="cell"
                    >
                        <template #item="{ element }">
                            <div class="dish">{{ element.name }}</div>
                        </template>
                    </draggable>
                </template>
            </template>
        </div>

    </div>
</template>

<style scoped>

</style>
