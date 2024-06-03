<script setup>
import { Link } from "@inertiajs/vue3";

defineProps(["elements"]);
</script>

<template>
    <div
        id="pagination"
        class="w-full flex justify-center items-center mx-auto mt-16 pb-8"
    >
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in elements.links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-zinc-500 border-white dark:border-black border rounded"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-indigo-500 border-white dark:border-black text-white dark:text-black"
                    :class="{ 'bg-indigo-500 text-white dark:text-black': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
