<script setup>

defineProps({
    options: Array
})

const model = defineModel({
    type: null,
    required: true
})

</script>

<template>
    <select class="select-standart__field" v-model="model">
        <option v-for="option in options"
                :key="option.id"
                :value="option.id"
        >
            {{ option.name }}
        </option>
    </select>
</template>

<style scoped>

</style>
