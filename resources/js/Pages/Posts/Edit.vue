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
    categories: Object,
    tags: Object,
});

const imagePreview = ref(null);
const imageInput = ref(null);

const form = useForm({
    _method: "PATCH",
    title: props.post.title,
    body: props.post.body,
    image: props.post.image,
    category: props.post.category.id,
    subCategory: props.post.subCategory.id,
    tags: props.post.tags,
});

const tagIds = form.tags.map(tag => tag.id);
const tagsInput = ref(tagIds);

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
        onError: () => new FilamentNotification()
            .title(page.props.errors.messageTitle ?? "Error :/")
            .danger()
            .body(page.props.errors.messageBody ?? "Validation error")
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

const categories = computed(() =>
    props.categories.map(category => ({ value: category.id, label: category.name }))
);

const subCategories = computed(() => {
    const selectedCategory = props.categories.find(category => category.id === form.category);
    return selectedCategory ?
        selectedCategory.subCategories.map(subCategory => ({ value: subCategory.id, label: subCategory.name }))
        : [];
});

const tags = computed(() =>
    props.tags.map(tag => ({ value: tag.id, label: tag.name }))
);

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

                    <SecondaryButton v-if="imagePreview || form.image"
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

                        <MdEditor v-model="form.body" language="en-US"
                            class="border-black shadow-sm dark:border-black rounded-md focus:ring-indigo-500 dark:focus:ring-indigo-500 hover:dark:border-indigo-500 hover:border-indigo-500" />

                        <InputError :message="form.errors.body" class="mt-2" />
                    </div>
                </div>

                <div class="flex lg:flex-row flex-col gap-4 lg:space-y-0 space-y-4 col-span-6">
                    <div class="lg:w-1/2 w-full">
                        <InputLabel for="category" value="Category" />

                        <Multiselect required mode="single" :hide-selected="false"
                            noOptionsText="No available categories" placeholder="Select Category"
                            v-model="form.category" :options="categories"
                            class="mt-4 sm:mt-0 border text-black capitalize border-black dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

                        <InputError :message="form.errors.category" class="mt-2" />
                    </div>

                    <div class="lg:w-1/2 w-full">
                        <InputLabel for="subCategory" value="Sub Category" />

                        <Multiselect required mode="single" :hide-selected="false"
                            noOptionsText="Select a category first" placeholder="Select Sub Category"
                            v-model="form.subCategory" :options="subCategories"
                            class="mt-4 sm:mt-0 border text-black capitalize border-black dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

                        <InputError :message="form.errors.subCategory" class="mt-2" />
                    </div>

                    <div class="lg:w-1/2 w-full">
                        <InputLabel for="tags" value="Tags" />

                        <Multiselect mode="multiple" :close-on-select="false" :hide-selected="false"
                            noOptionsText="No available tags" placeholder="Select Tags" v-model="tagsInput"
                            :options="tags"
                            class="mt-4 sm:mt-0 border text-black capitalize border-black dark:border-black hover:dark:border-indigo-500 hover:border-indigo-500 rounded-md w-full md:w-64 lg:w-64 xl:w-64 2xl:w-64 sm:w-64 h-12 focus:outline-none focus:border-indigo-500" />

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
