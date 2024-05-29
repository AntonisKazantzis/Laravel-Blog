<script setup>
import SectionTitle from './SectionTitle.vue';
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="px-4 py-5 sm:p-6 bg-[#343541] dark:bg-white border border-[#333440] dark:border-white text-black dark:text-black sm:rounded-lg shadow-2xl">
                <slot name="content" />
            </div>
        </div>
    </div>
</template>
