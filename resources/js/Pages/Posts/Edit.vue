<script setup>
import { ref, computed, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import Multiselect from '@vueform/multiselect'
import { MdEditor } from "md-editor-v3";
import "md-editor-v3/lib/style.css";

const page = usePage();

const props = defineProps({
    post: Object,
    posts: Object,
});

const form = useForm({
    _method: "PATCH",
    title: props.post.title,
    body: props.post.body,
    image: props.post.image,
    category: props.post.category.id,
    subCategory: props.post.subCategory.id,
    tags: props.post.tags,
});

const imagePreview = ref(null);
const imageInput = ref(null);

const tagIds = form.tags.map(tag => tag.id);
const tagsInput = ref(tagIds);

const categoriesMap = ref({});
const subCategoriesMap = ref({});
const tagsMap = ref({});

props.posts.forEach(post => {
    if (post.category && !categoriesMap.value[post.category.id]) {
        categoriesMap.value[post.category.id] = post.category.name;
        subCategoriesMap.value[post.category.id] = {};
    }
    if (post.subCategory) {
        if (!subCategoriesMap.value[post.category.id][post.subCategory.id]) {
            subCategoriesMap.value[post.category.id][post.subCategory.id] = post.subCategory.name;
        }
    }
    if (post.tags) {
        post.tags.forEach(tag => {
            if (!tagsMap.value[tag.id]) {
                tagsMap.value[tag.id] = tag.name;
            }
        });
    }
});

const submit = () => {
    if (tagsInput.value) {
        form.tags = tagsInput.value.map(tagId => parseInt(tagId));
    }

    if (imageInput.value.files[0]) {
        form.image = imageInput.value.files[0];
    }

    form.post(route("posts.update", { post: props.post.id }), {
        forceFormData: true,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            clearImageFileInput();
            new FilamentNotification()
                .title(page.props.flash.messageTitle)
                .success()
                .body(page.props.flash.messageBody)
                .send();
        },
        onError: () =>
            new FilamentNotification()
                .title(page.props.errors.messageTitle)
                .danger()
                .body(page.props.errors.messageBody)
                .send(),
    });
};

const deleteImage = () => {
    imagePreview.value = null;
    form.image = null;
    clearImageFileInput();
};

const selectNewImage = () => {
    imageInput.value.click();
};

const updateImagePreview = () => {
    const image = imageInput.value.files[0];

    if (!image) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };

    reader.readAsDataURL(image);
};

const clearImageFileInput = () => {
    if (imageInput.value?.value) {
        imageInput.value.value = null;
    }
};

const categories = computed(() => {
    return Object.entries(categoriesMap.value).map(([id, name]) => ({ value: id, label: name }));
});

const subCategories = computed(() => {
    if (!form.category) return [];
    return Object.entries(subCategoriesMap.value[form.category] || {}).map(([id, name]) => ({ value: id, label: name }));
});

const tags = computed(() => {
    return Object.entries(tagsMap.value).map(([id, name]) => ({ value: id, label: name }));
});

watch(() => form.category, () => {
    form.subCategory = null;
});
</script>

<template>
    <AppLayout title="Edit Post">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-white dark:text-black leading-tight">
                    Editing Post
                </h2>
            </div>
        </template>

        <FormSection @submitted="submit" class="w-full mx-auto py-10 sm:px-6 lg:px-8">
            <template #title> Post Information </template>

            <template #description> Edit post. </template>

            <template #form>
                <div class="col-span-6 sm:col-span-4">
                    <input id="image" ref="imageInput" type="file" class="hidden" @change="updateImagePreview" />

                    <InputLabel for="image" value="Image" />

                    <div v-show="!imagePreview && form.image" class="mt-2">
                        <img :src="form.image"
                            class="rounded-md h-[182px] w-[182px] object-cover border hover:border-indigo-500 hover:dark:border-indigo-500 border-black dark:border-black" />
                    </div>

                    <div v-show="imagePreview" class="mt-2">
                        <span
                            class="block rounded-md w-[182px] h-[182px] bg-cover bg-no-repeat bg-center border hover:border-indigo-500 hover:dark:border-indigo-500 border-black dark:border-black"
                            :style="'background-image: url(\'' +
                                imagePreview +
                                '\');'
                                " />
                    </div>

                    <SecondaryButton class="mt-2 me-2 w-48 max-w-[182px] max-h-[182px] items-center justify-center"
                        type="button" @click.prevent="selectNewImage">
                        Select A New Image
                    </SecondaryButton>

                    <SecondaryButton v-if="imagePreview"
                        class="mt-2 w-48 max-w-[182px] max-h-[182px] items-center justify-center" type="button"
                        @click.prevent="deleteImage">
                        Remove Image
                    </SecondaryButton>

                    <InputError :message="form.errors.image" class="mt-2" />
                </div>

                <div class="col-span-6">
                    <div class="mb-4">
                        <InputLabel for="title" value="Title" />

                        <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required
                            autocomplete="title" />

                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="body" value="Body" />

                        <MdEditor v-model="form.body" language="en-US" />

                        <InputError :message="form.errors.body" class="mt-2" />
                    </div>
                </div>

                <div class="flex gap-4 col-span-6">
                    <div class="w-1/2">
                        <InputLabel for="category" value="Category" />

                        <Multiselect required mode="single" :hide-selected="false" no-options-text="No categories"
                            placeholder="Select Category" v-model="form.category" :options="categories"
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

                        <InputError :message="form.errors.category" class="mt-2" />
                    </div>

                    <div class="w-1/2">
                        <InputLabel for="subCategory" value="Sub Category" />

                        <Multiselect required mode="single" :hide-selected="false" no-options-text="No sub categories"
                            placeholder="Select Sub Category" v-model="form.subCategory" :options="subCategories"
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

                        <InputError :message="form.errors.subCategory" class="mt-2" />
                    </div>

                    <div class="w-1/2">
                        <InputLabel for="tags" value="Tags" />

                        <Multiselect mode="multiple" :close-on-select="false" :hide-selected="false"
                            no-options-text="No tags" placeholder="Select Tags" v-model="tagsInput" :options="tags"
                            class="mt-4 sm:mt-0 border text-black capitalize border-white dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

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

<style src="@vueform/multiselect/themes/default.css"></style>
