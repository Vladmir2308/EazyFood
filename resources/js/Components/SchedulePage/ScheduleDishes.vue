<script setup>
import draggable from "vuedraggable";
import InputStandart from "@/Components/InputStandart.vue";
import {ref} from "vue";
import DishNumberCircle from "@/Components/SchedulePage/DishNumberCircle.vue";

const props = defineProps({
    items: null,
})

const dishes = ref(props.items)
const dishItemStatus = ref(true)



</script>

<template>
    <div class="schedule-dishes">
        <InputStandart model-value=""
                       type="search"
                       placeholder="Поиск по названию"/>

        <div class="filters"></div>


        <draggable class="schedule-dishes__list"
                   v-model="dishes"
                   :group="{ name: 'meals', pull: 'clone', put: false}"
                   @end="drag=false"
                   :sort="false"
                   item-key="id"
                   chosen-class="drag-chosen"

        >
            <template #item="{element}">
                <div>
                    <div class="schedule-dish__item" v-if="dishItemStatus">
                        <DishNumberCircle :dish="element"/>
                        <div class="schedule-dish__item-title">{{ element.name }}</div>
                    </div>
                </div>
            </template>
        </draggable>
    </div>
</template>

<style scoped>
.drag-chosen {
    opacity: 0.3;

    transition: opacity .3s linear;
}
</style>
