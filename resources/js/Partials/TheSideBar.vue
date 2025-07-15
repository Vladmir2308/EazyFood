<script setup>
import {Link} from "@inertiajs/vue3";
import HouseIcon from "@/Components/Svg/HouseIcon.vue";
import DIshIcon from "@/Components/Svg/DIshIcon.vue";

const pages = {
    'MainPage': {
        icon: HouseIcon,
        title: 'Главная',
        routeLink: 'main.index',
    },
    'DishPage': {
        icon: DIshIcon,
        title: 'Блюда',
        routeLink: 'dish.index',
    },
}

</script>

<template>
    <div class="sidebar">
        <img src="storage/img/logo.png" alt="Logo" class="sidebar-logo">

        <nav class="sidebar-nav">
            <ul class="sidebar-nav__list">
                <li v-for="(page, key) in pages" :key="page"
                    class="sidebar-nav__list-item"
                    :class="{ 'active': $page.component === key }"
                >
                    <b class="sidebar-nav__list-item__corner"></b>
                    <b class="sidebar-nav__list-item__corner"></b>
                    <Link :href="route(page.routeLink)" class="sidebar-nav__list-link">
                            <component class="sidebar-svg"
                                       :is="page.icon"
                            />

                        <div class="sidebar-link__title">{{ page.title }}</div>
                    </Link>
                </li>
            </ul>
        </nav>


    </div>
</template>

<style>

</style>
