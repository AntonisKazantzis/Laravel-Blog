<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    as: String,
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-white dark:text-black hover:bg-indigo-500  dark:hover:bg-indigo-500 focus:outline-none focus:bg-indigo-900 dark:focus:bg-indigo-900 transition duration-150 ease-in-out">
            <slot />
        </button>

        <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm leading-5 text-white dark:text-black hover:bg-indigo-500  dark:hover:bg-indigo-500 focus:outline-none focus:bg-indigo-900 dark:focus:bg-indigo-900 transition duration-150 ease-in-out">
            <slot />
        </a>

        <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-white dark:text-black hover:bg-indigo-500  dark:hover:bg-indigo-500 focus:outline-none focus:bg-indigo-900 dark:focus:bg-indigo-900 transition duration-150 ease-in-out">
            <slot />
        </Link>
    </div>
</template>
