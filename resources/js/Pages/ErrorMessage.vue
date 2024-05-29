<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps(["status"]);

const errorMessages = {
    404: {
        status: "404",
        message: "Page not found",
        description:
            "Sorry, the page you are looking for might be in another castle.",
    },
    500: {
        status: "500",
        message: "Internal Server Error",
        description:
            "Oops! Something went wrong on our end. We are working to fix it. Please try again later.",
    },
    503: {
        status: "503",
        message: "Service Unavailable",
        description:
            "We're currently undergoing maintenance or experiencing high traffic. Please check back later.",
    },
    403: {
        status: "403",
        message: "Forbidden",
        description:
            "Access to this resource is restricted. Make sure you have the necessary permissions.",
    },
    401: {
        status: "401",
        message: "Unauthorized",
        description:
            "Authentication required. Please log in to access this resource.",
    },
};

const errorStatus = props.status;
const errorMessage =
    errorMessages[props.status]?.message || errorMessages["500"].message;
const errorDescription =
    errorMessages[props.status]?.description ||
    errorMessages["500"].description;
</script>

<template>
    <template v-if="$page.props.auth && $page.props.auth.user">
        <AppLayout :title="`${errorStatus}`">
            <template #header>
                <div class="flex justify-between text-white dark:text-black p-[10px]">
                    <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">
                        {{ errorStatus }}
                    </h2>
                </div>
            </template>

            <main class="place-items-center mx-auto bg-[#343541] dark:bg-white pt-32 border-t">
                <div class="text-center">
                    <p class="font-semibold text-indigo-500 text-4xl">
                        {{ errorStatus }}
                    </p>

                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-white dark:text-black sm:text-5xl">
                        {{ errorMessage }}
                    </h1>

                    <p class="mt-6 text-base leading-7 text-white dark:text-black">
                        {{ errorDescription }}
                    </p>

                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <button onclick="history.back();return false;"
                            class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Go back
                        </button>
                    </div>
                </div>
            </main>
        </AppLayout>
    </template>

    <template v-else>
        <GuestLayout :title="`${errorStatus}`">
            <template #header>
                <div class="flex justify-between text-white dark:text-black p-[10px]">
                    <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">
                        {{ errorStatus }}
                    </h2>
                </div>
            </template>

            <main class="place-items-center mx-auto bg-[#343541] dark:bg-white pt-32 border-t">
                <div class="text-center">
                    <p class="font-semibold text-indigo-500 text-4xl">
                        {{ errorStatus }}
                    </p>

                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-white dark:text-black sm:text-5xl">
                        {{ errorMessage }}
                    </h1>

                    <p class="mt-6 text-base leading-7 text-white dark:text-black">
                        {{ errorDescription }}
                    </p>

                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <button onclick="history.back();return false;"
                            class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Go back
                        </button>
                    </div>
                </div>
            </main>
        </GuestLayout>
    </template>
</template>
