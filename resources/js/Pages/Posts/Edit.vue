<script setup>
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import { MdEditor } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';

const props = defineProps({
    post: Object,
    posts: Object,
});

// Initialize categories and subcategories
let categories = {};
let subCategories = {};
let tags = {};

// Populate categories and subcategories from posts
props.posts.forEach(post => {
    if (post.category && !categories.hasOwnProperty(post.category.id)) {
        categories[post.category.id] = post.category.name;
    }
    if (post.subCategory && !subCategories.hasOwnProperty(post.subCategory.id)) {
        subCategories[post.subCategory.id] = post.subCategory.name;
    }
    if (post.tags) {
        post.tags.forEach(tag => {
            const tagId = parseInt(tag.id);
            if (!tags.hasOwnProperty(tagId)) {
                tags[tagId] = tag.name;
            }
        });
    }
});

const form = useForm({
    _method: "PUT",
    title: props.post.title,
    body: props.post.body,
    image: props.post.image,
    category: props.post.category.id,
    subCategory: props.post.subCategory.id,
    tags: props.post.tags
});


const submit = () => {
    form.post(route("posts.update", { post: props.post.id }), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => new FilamentNotification()
            .title('Updated successfully')
            .success()
            .body('The post has been updated.')
            .send(),
        onError: () => new FilamentNotification()
            .title('Error :/.')
            .danger()
            .body('Failed to update this post.')
            .send(),
    });
};
</script>

<template>
    <AppLayout title="Edit Post">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-white dark:text-black leading-tight">Editing Post</h2>
            </div>
        </template>

        <FormSection @submitted="submit" class="w-full mx-auto py-10 sm:px-6 lg:px-8">
            <template #title> Post Information </template>

            <template #description>
                Edit post information.
            </template>

            <template #form>
                <div class="col-span-6 flex gap-4">
                    <div class="col-span-6 min-w-[300px] w-full">
                        <InputLabel for="image" value="Image" />
                        <div
                            class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900 px-6 py-10">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-white dark:text-black" viewBox="0 0 24 24"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-white dark:text-black flex-col">
                                    <label for="file-upload"
                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <input id="file-upload" type="file" name="image"
                                            @input="form.image = $event.target.files[0]" />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-white dark:text-black">PNG, JPG, JPEG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                        <InputError :message="form.errors.image" class="mt-2" />
                    </div>
                </div>

                <!-- Name -->
                <div class="col-span-6">
                    <div class="mb-4">
                        <InputLabel for="title" value="Title" />

                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required
                            autocomplete="title" />

                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <InputLabel for="body" value="Body" />

                        <MdEditor v-model="form.body" language="en-US" />

                        <InputError :message="form.errors.body" class="mt-2" />
                    </div>
                </div>

                <!-- Select -->
                <div class="flex gap-4 col-span-6">
                    <!-- Category -->
                    <div class="w-1/2">
                        <InputLabel for="category" value="Category" />

                        <select v-model="form.category"
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500">
                            <option value="" disabled selected hidden>
                                Select Category
                            </option>
                            <template v-for="(categoryName, categoryId) in categories" :key="categoryId">
                                <option :value="categoryId">
                                    {{ categoryName }}
                                </option>
                            </template>
                        </select>

                        <InputError :message="form.errors.category" class="mt-2" />
                    </div>

                    <!-- Sub Category -->
                    <div class="w-1/2">
                        <InputLabel for="subCategory" value="Sub Category" />

                        <select v-model="form.subCategory"
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500">
                            <option value="" disabled selected hidden>
                                Select Sub Category
                            </option>
                            <template v-for="(subCategoryName, subCategoryId) in subCategories" :key="subCategoryId">
                                <option :value="subCategoryId">
                                    {{ subCategoryName }}
                                </option>
                            </template>
                        </select>

                        <InputError :message="form.errors.subCategory" class="mt-2" />
                    </div>

                    <!-- Tags -->
                    <div class="w-1/2">
                        <InputLabel for="tags" value="Tags" />

                        <select v-model="form.tags" multiple
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-82 lg:w-82 xl:w-82 2xl:w-82 sm:w-82 h-32 focus:outline-none focus:border-indigo-500">
                            <option value="" disabled selected hidden>
                                Select Tags
                            </option>
                            <template v-for="(tagName, tagId) in tags" :key="tagId">
                                <option :value="{ id: tagId, name: tagName }">
                                    {{ tagName }}
                                </option>
                            </template>
                        </select>

                        <InputError :message="form.errors.tags" class="mt-2" />
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="form.recentlySuccessful" class="me-3">
                    Saved.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </FormSection>
    </AppLayout>
</template>
