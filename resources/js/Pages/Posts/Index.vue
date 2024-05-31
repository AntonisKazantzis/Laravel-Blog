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

const edit = (postId) => {
    router.get(route("posts.edit", { post: postId }));
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

            <div v-if="posts && posts.length > 0" class="pt-16 pb-8 border-t sm:block">
                <div v-for="post in posts" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-4xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-4 md:p-8 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6">
                                <div v-if="post.title"
                                    class="flex items-center pr-3 text-xl font-semibold text-white dark:text-black pb-4 md:pb-10">
                                    {{ post.title }}
                                </div>
                                <span v-if="post.body" v-html="post.body"
                                    class="block text-white dark:text-black break-words"></span>
                            </div>

                            <div class="px-4 md:px-6 pt-4 md:pt-10">
                                <div class="space-y-4">
                                    <div>
                                        <span v-if="post.category"
                                            class="flex items-center pr-3 text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.category.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <span v-if="post.subCategory"
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
                        <img class="w-full h-full object-cover rounded" :src="post.image" alt="" />

                        <div class="absolute top-2 md:right-2 md:justify-none justify-between md:w-auto w-full flex">
                            <button type="button" @click="destroy(post.id)"
                                class="bg-zinc-300 text-black p-2 rounded-full md:mr-2 ml-2">
                                <IconX :size="18" />
                            </button>

                            <button type="button" @click="edit(post.id)"
                                class="bg-zinc-300 text-black p-2 rounded-full md:mr-0 mr-2">
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

            <div v-if="posts && posts.length > 0" class="pt-16 pb-8 border-t sm:block">
                <div v-for="post in posts" :key="post.id"
                    class="shadow-xl border-2 m-auto rounded flex flex-col md:flex-row w-full max-w-md md:max-w-4xl mb-8 transition-transform transform hover:scale-105">

                    <div class="flex-1 p-4 md:p-8 overflow-hidden">
                        <div class="bg-[#343541] dark:bg-white">
                            <div class="px-4 md:px-6">
                                <div v-if="post.title"
                                    class="flex items-center pr-3 text-xl font-semibold text-white dark:text-black pb-4 md:pb-10">
                                    {{ post.title }}
                                </div>
                                <span v-if="post.body" v-html="post.body"
                                    class="block text-white dark:text-black break-words"></span>
                            </div>

                            <div class="px-4 md:px-6 pt-4 md:pt-10">
                                <div class="space-y-4">
                                    <div>
                                        <span v-if="post.category"
                                            class="flex items-center pr-3 text-sm font-semibold text-white dark:text-black">
                                            <IconCategory class="mr-1" :size="18" />
                                            {{ post.category.name }}
                                        </span>
                                    </div>

                                    <div>
                                        <span v-if="post.subCategory"
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
                                                    {{ tag.name }}
                                                </div>
                                            </template>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
