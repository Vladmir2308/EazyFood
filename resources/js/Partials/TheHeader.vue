<script setup>
import {Link} from "@inertiajs/vue3";

</script>

<template>
    <header>
        <div class="header__inner">
            <div class="header-profile">
                <div class="btn pointer-events-none">{{ $page.props.auth.user.name }}</div>
                <Link class="btn btn--btn__gray"
                      :href="route('logout')" method="post" type="button">Выйти</Link>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>
