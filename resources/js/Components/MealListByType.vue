<script setup>
import CircleXmarkIcon from "@/Components/Svg/CircleXmarkIcon.vue";
import {useForm} from "@inertiajs/vue3";

defineProps({
    type: null,
    dishes: null,
    customClass: null,
    showToolsStatus: null,
})

const deleteDishOnId = (dishId) => {
    const dish = useForm({
        id: dishId
    })

    dish.delete(route('dish.delete'))
}
</script>

<template>
    <div class="meal__list">
        <div :class="'meal__list-type ' + customClass">{{ type }}</div>

        <div class="meal__list-dishes" v-auto-animate>
            <div class="meal__list-dishes__item"
                 v-for="dish in dishes"
                 :key="dish.id"
            >
                <div class="meal__list-dishes__item-title">{{ dish.name }}</div>

                <CircleXmarkIcon class="circle-xmark-icon" @click="deleteDishOnId(dish.id)" v-if="showToolsStatus"/>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
