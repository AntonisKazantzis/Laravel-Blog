<script setup>
// Import necessary components and libraries
import AppLayout from "@/Layouts/AppLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import { IconTags, IconCategory } from "@tabler/icons-vue";

// Define props with default values
let props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <AppLayout title="Posts">
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between text-white dark:text-black gap-6">
                <h2
                    class="font-semibold text-xl flex-grow flex items-center justify-start mb-4 sm:mb-0 sm:ml-[11px] ml-0 mr-4">
                    Posts
                </h2>
            </div>
        </template>

        <div v-if="posts && posts.length > 0" class="pt-16 pb-8 border-t hidden sm:block">
            <div v-for="post in posts" :key="post.id"
                class="shadow-xl border-2 m-auto rounded flex md:w-[900px] md:h-[330px] mb-8 transition-transform transform hover:scale-105">

                <div class="flex-1 p-8">

                    <!-- Left Section (Details) -->
                    <NavLink class="border-none" :href="route('posts.show', { post: post.id })"
                        :active="route().current('posts')">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-6">
                                <div
                                    class="flex items-center pr-3 text-xl font-semibold text-white dark:text-black pb-10">
                                    {{ post.title }}
                                </div>
                                <span v-html="post.body"></span>
                            </div>

                            <div class="px-6 pt-10">
                                <div class="space-y-4">
                                    <div>
                                        <span
                                            class="flex items-center pr-3 text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.category.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <span
                                            class="flex items-center pr-3 text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.subCategory.name }}
                                        </span>
                                    </div>

                                    <div v-if="post.tags && post.tags.length > 0">
                                        <span
                                            class="flex items-center pr-3 text-sm font-semibold text-white dark:text-black">
                                            <IconTags class="mr-1" :size="18" />
                                            <template v-for="tag in post.tags" :key="tag.id">
                                                <!-- Display the tag name with rounded background gray color -->
                                                <div class="inline-block bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">{{
                                                    tag.name }}</div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </NavLink>
                </div>

                <!-- Right Section (Image) -->
                <div class="flex-1">
                    <img class="w-full h-full object-cover rounded" :src="post.image" alt="" />
                </div>
            </div>
        </div>

        <div v-else class="hidden sm:block">
            <p class="flex mx-auto items-center justify-center min-h-screen">
                No posts found :/
            </p>
        </div>
    </AppLayout>
</template>

<style scoped></style>
