<script setup>
import {Head} from "@inertiajs/vue3";
import AddableList from "@/Components/AddableList.vue";
import MainButton from "@/Components/MainButton.vue";
import {reactive, ref, provide} from "vue";
import PlusIcon from "@/Components/Svg/PlusIcon.vue";
import MealListByType from "@/Components/MealListByType.vue";
import EyeOpenIcon from "@/Components/Svg/EyeOpenIcon.vue";
import EyeCloseIcon from "@/Components/Svg/EyeCloseIcon.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    types: null,
    dishesByTypes: null,
    user_id: null
})


/* Addable List */
const addableList = reactive({
    status: false,
    dishForUpdate: null,
    method: null
})

provide('currentDish', addableList)
const showAddableList = (method) => {
    addableList.status = true
    addableList.method = method
    document.body.style.overflow = 'hidden'
}
/* ... */

/* Eyes */
const showEyeStatus = ref(false)
/* ... */
</script>

<template>
    <MainLayout>
        <div class="dish">
            <Head title="Блюда"/>

            <AddableList :visible="addableList.status"
                         @close-addable-list="addableList.status = false"
                         header-title="Добавь свое блюдо"
                         :types="types"
                         :user-id="user_id"
                         :updated-dish="addableList.dishForUpdate"
                         :method="addableList.method"
            />

            <div class="dish__header">
                <MainButton :icon="PlusIcon" title="Добавить" @click="showAddableList('create')"/>
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
                                :dish-color="type.color"
                />
            </div>
        </div>
    </MainLayout>
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
