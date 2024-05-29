<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import FlashMessage from "@/Components/FlashMessage.vue";
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
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

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen" :class="{ 'dark': isDark, 'light': !isDark }">
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

                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <div class="ms-3 relative">
                                    <!-- Teams Dropdown -->
                                    <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-white hover:text-indigo-500 dark:hover:text-indigo-500 dark:text-black focus:outline-none active:bg-black transition ease-in-out duration-150">
                                                    {{ $page.props.auth.user.current_team.name }}

                                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <div class="w-60">
                                                <!-- Team Management -->
                                                <div class="block px-4 py-2 text-xs text-white dark:text-black">
                                                    Manage Team
                                                </div>

                                                <!-- Team Settings -->
                                                <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                                    Team Settings
                                                </DropdownLink>

                                                <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                    Create New Team
                                                </DropdownLink>

                                                <!-- Team Switcher -->
                                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                    <div class="border-t border-white dark:border-gray-900" />

                                                    <div class="block px-4 py-2 text-xs text-white dark:text-black">
                                                        Switch Teams
                                                    </div>

                                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                        <form @submit.prevent="switchToTeam(team)">
                                                            <DropdownLink as="button">
                                                                <div class="flex items-center">
                                                                    <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>

                                                                    <div>
                                                                        {{ team.name }}
                                                                    </div>
                                                                </div>
                                                            </DropdownLink>
                                                        </form>
                                                    </template>
                                                </template>
                                            </div>
                                        </template>
                                    </Dropdown>
                                </div>

                                <button @click="toggleDarkMode">
                                    <template v-if="isDark">
                                        <IconSun class="mr-1 text-white dark:text-black" :size="24" />
                                    </template>

                                    <template v-else>
                                        <IconMoon class="mr-1 text-white" :size="24" />
                                    </template>
                                </button>

                                <!-- Settings Dropdown -->
                                <div class="ms-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-indigo dark:border-white transition">
                                                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                            </button>

                                            <span v-else class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-black text-white dark:bg-white hover:text-indigo-500 dark:hover:text-indigo-500 dark:text-black focus:outline-none active:bg-black transition ease-in-out duration-150">
                                                    {{ $page.props.auth.user.name }}

                                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <!-- Account Management -->
                                            <div class="block px-4 py-2 text-xs text-zinc-500 dark:text-zinc-500">
                                                Manage Account
                                            </div>

                                            <DropdownLink :href="route('profile.show')">
                                                Profile
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                                API Tokens
                                            </DropdownLink>

                                            <div class="border-t border-white dark:border-gray-900" />

                                            <!-- Authentication -->
                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button">
                                                    Log Out
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button @click="toggleDarkMode">
                                    <template v-if="isDark">
                                        <IconSun class="mr-1 text-white dark:text-black" :size="24" />
                                    </template>

                                    <template v-else>
                                        <IconMoon class="mr-1 text-white" :size="24" />
                                    </template>
                                </button>

                                <button class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-indigo-500hover:bg-black focus:outline-none focus:text-indigo-900 dark:text-black transition duration-150 ease-in-out" @click="showingNavigationDropdown = !showingNavigationDropdown">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />

                                        <path :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
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

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-white dark:border-gray-900">
                            <div class="flex items-center px-4">
                                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                    <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                </div>

                                <div>
                                    <div class="font-medium text-base text-white dark:text-black">
                                        {{ $page.props.auth.user.name }}
                                    </div>

                                    <div class="font-medium text-sm text-white dark:text-black">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profile
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                    API Tokens
                                </ResponsiveNavLink>

                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        Log Out
                                    </ResponsiveNavLink>
                                </form>

                                <!-- Team Management -->
                                <template v-if="$page.props.jetstream.hasTeamFeatures">
                                    <div class="border-t border-white dark:border-gray-900" />

                                    <div class="block px-4 py-2 text-xs text-white dark:text-black">
                                        Manage Team
                                    </div>

                                    <!-- Team Settings -->
                                    <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                        Team Settings
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                        Create New Team
                                    </ResponsiveNavLink>

                                    <!-- Team Switcher -->
                                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                                        <div class="border-t border-white dark:border-gray-900" />

                                        <div class="block px-4 py-2 text-xs text-white dark:text-black">
                                            Switch Teams
                                        </div>

                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <ResponsiveNavLink as="button">
                                                    <div class="flex items-center">
                                                        <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>

                                                        <div>
                                                            {{ team.name }}
                                                        </div>
                                                    </div>
                                                </ResponsiveNavLink>
                                            </form>
                                        </template>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-black dark:bg-white shadow">
                    <FlashMessage :message="$page.props.flash.message" :type="$page.props.flash.type" />

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
