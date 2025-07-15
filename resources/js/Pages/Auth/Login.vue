<script setup>
import Checkbox from '@/Components/Old/Checkbox.vue';
import InputError from '@/Components/Old/InputError.vue';
import InputLabel from '@/Components/Old/InputLabel.vue';
import PrimaryButton from '@/Components/Old/PrimaryButton.vue';
import TextInput from '@/Components/Old/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineOptions({
    layout: GuestLayout
})

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Почта" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Пароль" />

            <TextInput
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                autocomplete="current-password"
            />

            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 block">
            <label class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="ms-2 text-sm text-gray-600"
                    >Запомните твари</span
                >
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a
                target="_blank"
                v-if="canResetPassword"
                href="https://books.yandex.ru/reader/UpFfJGco?resource=book"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Забыл пароль?
            </a>

            <Link
                v-if="canResetPassword"
                :href="route('register')"
                class="ml-5 rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Нет профиля?
            </Link>

            <PrimaryButton
                class="ms-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Войти
            </PrimaryButton>
        </div>
    </form>
</template>
