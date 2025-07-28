<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AddableList from "@/Components/AddableList.vue";
import MainButton from "@/Components/MainButton.vue";
import {nextTick, onBeforeUnmount, onMounted, reactive, ref, watch} from "vue";
import { v4 as uuidv4 } from 'uuid'
import PlusIcon from "@/Components/Svg/PlusIcon.vue";
import FieldsStandartBlock from "@/Components/FieldsStandartBlock.vue";
import FieldsStandartBlockItem from "@/Components/FieldsStandartBlockItem.vue";
import SelectStandartField from "@/Components/SelectStandartField.vue";
import InputStandart from "@/Components/InputStandart.vue";
import {debounce} from "lodash";
import {onKeyUp} from "@vueuse/core";
import ListIcon from "@/Components/Svg/ListIcon.vue";
import SelectList from "@/Components/SelectList.vue";
import MealListByType from "@/Components/MealListByType.vue";
import EyeOpenIcon from "@/Components/Svg/EyeOpenIcon.vue";
import EyeCloseIcon from "@/Components/Svg/EyeCloseIcon.vue";

const props = defineProps({
    types: null,
    dishesByTypes: null,
    user_id: null
})

/* Addable List */
const addableList = ref(false)
const showAddableList = () => {
    addableList.value = true
    document.body.style.overflow = 'hidden'
}
/* ... */

/* Eyes */
const showEyeStatus = ref(false)
/* ... */
</script>

<template>
    <Head title="Блюда"/>

    <AddableList :visible="addableList"
                 @close-addable-list="addableList = false"
                 header-title="Добавь свое блюдо"
                 :types="types"
                 :user-id="user_id"
    />

    <div class="dish__header">
        <MainButton :icon="PlusIcon" title="Добавить" @click="showAddableList"/>
        <EyeOpenIcon class="eye-icon" v-if="showEyeStatus" @click="showEyeStatus = !showEyeStatus"/>
        <EyeCloseIcon class="eye-icon" v-if="!showEyeStatus" @click="showEyeStatus = !showEyeStatus"/>
    </div>

    <div class="dish__content">
        <MealListByType v-for="type in dishesByTypes"
                        :key="type.id"
                        :type="type.name"
                        :dishes="type.dishes"
                        :custom-class="`meal__list-type--color-${type.id}`"
                        :show-tools-status="showEyeStatus"
        />
    </div>

</template>

<style>
.fade-down-enter-active,
.fade-down-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.fade-down-enter-from,
.fade-down-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}

.fade-down-enter-to,
.fade-down-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
