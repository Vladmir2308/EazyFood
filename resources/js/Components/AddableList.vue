<script setup>
defineProps({
    visible: Boolean,
    headerTitle: String,
    submitBtnStatus: Boolean
})

const emit = defineEmits(['closeAddableList', 'sendDishData'])
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="addable__list-bg" @click.self="emit('closeAddableList')">
            <div class="addable__list">
                <div class="addable__list-header">
                    {{ headerTitle }}
                </div>

                <div class="addable__list-content">
                    <slot />
                </div>

                <div class="addable__list-buttons">
                    <button class="btn"
                            @click="emit('sendDishData')"
                            :disabled="submitBtnStatus"
                            :class="{'btn--submited': submitBtnStatus}"
                    >Сохранить</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Переход фейда + слайда */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.fade-enter-from .addable__list {
    transform: translateX(100%);
}

.fade-enter-to .addable__list {
    transform: translateX(0%);
}

.fade-leave-from .addable__list {
    transform: translateX(0%);
}

.fade-leave-to .addable__list {
    transform: translateX(100%);
}
</style>
