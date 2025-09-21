<script setup>
import CircleXmarkIcon from "@/Components/Svg/CircleXmarkIcon.vue";
import PencilIcon from "@/Components/Svg/PencilIcon.vue";
import IconButton from "@/Components/IconButton.vue";
import {inject} from "vue";

const props = defineProps({
    currentDish: null,
    dishColor: null,
    deleteProcess: null
})

let addableList = inject('currentDish')

const emit = defineEmits(['deleteItemFromMealList'])

const takeCurrentDish = () => {
    addableList.dishForUpdate = props.currentDish
    addableList.status = true
    addableList.method = 'update'
}

const eyeStatus = inject('eyeStatus')
</script>

<template>
    <div class="meal__list-dishes__item">
        <div class="meal__list-dishes__item-title">{{ currentDish.name }}</div>
        <div class="meal__list-dishes__item-number"
             :style="{ background: dishColor}"
        >
            {{ currentDish.display_number}}
        </div>

        <div class="meal__list-dishes__item-buttons">
            <IconButton @click="emit('deleteItemFromMealList', currentDish.id)"
                        :disabled="deleteProcess"
                        :icon="CircleXmarkIcon"
                        v-if="eyeStatus"
            />

            <IconButton @click="takeCurrentDish"
                        :icon="PencilIcon"
                        v-if="eyeStatus"
            />
        </div>


    </div>
</template>

<style scoped>

</style>
