<script setup>
import InputStandart from "@/Components/InputStandart.vue";

const props = defineProps({
    items: null,
})

console.log(props.items)
</script>

<template>
    <div class="schedule-dishes">
        <InputStandart model-value=""
                       type="search"
                       placeholder="Поиск по названию"/>

        <div class="filters"></div>

        <div class="schedule-dishes__list">
            <div class="schedule-dish__item"
                 v-for="item in items"
                 :key="item.id"
            >
                <div class="schedule-dish__item-number"
                     :style="{ background: item.type.color }"
                >{{ item.display_number }}</div>
                <div class="schedule-dish__item-title">{{ item.name }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
