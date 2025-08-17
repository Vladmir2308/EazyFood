<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import MainButton from "@/Components/MainButton.vue";
import {ref} from "vue";

const props = defineProps({
    basket: null,
    user: null
})

const sendMessage = () => {
    const basket = useForm({
        products: props.basket,
    })

    basket.post(route('telegram.send.message'))
}

</script>

<template>
    <Head title="Корзина"/>

    <MainLayout>
        <div class="basket">
            <a :href="'https://t.me/testvovanchichbot?start=' + user_id"
               v-if="!user.telegram_chat_id"
               target="_blank"
               rel="noopener noreferrer">
                <MainButton class="basket-send__button-tg"
                            title="Завести ТГ бота"

                />
            </a>

            <MainButton class="basket-send__button-tg"
                        v-else
                        title="Отправить в ТГ"
                        @click="sendMessage"
            />
            <div class="basket-inner">
                <div class="basket-item" v-for="(products, key) in basket" :key="key">
                    <div class="basket-item__category">{{ key }}</div>
                    <div class="basket-item__product" v-for="product in products" :key="product.id">
                        <div class="basket-item__product-name">{{ product.name }}</div>
                        <div class="basket-item__product-total">{{ product.total }}</div>
                        <div class="basket-item__product-unit">{{ product.unit }}</div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>

</style>
