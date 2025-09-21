<script setup>
import draggable from "vuedraggable";
import InputStandart from "@/Components/InputStandart.vue";
import {ref, watch} from "vue";
import DishNumberCircle from "@/Components/SchedulePage/DishNumberCircle.vue";
import {debounce} from "lodash";

const props = defineProps({
    items: null,
})

console.log(props.items)

let dishes = ref(props.items)
const dishItemStatus = ref(true)

const searchDish = ref()

watch(searchDish, debounce(async () => {
    if(searchDish.value === ''){
        searchDish.value = ''
        return
    }

    const { data } = await axios.get(route('schedule.search.dishes'), {
        params: { q: searchDish.value}
    })

    console.log(data)
    dishes = data
}, 500))
</script>

<template>
    <div class="schedule-dishes">
        <InputStandart v-model="searchDish"
                       type="search"
                       placeholder="Поиск по названию"/>

        <div class="filters"></div>


        <draggable class="schedule-dishes__list" v-auto-animate
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
