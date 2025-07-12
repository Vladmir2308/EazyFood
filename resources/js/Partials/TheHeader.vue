<script setup>
import {Link} from "@inertiajs/vue3";

</script>

<template>
    <header class="w-full p-2 bg-green-300 text-black">
        <div class="container m-auto flex justify-between items-center">
            <img class="w-20" src="storage/img/logo.png" alt="Logo">

            <nav>
                <ul class="flex gap-10">
                    <li>
                        <Link :class="{ 'bg-white' : $page.component === 'MainPage' }" class="hover:bg-white rounded-lg p-2 transition" :href="route('main.index')">Главная</Link>
                    </li>

                    <li>
                        <Link :class="{ 'bg-white' : $page.component === 'DishPage' }" class="hover:bg-white rounded-lg p-2 transition" :href="route('dish.index')">Мои блюда</Link>
                    </li>

                    <li>
                        <Link :class="{ 'bg-white' : $page.component === 'SchedulePage' }" class="hover:bg-white rounded-lg p-2 transition" :href="route('main.index')">Расписание</Link>
                    </li>
                </ul>
            </nav>

            <div class="flex gap-2 items-center">
                <div class="p-2 bg-gray-800 rounded-md text-white">{{ $page.props.auth.user.name }}</div>
                <Link class="bg-white text-black p-2.5 rounded-xl hover:bg-gray-400 transition" :href="route('logout')" method="post" type="button">Выйти</Link>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>
