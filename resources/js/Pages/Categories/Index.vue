<script setup>
import { router, Link, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { IconDots, IconCategory } from "@tabler/icons-vue";

const page = usePage()

const props = defineProps({
    categories: Object,
});

const destroy = (categoryId) => {
    router.delete(route("categories.destroy", { category: categoryId }), {
        preserveScroll: true,
        onBefore: () => confirm('Are you sure you want to delete this category?'),
        onSuccess: () => new FilamentNotification()
            .title(page.props.flash.messageTitle)
            .success()
            .body(page.props.flash.messageBody)
            .send(),
        onError: () => new FilamentNotification()
            .title(page.props.errors.messageTitle)
            .danger()
            .body(page.props.errors.messageBody)
            .send(),
    });
};
</script>

<template>
    <template v-if="$page.props.auth.user">
        <AppLayout title="Categories">
            <template #header>
                <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                    <h2
                        class="font-semibold text-xl flex-grow flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4">
                        Categories
                    </h2>

                    <Link
                        class="font-semibold text-xl flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4"
                        :href="route('categories.create')" :active="route().current('categories.create')">
                    Create Category
                    </Link>
                </div>
            </template>

            <div v-if="categories" class="pt-16 pb-8 border-t sm:block">
                <div v-for="category in categories" :key="category.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-1xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-4 md:p-12 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6 space-y-12">
                                <div class="text-xl font-semibold text-white dark:text-black">
                                    {{ category.name }}
                                </div>

                                <div v-if="category.subCategories && category.subCategories.length > 0">
                                    <span class="items-center text-sm font-semibold text-white dark:text-black">
                                        <IconCategory class="inline-block mr-1" :size="18" />
                                        <template v-for="subCategory in category.subCategories" :key="subCategory.id">
                                            <div
                                                class="inline-block mb-2 bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                {{ subCategory.name }}
                                            </div>
                                        </template>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex relative">
                        <div class="fixed top-2 right-2 flex rounded-full hover:bg-indigo-500 focus:bg-indigo-900">
                            <div class="relative flex items-center">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex text-sm rounded-full dark:text-black text-white focus:outline-none transition">
                                            <IconDots :size="40" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="block px-4 py-2 text-xs text-zinc-500 dark:text-zinc-500">
                                            Manage Category
                                        </div>

                                        <DropdownLink :href="route('categories.edit', { category: category.id })">
                                            Edit
                                        </DropdownLink>

                                        <div class="border-t border-white dark:border-gray-900" />

                                        <form @submit.prevent="destroy(category.id)">
                                            <DropdownLink as="button">
                                                Delete
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="hidden sm:block">
                <p class="flex mx-auto items-center justify-center min-h-screen">
                    No categories found :/
                </p>
            </div>
        </AppLayout>
    </template>

    <template v-else>
        <GuestLayout title="Categories">
            <template #header>
                <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                    <h2
                        class="font-semibold text-xl flex-grow flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4">
                        Categories
                    </h2>

                    <Link
                        class="font-semibold text-xl flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4"
                        :href="route('categories.create')" :active="route().current('categories.create')">
                    Create Category
                    </Link>
                </div>
            </template>

            <div v-if="categories" class="pt-16 pb-8 border-t sm:block">
                <div v-for="category in categories" :key="category.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex p-4 md:p-12 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6 space-y-12">
                                <div class="text-xl font-semibold text-white dark:text-black">
                                    {{ category.name }}
                                </div>

                                <div v-if="category.subCategories && category.subCategories.length > 0">
                                    <span class="items-center text-sm font-semibold text-white dark:text-black">
                                        <IconCategory class="inline-block mr-1" :size="18" />
                                        <template v-for="subCategory in category.subCategories" :key="subCategory.id">
                                            <div
                                                class="inline-block mb-2 bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                {{ subCategory.name }}
                                            </div>
                                        </template>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="hidden sm:block">
                <p class="flex mx-auto items-center justify-center min-h-screen">
                    No categories found :/
                </p>
            </div>
        </GuestLayout>
    </template>
</template>

<style scoped></style>
