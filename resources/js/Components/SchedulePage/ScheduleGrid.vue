<script setup>
import draggable from 'vuedraggable'
import {computed, onMounted, reactive, ref, watch} from "vue";
import {getCurrentWeekDates} from "@/functions.js";
import {useForm} from "@inertiajs/vue3";
import DishNumberCircle from "@/Components/SchedulePage/DishNumberCircle.vue";
import {debounce} from "lodash";

const props = defineProps({
    types: null,
    scheduleItems: null
})


const days = getCurrentWeekDates();
const currentDate = new Date().toISOString().slice(0, 10)
const mealTypes = props.types

const items = ref(props.scheduleItems ?? []);

const gridData = computed(() => {
    const byCell = new Map();
    for (const it of (items.value ?? [])) {
        const date = (it.date ?? '').slice(0, 10);
        const typeId = it.type_id ?? it.type?.id;
        const key = `${date}|${typeId}`;
        if (!byCell.has(key)) byCell.set(key, []);
        byCell.get(key).push(it);
    }
    return days.map(d => mealTypes.map(mt => byCell.get(`${d.date}|${mt.id}`) ?? []));
});

/* Add Dish in Grid*/
const putDishInGrid = (date, meal_id, event) => {
    const newDish = event.item.__draggable_context.element
    const currentGridData = useForm({
        dish_id: newDish.id,
        date: date,
        type_id: meal_id
    })

    currentGridData.post(route('schedule.store'))
}
/* ... */

/* Delete Dish From Grid */
const deleteDishFromGrid = debounce((elementId) => {
    const currentElementId = useForm({
        id: elementId
    })

    currentElementId.delete(route('schedule.delete'), {
        onSuccess: () => {
            items.value = items.value.filter(it => it.id !== elementId); // сетка пересчитается сама
        }
    })
}, 200)
/* ... */


/* Hover on Grid Item */
const dishInfoModal = reactive({
    dishId: null,
    status: false
})

/* ... */

const startDragElement = () => {
}
const onHoverInGridElement = (element) => {
    dishInfoModal.dishId = element.id
    dishInfoModal.status = true
}

const onLeaveInGridElement = () => {
    dishInfoModal.status = false
}

watch(() => props.scheduleItems, v => { items.value = v ?? [] }, { deep: true });
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
                        :group="{name: 'meals', pull: false}"
                        :draggable="false"
                        :sort="false"
                        item-key="id"
                        class="cell"
                        @add="(event) => putDishInGrid(day.date, meal.id, event)"
                    >
                        <template #item="{ element }">
                            <div class="schedule-grid__item">
                                <DishNumberCircle :dish="element"
                                                  @dblclick="deleteDishFromGrid(element.id)"
                                                  @mouseenter="onHoverInGridElement(element)"
                                                  @mouseleave="onLeaveInGridElement"
                                />


                                <div class="schedule-grid__item-modal"
                                     v-if="dishInfoModal.dishId === element.id && dishInfoModal.status"
                                >
                                    <div class="schedule-grid__item-modal__title">{{ element.dish.name }}</div>

                                    <div class="schedule-grid__item-modal__items">
                                        <div class="schedule-grid__item-modal__item"
                                             v-for="item in element.products"
                                             :key="item.id">
                                            <div class="schedule-grid__item-modal__item-name">{{ item.name }}</div>
                                            <div class="schedule-grid__item-modal__item-amount">{{ item.pivot.amount}} {{ item.pivot.unit }}.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </template>
                    </draggable>
                </template>
            </template>
        </div>

    </div>
</template>

<style scoped>

</style>
