<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { IconSun, IconMoon } from "@tabler/icons-vue";

defineProps({
    title: String,
});

const isDark = ref(localStorage.getItem("theme") === "true");

function toggleDarkMode() {
    isDark.value = !isDark.value;
    localStorage.setItem("theme", JSON.stringify(isDark.value));
}

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen" :class="{ dark: isDark, light: !isDark }">
            <div class="bg-black dark:bg-white">
                <nav class="bg-black dark:bg-white border-b">
                    <!-- Primary Navigation Menu -->
                    <div class="mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <Link :href="'/'">
                                        <ApplicationMark class="block h-9 w-auto" />
                                    </Link>
                                </div>

                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                        Dashboard
                                    </NavLink>

                                    <NavLink :href="route('posts.index')" :active="route().current('posts.index')">
                                        Posts
                                    </NavLink>

                                    <NavLink :href="route('categories.index')" :active="route().current('categories.index')">
                                        Categories
                                    </NavLink>
                                </div>
                            </div>

                            <div class="hidden space-x-8 sm:flex sm:items-center sm:ms-6">
                                <NavLink :href="route('login')" :active="route().current('login')">
                                    Login
                                </NavLink>

                                <NavLink :href="route('register')" :active="route().current('register')">
                                    Register
                                </NavLink>

                                <button @click="toggleDarkMode">
                                    <template v-if="isDark">
                                        <IconSun class="mr-1 text-white dark:text-black" :size="24" />
                                    </template>

                                    <template v-else>
                                        <IconMoon class="mr-1 text-white" :size="24" />
                                    </template>
                                </button>
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-indigo-500hover:bg-black focus:outline-none focus:text-indigo-900 dark:text-black transition duration-150 ease-in-out" @click="showingNavigationDropdown = !showingNavigationDropdown">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

                                        <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('posts.index')" :active="route().current('posts.index')">
                                Posts
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('categories.index')" :active="route().current('categories.index')">
                                Categories
                            </ResponsiveNavLink>
                        </div>


                        <div class="pt-2 pb-3 border-t space-y-1">
                            <ResponsiveNavLink :href="route('login')" :active="route().current('login')">
                                Login
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('register')" :active="route().current('register')">
                                Register
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </nav>

                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-black dark:bg-white shadow">
                    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="bg-[#343541] dark:bg-white text-white min-h-screen">
                    <slot />
                </main>

                <!-- Page Footer -->
                <footer class="bg-black dark:bg-white text-white dark:text-black border-t shadow">
                    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row justify-between items-center mx-auto">
                            <div class="mb-4 sm:mb-0 text-center w-auto">
                                <p class="text-sm font-medium text-white dark:text-black ml-1">
                                    Copyright &copy; {{ new Date().getFullYear() }} Laravel Blog - All rights reserved
                                </p>

                                <p class="text-sm font-medium text-white dark:text-black ml-1">by&nbsp;
                                    <a href="https://github.com/AntonisKazantzis" class="border-b-2 border-transparent text-sm dark:focus:text-indigo-500 font-medium text-white hover:border-indigo-500 dark:border-white dark:hover:border-indigo-500 focus:text-indigo-500 focus:outline-none dark:text-black transition duration-150 ease-in-out" target="_blank">
                                        Antonis Kazantzis
                                    </a>
                                </p>
                            </div>

                            <div class="flex flex-wrap sm:space-x-4 justify-center sm:justify-start mb-2 sm:mb-0">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="mb-2 sm:mb-0">
                                    Dashboard
                                </NavLink>

                                <NavLink :href="route('posts.index')" :active="route().current('posts.index')" class="mb-2 sm:mb-0">
                                    Posts
                                </NavLink>

                                <NavLink :href="route('categories.index')" :active="route().current('categories.index')" class="mb-2 sm:mb-0">
                                    Categories
                                </NavLink>
                            </div>

                            <div>
                                <a href="#" class="border-b-2 border-transparent text-sm font-medium leading-5 text-white dark:text-black dark:focus:text-indigo-500 hover:border-indigo-500 focus:text-indigo-500 dark:border-white dark:hover:border-indigo-500 focus:outline-none transition duration-150 ease-in-out">Top</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
