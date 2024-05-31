<script setup>
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";

const props = defineProps({
    categories: Object,
});

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route("categories.store"), {
        forceFormData: true,
        onSuccess: () => new FilamentNotification()
            .title('Created successfully')
            .success()
            .body('The category has been created.')
            .send(),
        onError: () => new FilamentNotification()
            .title('Error :/.')
            .danger()
            .body('Failed to create category.')
            .send(),
    });
};
</script>

<template>
    <AppLayout title="Create Category">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-white dark:text-black leading-tight">Creating Category</h2>
            </div>
        </template>

        <FormSection @submit.prevent="submit" class="w-full mx-auto py-10 sm:px-6 lg:px-8">
            <template #title> Category Information </template>

            <template #description>
                Create your account's category information.
            </template>

            <template #form>
                <!-- Name -->
                <div class="col-span-6">
                    <div class="mb-4">
                        <InputLabel for="name" value="Name" />

                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                            autocomplete="name" />

                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="form.recentlySuccessful" class="me-3">
                    Created.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
                </PrimaryButton>
            </template>
        </FormSection>
    </AppLayout>
</template>
