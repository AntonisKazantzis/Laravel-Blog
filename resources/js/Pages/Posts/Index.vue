<script setup>
import { router, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { IconTags, IconCategory, IconX, IconPencil } from "@tabler/icons-vue";

const props = defineProps({
    posts: Object,
});

const destroy = (postId) => {
    router.delete(route("posts.destroy", { post: postId }), {
        preserveScroll: true,
        onBefore: () => confirm('Are you sure you want to delete this post?'),
        onSuccess: () => new FilamentNotification()
            .title('Deleted successfully')
            .success()
            .body('The post has been deleted.')
            .send(),
        onError: () => new FilamentNotification()
            .title('Error :/.')
            .danger()
            .body('Something went wrong.')
            .send(),
    });
};

const edit = (categoryId) => {
    router.get(route("categories.edit", { category: categoryId }));
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

            <div v-if="posts && posts.length > 0" class="pt-16 pb-8 border-t hidden sm:block">
                <div v-for="post in posts" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex md:w-[900px] md:h-[330px] mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-8">

                        <!-- Left Section (Details) -->
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
                                                <div
                                                    class="inline-block bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                    {{
                                                        tag.name }}</div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section (Image) -->
                    <div class="flex-1 relative">
                        <img class="w-full h-full object-cover rounded" :src="post.image" alt="" />

                        <div class="absolute top-2 right-2 flex">
                            <button type="button" @click="destroy(post.id)"
                                class="bg-zinc-300 text-black p-2 rounded-full mr-2">
                                <IconX :size="18" />
                            </button>

                            <button type="button" @click="edit(category.id)"
                                class="bg-zinc-300 text-black p-2 rounded-full">
                                <IconPencil :size="18" />
                            </button>
                        </div>
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

            <div v-if="posts && posts.length > 0" class="pt-16 pb-8 border-t hidden sm:block">
                <div v-for="post in posts" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex md:w-[900px] md:h-[330px] mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-8">

                        <!-- Left Section (Details) -->
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
                                                <div
                                                    class="inline-block bg-zinc-300 text-black dark:text-black rounded-full px-2 py-1 mr-1">
                                                    {{
                                                        tag.name }}</div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        </GuestLayout>
    </template>
</template>

<style scoped></style>
