<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-black dark:bg-indigo-50 focus:outline-none focus:text-indigo-900 focus:border-indigo-900 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white dark:text-black hover:text-indigo-500 dark:hover:text-indigo-500 dark:text-black hover:bg-zinc-700 hover:border-indigo-500 dark:border-white dark:hover:border-indigo-500 dark:border-white focus:outline-none focus:text-indigo-900 dark:text-black focus:border-indigo dark:border-white transition duration-150 ease-in-out';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-start">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
