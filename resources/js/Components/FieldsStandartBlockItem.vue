<script setup>
defineProps({
    title: null,
    dishName: null,
    errorStatus: null
})

const model = defineModel({
    type: null,
    required: true
})
</script>

<template>
    <div class="flex flex-col">
        <div class="fields-standart__block-item">
            <label class="input-standart__label" for="standartInputField">{{ title }}</label>
            <input class="input__standart" type="text" id="standartInputField"
                   v-model="model"
                   :class="{'border-red-600' : errorStatus}"
            >
        </div>
    </div>
</template>

<style scoped>

</style>
