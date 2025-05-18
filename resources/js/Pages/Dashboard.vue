<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    team: Object,
    seasons: Array,
    tournaments: Array,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="relative bg-cover bg-center min-h-[300px] flex flex-col justify-center items-center rounded-b-3xl shadow-xl mb-12 overflow-hidden" style="background-image: url('https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png')">
            <div class="absolute inset-0 pointer-events-none bg-black/50"></div>
            <div class="relative z-10 px-6 py-16 text-center">
                <h1 class="mb-4 text-4xl font-extrabold text-white md:text-5xl drop-shadow-lg">Welcome to DGMP!</h1>
                <p class="mb-6 text-lg text-indigo-100 md:text-2xl">Danish Guild Mythc Plus. Are you ready to show which guild is the best?</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link :href="route('seasons.index')" class="px-6 py-3 font-semibold text-white transition shadow bg-white/20 hover:bg-white/30 rounded-xl backdrop-blur">
                        View All Seasons
                    </Link>
                    <Link v-if="team" :href="route('teams.show', team.id)" class="px-6 py-3 font-semibold text-white transition shadow bg-white/20 hover:bg-white/30 rounded-xl backdrop-blur">
                        Manage Team
                    </Link>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 md:grid-cols-2">
            <!-- Team Overview Card -->
            <div class="relative flex flex-col p-8 overflow-hidden shadow-2xl bg-white/80 dark:bg-gray-900/80 rounded-2xl">
                <div class="absolute w-32 h-32 rounded-full -top-8 -right-8 bg-gradient-to-br from-indigo-400 to-purple-400 opacity-20 blur-2xl"></div>
                <h3 class="flex items-center gap-2 mb-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <svg class="text-indigo-500 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ team?.name || 'No Team' }}
                </h3>
                <div v-if="team" class="w-full mt-4">
                    <h4 class="mb-2 text-sm font-semibold text-gray-500 dark:text-gray-400">Team Members</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="member in team.users" :key="member.id" class="flex items-center gap-3 px-3 py-2 rounded-lg shadow bg-indigo-50 dark:bg-gray-800">
                            <img class="object-cover w-10 h-10 border-2 border-indigo-300 rounded-full" :src="member.profile_photo_url" :alt="member.name">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ member.name }}</p>
                                <p class="text-xs text-indigo-600 dark:text-indigo-300">{{ member.membership.role || 'Member' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="mt-4 text-gray-500 dark:text-gray-400">You are not part of a team yet.</div>
            </div>

            <!-- Active Seasons Card -->
            <div class="relative flex flex-col p-8 overflow-hidden shadow-2xl bg-white/80 dark:bg-gray-900/80 rounded-2xl">
                <div class="absolute w-32 h-32 rounded-full -bottom-8 -left-8 bg-gradient-to-br from-pink-400 to-indigo-400 opacity-20 blur-2xl"></div>
                <h3 class="flex items-center gap-2 mb-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <svg class="text-pink-500 w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Active Seasons
                </h3>
                <div class="mt-4">
                    <div v-if="seasons?.length" class="flex flex-col gap-4">
                        <div v-for="season in seasons" :key="season.id" class="flex flex-col p-4 shadow bg-gradient-to-r from-indigo-100 to-pink-100 dark:from-gray-800 dark:to-gray-700 rounded-xl md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ season.name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Starts: {{ new Date(season.starts_at).toLocaleDateString() }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-2 md:mt-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                                    'bg-green-100 text-green-800': season.status === 'active',
                                    'bg-yellow-100 text-yellow-800': season.status === 'pending',
                                    'bg-red-100 text-red-800': season.status === 'completed'
                                }">
                                    {{ season.status }}
                                </span>
                                <Link :href="route('seasons.show', season.id)" class="ml-2 text-xs font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</Link>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">No active seasons</p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="px-4 mx-auto mt-12 max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <Link href="#" class="flex flex-col items-center p-6 transition shadow-lg rounded-2xl bg-gradient-to-br from-indigo-100 to-pink-100 dark:from-gray-800 dark:to-gray-700 hover:scale-105">
                    <svg class="w-8 h-8 mb-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Leaderboard</span>
                </Link>
                <Link href="#" class="flex flex-col items-center p-6 transition shadow-lg rounded-2xl bg-gradient-to-br from-indigo-100 to-pink-100 dark:from-gray-800 dark:to-gray-700 hover:scale-105">
                    <svg class="w-8 h-8 mb-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Schedule</span>
                </Link>
                <Link href="#" class="flex flex-col items-center p-6 transition shadow-lg rounded-2xl bg-gradient-to-br from-indigo-100 to-pink-100 dark:from-gray-800 dark:to-gray-700 hover:scale-105">
                    <svg class="w-8 h-8 mb-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Rules</span>
                </Link>
                <Link href="#" class="flex flex-col items-center p-6 transition shadow-lg rounded-2xl bg-gradient-to-br from-indigo-100 to-pink-100 dark:from-gray-800 dark:to-gray-700 hover:scale-105">
                    <svg class="w-8 h-8 mb-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Calendar</span>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
