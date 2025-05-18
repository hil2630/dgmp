<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    seasons: Array,
});
</script>

<template>
    <AppLayout title="Seasons">
        <div class="relative bg-gradient-to-br from-blue-900/50 to-gray-900/50 min-h-[180px] flex flex-col justify-center items-center shadow-xl mb-12 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/50 to-gray-900/50 mix-blend-multiply"></div>
                <img src="https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png" alt="Background" class="object-cover w-full h-full" />
            </div>
            <div class="relative z-10 px-6 py-10 text-center">
                <h1 class="mb-2 text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">Seasons</h1>
                <p class="text-lg text-blue-100">Browse all Mythic+ seasons and their leaderboards</p>
            </div>
        </div>
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <div v-for="season in seasons" :key="season.id" class="relative flex flex-col md:flex-row items-center md:items-stretch p-6 md:p-8 bg-white/90 dark:bg-gray-900/90 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 hover:scale-[1.01] transition">
                    <div class="flex flex-col justify-between flex-1 w-full gap-6 md:flex-row md:items-center">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-4 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ season.name }}</h2>
                                <span class="inline-flex items-center px-3 py-1 ml-2 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': season.status === 'active',
                                        'bg-yellow-100 text-yellow-800': season.status === 'pending',
                                        'bg-red-100 text-red-800': season.status === 'completed'
                                    }">
                                    {{ season.status }}
                                </span>
                            </div>
                            <div v-if="season.description" class="mb-2 text-sm italic text-gray-500 dark:text-gray-400">
                                {{ season.description }}
                            </div>
                            <div class="flex flex-wrap gap-6 mb-2 text-sm text-gray-700 dark:text-gray-300">
                                <div>
                                    <span class="font-semibold">Start:</span> {{ new Date(season.starts_at).toLocaleDateString() }}
                                </div>
                                <div>
                                    <span class="font-semibold">Time Limit:</span> {{ season.time_limit_hours }} hours
                                </div>
                                <div>
                                    <span class="font-semibold">Teams:</span> {{ season.teams.length }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-end gap-2 min-w-[160px]">
                            <Link :href="route('seasons.show', season.id)" class="inline-block px-6 py-3 font-semibold text-white transition shadow bg-gradient-to-r from-indigo-500 to-pink-500 rounded-xl hover:from-indigo-600 hover:to-pink-600">
                                View Season
                            </Link>
                            <div v-if="season.status === 'completed'" class="flex items-center gap-3 px-4 py-2 mt-4 font-semibold text-yellow-900 bg-yellow-100 shadow rounded-xl dark:bg-yellow-900/30 dark:text-yellow-200">
                                <template v-if="season.winner">
                                    <img v-if="season.winner.logo_url" :src="season.winner.logo_url" class="w-8 h-8 border-2 border-yellow-400 rounded-full" :alt="season.winner.name">
                                    <svg v-else class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8M12 17v4M17 5V3a1 1 0 00-1-1H8a1 1 0 00-1 1v2M21 5h-4M7 5H3m0 0a9 9 0 0018 0" /></svg>
                                    <span>Winner: {{ season.winner.name }}</span>
                                </template>
                                <template v-else>
                                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8M12 17v4M17 5V3a1 1 0 00-1-1H8a1 1 0 00-1 1v2M21 5h-4M7 5H3m0 0a9 9 0 0018 0" /></svg>
                                    <span>Winner: TBA</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!seasons.length" class="py-12 text-lg text-center text-gray-400">
                    No seasons found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
