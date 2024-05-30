<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <template v-if="$page.props.auth.user">
        <AppLayout title="Welcome">
            <template #header>
                <div class="flex justify-between text-white dark:text-black p-[10px]">
                    <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">Welcome</h2>
                </div>
            </template>

            <div class="py-12 border-t">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"></div>
                </div>
            </div>
        </AppLayout>
    </template>

    <template v-else>
        <GuestLayout title="Welcome">
            <template #header>
                <div class="flex justify-between text-white dark:text-black p-[10px]">
                    <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">Welcome</h2>
                </div>
            </template>

            <div class="py-12 border-t">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"></div>
                </div>
            </div>
        </GuestLayout>
    </template>
</template>

<style scoped></style>
