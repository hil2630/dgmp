<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ModernLogo from '@/Components/ModernLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

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
    <nav
        class="sticky top-0 z-50 border-b shadow-sm bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl border-gray-200/50 dark:border-gray-700/50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left side -->
                <div class="flex items-center min-h-0">
                    <!-- Logo -->
                    <Link :href="route('dashboard')" class="flex items-center min-h-0 group">
                    <ModernLogo class="min-h-0" />
                    </Link>

                    <!-- Navigation Links -->
                    <div class="items-center hidden min-h-0 ml-10 space-x-8 sm:flex">
                        <Link :href="route('dashboard')"
                            class="relative inline-flex items-center min-h-0 px-1 pt-1 text-sm font-medium transition-all duration-200"
                            :class="[
                                route().current('dashboard')
                                    ? 'text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                            ]">
                        <span class="relative">Dashboard
                            <span v-if="route().current('dashboard')"
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-500 to-pink-500 transform origin-left transition-transform duration-300"></span>
                        </span>
                        </Link>
                        <Link :href="route('seasons.index')"
                            class="relative inline-flex items-center min-h-0 px-1 pt-1 text-sm font-medium transition-all duration-200"
                            :class="[
                                route().current('seasons.*')
                                    ? 'text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                            ]">
                        <span class="relative">Seasons
                            <span v-if="route().current('seasons.*')"
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-500 to-pink-500 transform origin-left transition-transform duration-300"></span>
                        </span>
                        </Link>
                        <Link :href="route('donations')"
                            class="relative inline-flex items-center min-h-0 px-1 pt-1 text-sm font-medium transition-all duration-200"
                            :class="[
                                route().current('donations')
                                    ? 'text-indigo-600 dark:text-indigo-400'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
                            ]">
                        <span class="relative">Prize Pool
                            <span v-if="route().current('donations')"
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-indigo-500 to-pink-500 transform origin-left transition-transform duration-300"></span>
                        </span>
                        </Link>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center min-h-0 space-x-4">
                    <!-- Teams Dropdown -->
                    <div v-if="$page.props.jetstream.hasTeamFeatures" class="relative">
                        <Dropdown align="right" width="60">
                            <template #trigger>
                                <button
                                    class="flex items-center min-h-0 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 border dark:text-gray-300 bg-white/50 dark:bg-gray-800/50 border-gray-200/50 dark:border-gray-700/50 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                                    <span class="flex items-center min-h-0">
                                        <svg class="w-5 h-5 min-h-0 mr-2 text-indigo-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="min-h-0">{{ $page.props.auth.user.current_team ?
                                            $page.props.auth.user.current_team.name : 'No Team' }}</span>
                                    </span>
                                    <svg class="w-4 h-4 min-h-0 ml-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs font-medium text-gray-400">Manage Team</div>

                                    <DropdownLink v-if="$page.props.auth.user.current_team"
                                        :href="route('teams.show', { team: $page.props.auth.user.current_team.id })"
                                        class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <span class="flex items-center min-h-0">
                                            <svg class="w-4 h-4 min-h-0 mr-2 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="min-h-0">Team Settings</span>
                                        </span>
                                    </DropdownLink>

                                    <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                        :href="route('teams.create')"
                                        class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <span class="flex items-center min-h-0">
                                            <svg class="w-4 h-4 min-h-0 mr-2 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span class="min-h-0">Create M+ Team</span>
                                        </span>
                                    </DropdownLink>

                                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                                        <div class="border-t border-gray-200 dark:border-gray-600" />
                                        <div class="block px-4 py-2 text-xs font-medium text-gray-400">Switch Teams
                                        </div>
                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <DropdownLink as="button"
                                                    class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                    <span class="flex items-center min-h-0">
                                                        <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                            class="w-4 h-4 min-h-0 mr-2 text-green-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="min-h-0">{{ team.name }}</span>
                                                    </span>
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button v-if="$page.props.jetstream.managesProfilePhotos"
                                    class="flex items-center min-h-0 text-sm transition-all duration-200 border-2 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 hover:shadow-md">
                                    <img class="object-cover w-8 h-8 min-h-0 rounded-full"
                                        :src="$page.props.auth.user.profile_photo_url"
                                        :alt="$page.props.auth.user.name">
                                </button>

                                <button v-else
                                    class="flex items-center min-h-0 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 border dark:text-gray-300 bg-white/50 dark:bg-gray-800/50 border-gray-200/50 dark:border-gray-700/50 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                                    <span class="min-h-0">{{ $page.props.auth.user.name }}</span>
                                    <svg class="w-4 h-4 min-h-0 ml-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="block px-4 py-2 text-xs font-medium text-gray-400">Manage Account</div>

                                <DropdownLink :href="route('profile.show')"
                                    class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <span class="flex items-center min-h-0">
                                        <svg class="w-4 h-4 min-h-0 mr-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="min-h-0">Profile</span>
                                    </span>
                                </DropdownLink>

                                <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                    class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <span class="flex items-center min-h-0">
                                        <svg class="w-4 h-4 min-h-0 mr-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        <span class="min-h-0">API Tokens</span>
                                    </span>
                                </DropdownLink>

                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                <form @submit.prevent="logout">
                                    <DropdownLink as="button"
                                        class="flex items-center min-h-0 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <span class="flex items-center min-h-0">
                                            <svg class="w-4 h-4 min-h-0 mr-2 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span class="min-h-0">Log Out</span>
                                        </span>
                                    </DropdownLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center min-h-0 -mr-2 sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center min-h-0 p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
                        <svg class="w-6 h-6 min-h-0" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path
                                :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <Link :href="route('dashboard')"
                    class="block px-4 py-2 text-base font-medium transition duration-150 ease-in-out" :class="[
                        route().current('dashboard')
                            ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/50'
                            : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800'
                    ]">
                Dashboard
                </Link>
                <Link :href="route('seasons.index')"
                    class="block px-4 py-2 text-base font-medium transition duration-150 ease-in-out" :class="[
                        route().current('seasons.*')
                            ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/50'
                            : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800'
                    ]">
                Seasons
                </Link>

                <Link :href="route('donations')"
                    class="block px-4 py-2 text-base font-medium transition duration-150 ease-in-out" :class="[
                        route().current('donations')
                            ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/50'
                            : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800'
                    ]">
                Donations
                </Link>
            </div>

            <!-- Mobile Settings -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center min-h-0 px-4">
                    <div v-if="$page.props.jetstream.managesProfilePhotos" class="min-h-0 mr-3 shrink-0">
                        <img class="object-cover w-10 h-10 min-h-0 rounded-full"
                            :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                    </div>

                    <div class="min-h-0">
                        <div class="min-h-0 text-base font-medium text-gray-800 dark:text-gray-200">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="min-h-0 text-sm font-medium text-gray-500">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <Link :href="route('profile.show')"
                        class="flex items-center min-h-0 px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="flex items-center min-h-0">
                        <svg class="w-5 h-5 min-h-0 mr-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="min-h-0">Profile</span>
                    </span>
                    </Link>

                    <Link v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')"
                        class="flex items-center min-h-0 px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="flex items-center min-h-0">
                        <svg class="w-5 h-5 min-h-0 mr-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <span class="min-h-0">API Tokens</span>
                    </span>
                    </Link>

                    <form method="POST" @submit.prevent="logout">
                        <button
                            class="flex items-center w-full min-h-0 px-4 py-2 text-base font-medium text-left text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="flex items-center min-h-0">
                                <svg class="w-5 h-5 min-h-0 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="min-h-0">Log Out</span>
                            </span>
                        </button>
                    </form>

                    <!-- Mobile Team Management -->
                    <template v-if="$page.props.jetstream.hasTeamFeatures">
                        <div class="border-t border-gray-200 dark:border-gray-600" />
                        <div class="block px-4 py-2 text-xs font-medium text-gray-400">Manage Team</div>

                        <Link v-if="$page.props.auth.user.current_team"
                            :href="route('teams.show', { team: $page.props.auth.user.current_team.id })"
                            class="flex items-center min-h-0 px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span class="flex items-center min-h-0">
                            <svg class="w-5 h-5 min-h-0 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="min-h-0">Team Settings</span>
                        </span>
                        </Link>

                        <Link v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')"
                            class="flex items-center min-h-0 px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span class="flex items-center min-h-0">
                            <svg class="w-5 h-5 min-h-0 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="min-h-0">Create M+ Team</span>
                        </span>
                        </Link>

                        <template v-if="$page.props.auth.user.all_teams.length > 1">
                            <div class="border-t border-gray-200 dark:border-gray-600" />
                            <div class="block px-4 py-2 text-xs font-medium text-gray-400">Switch Teams</div>

                            <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                <form @submit.prevent="switchToTeam(team)">
                                    <button
                                        class="flex items-center w-full min-h-0 px-4 py-2 text-base font-medium text-left text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <span class="flex items-center min-h-0">
                                            <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                class="w-5 h-5 min-h-0 mr-3 text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="min-h-0">{{ team.name }}</span>
                                        </span>
                                    </button>
                                </form>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>
