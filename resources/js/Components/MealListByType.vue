<script setup>
import CircleXmarkIcon from "@/Components/Svg/CircleXmarkIcon.vue";
import {useForm} from "@inertiajs/vue3";
import MealListItem from "@/Components/MealListItem.vue";

defineProps({
    type: null,
    dishes: null,
    customClass: null,
    showToolsStatus: null,
    dishColor: null,
})

const dishItem = useForm({
    id: null
})

const deleteDishOnId = (dishId) => {
    dishItem.id = dishId

    dishItem.delete(route('dish.delete'))
}
</script>

<template>
    <div class="meal__list">
        <div :class="'meal__list-type ' + customClass">{{ type }}</div>

        <div class="meal__list-dishes" v-auto-animate>
            <MealListItem v-for="dish in dishes"
                          :key="dish.id"
                          :current-dish="dish"
                          :dish-color="dishColor"
                          :delete-process="dishItem.processing"
                          @delete-item-from-meal-list="deleteDishOnId"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
