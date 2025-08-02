<script setup>
import draggable from "vuedraggable";
import InputStandart from "@/Components/InputStandart.vue";
import {ref} from "vue";

const props = defineProps({
    items: null,
})

const dishes = ref(props.items)

</script>

<template>
    <div class="schedule-dishes">
        <InputStandart model-value=""
                       type="search"
                       placeholder="Поиск по названию"/>

        <div class="filters"></div>


        <draggable class="schedule-dishes__list"
            v-model="dishes"
            group="meals"
            @start="drag=true"
            @end="drag=false"
            item-key="id">
            <template #item="{element}">
                <div class="schedule-dish__item">
                    <div class="schedule-dish__item-number"
                         :style="{ background: element.type.color }"
                    >{{ element.display_number }}</div>
                    <div class="schedule-dish__item-title">{{ element.name }}</div>
                </div>
            </template>
        </draggable>
    </div>
</template>

<style scoped>

</style>
