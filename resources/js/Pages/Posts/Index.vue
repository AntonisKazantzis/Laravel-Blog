<script setup>
import { router, Link, usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { IconTags, IconCategory, IconDots } from "@tabler/icons-vue";

const page = usePage()

const props = defineProps({
    posts: Object,
});

const destroy = (postId) => {
    router.delete(route("posts.destroy", { post: postId }), {
        preserveScroll: true,
        preserveState: true,
        onBefore: () => confirm('Are you sure you want to delete this post?'),
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
        <AppLayout title="Posts">
            <template #header>
                <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                    <h2
                        class="font-semibold text-xl flex-grow flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4">
                        Posts
                    </h2>

                    <Link
                        class="font-semibold text-xl flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4"
                        :href="route('posts.create')" :active="route().current('posts.create')">
                    Create Post
                    </Link>
                </div>
            </template>

            <div v-if="posts.data" class="pt-16 pb-8 border-t sm:block">
                <div v-for="post in posts.data" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-3xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-4 md:p-12 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6">
                                <div
                                    class="flex items-center text-xl font-semibold text-white dark:text-black pb-4 md:pb-10">
                                    {{ post.title }}
                                </div>

                                <div v-if="post.image" class="flex items-center w-full h-full pb-4">
                                    <img class="rounded max-h-[280px]" :src="post.image" alt="" />
                                </div>

                                <div>
                                    <span v-html="post.body"
                                        class="block text-white dark:text-black break-words md:hidden"></span>
                                </div>
                            </div>

                            <div class="px-4 md:px-6 pt-4 md:pt-10">
                                <div class="space-y-4">
                                    <div>
                                        <span
                                            class="flex items-center text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.category.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <span
                                            class="flex items-center text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.subCategory.name }}
                                        </span>
                                    </div>

                                    <div v-if="post.tags && post.tags.length > 0">
                                        <span class="items-center text-sm font-semibold text-white dark:text-black">
                                            <IconTags class="inline-block mr-1" :size="18" />
                                            <template v-for="tag in post.tags" :key="tag.id">
                                                <div
                                                    class="inline-block mb-2 bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                    {{ tag.name }}
                                                </div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 relative">
                        <div class="justify-center items-center md:flex p-4 md:p-12 hidden md:display:block">
                            <span v-if="post.body" v-html="post.body"
                                class="block text-white dark:text-black break-words"></span>
                        </div>

                        <div class="fixed top-2 right-2 flex rounded-full hover:bg-indigo-500 dark:hover:bg-indigo-500 focus:bg-indigo-900 dark:focus:bg-indigo-900">
                            <div class="relative flex items-center">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex text-sm rounded-full focus:outline-none dark:text-black text-white transition">
                                            <IconDots :size="40" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="block px-4 py-2 text-xs text-zinc-500 dark:text-zinc-500">
                                            Manage Post
                                        </div>

                                        <DropdownLink :href="route('posts.edit', { post: post.id })">
                                            Edit
                                        </DropdownLink>

                                        <div class="border-t border-white dark:border-gray-900" />

                                        <form @submit.prevent="destroy(post.id)">
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

                <Pagination :elements="posts" />
            </div>

            <div v-else class="hidden sm:block">
                <p class="flex mx-auto items-center justify-center min-h-screen">
                    No posts found :/
                </p>
            </div>
        </AppLayout>
    </template>

    <template v-else>
        <GuestLayout title="Posts">
            <template #header>
                <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                    <h2
                        class="font-semibold text-xl flex-grow flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4">
                        Posts
                    </h2>

                    <Link
                        class="font-semibold text-xl flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4"
                        :href="route('posts.create')" :active="route().current('posts.create')">
                    Create Post
                    </Link>
                </div>
            </template>

            <div v-if="posts.data" class="pt-16 pb-8 border-t sm:block">
                <div v-for="post in posts.data" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-3xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-4 md:p-12 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6">
                                <div
                                    class="flex items-center text-xl font-semibold text-white dark:text-black pb-4 md:pb-10">
                                    {{ post.title }}
                                </div>

                                <div v-if="post.image" class="flex items-center w-full h-full pb-4">
                                    <img class="rounded max-h-[280px]" :src="post.image" alt="" />
                                </div>

                                <div>
                                    <span v-html="post.body"
                                        class="block text-white dark:text-black break-words md:hidden"></span>
                                </div>
                            </div>

                            <div class="px-4 md:px-6 pt-4 md:pt-10">
                                <div class="space-y-4">
                                    <div>
                                        <span
                                            class="flex items-center text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.category.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <span
                                            class="flex items-center text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.subCategory.name }}
                                        </span>
                                    </div>

                                    <div v-if="post.tags && post.tags.length > 0">
                                        <span class="items-center text-sm font-semibold text-white dark:text-black">
                                            <IconTags class="inline-block mr-1" :size="18" />
                                            <template v-for="tag in post.tags" :key="tag.id">
                                                <div
                                                    class="inline-block mb-2 bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                    {{ tag.name }}
                                                </div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 relative">
                        <div class="justify-center items-center md:flex p-4 md:p-12 hidden md:display:block">
                            <span v-if="post.body" v-html="post.body"
                                class="block text-white dark:text-black break-words"></span>
                        </div>
                    </div>
                </div>

                <Pagination :elements="posts" />
            </div>

            <div v-else class="hidden sm:block">
                <p class="flex mx-auto items-center justify-center min-h-screen">
                    No posts found :/
                </p>
            </div>
        </GuestLayout>
    </template>
</template>

<style scoped></style>
