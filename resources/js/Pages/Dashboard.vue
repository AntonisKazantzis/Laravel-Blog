<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">Dashboard</h2>
            </div>
        </template>

        <div class="py-12 border-t">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"></div>
            </div>
        </div>
    </AppLayout>
</template>
