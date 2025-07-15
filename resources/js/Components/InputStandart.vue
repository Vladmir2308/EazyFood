<script setup>
defineProps({
    type: null,
    placeholder: null,
    customClass: null,
})

const model = defineModel({
    type: null,
    required: true
})
</script>

<template>
    <input :class="'input__standart ' + customClass"
           :type="type"
           :placeholder="placeholder"
           v-model="model"
    >
</template>

<style scoped>

</style>
