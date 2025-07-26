<script setup>
defineProps({
    items: null
})

defineEmits(['sendSuggestionItem'])
</script>

<template>
    <ul
        class="fields-product__suggestions"
    >
        <li class="fields-product__suggestions-item"
            v-for="item in items"
            :key="item.id"
            @click="$emit('sendSuggestionItem', item.name)"
        >
            {{ item.name }}
        </li>
    </ul>
</template>

<style scoped>

</style>
