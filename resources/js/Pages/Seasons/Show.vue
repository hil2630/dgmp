<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    season: Object,
    leaderboard: Array,
});
const season = props.season;
const leaderboard = props.leaderboard;

const page = usePage();
const user = page.props.auth.user;
const team = page.props.team;

const processing = ref(false);
const successMessage = ref('');

function isTeamOwner() {
    return team && team.owner && user && user.id === team.owner.id;
}

function isTeamSignedUp() {
    return team && season.teams && season.teams.some(t => t.id === team.id);
}

function signUpTeam() {
    processing.value = true;
    router.post(route('seasons.signup', { season: season.id }), {}, {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Team signed up successfully!';
            processing.value = false;
            if (team && season.teams && !season.teams.some(t => t.id === team.id)) {
                season.teams.push({ id: team.id });
            }
            if (team && leaderboard && !leaderboard.some(t => t.id === team.id)) {
                leaderboard.push({ id: team.id, name: team.name, runs_count: 0 });
            }
            setTimeout(() => successMessage.value = '', 3000);
        },
        onFinish: () => { processing.value = false; },
    });
}

function removeSignupTeam() {
    processing.value = true;
    router.post(route('seasons.remove-signup', { season: season.id }), {}, {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Team signup removed!';
            processing.value = false;
            if (team && season.teams) {
                const idx = season.teams.findIndex(t => t.id === team.id);
                if (idx !== -1) season.teams.splice(idx, 1);
            }
            if (team && leaderboard) {
                const idx = leaderboard.findIndex(t => t.id === team.id);
                if (idx !== -1) leaderboard.splice(idx, 1);
            }
            setTimeout(() => successMessage.value = '', 3000);
        },
        onFinish: () => { processing.value = false; },
    });
}
</script>

<template>
    <AppLayout :title="season.name">
        <div
            class="relative bg-gradient-to-br from-blue-900/50 to-gray-900/50 min-h-[180px] flex flex-col justify-center items-center shadow-xl mb-12 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/50 to-gray-900/50 mix-blend-multiply">
                </div>
                <img src="https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png"
                    alt="Background" class="object-cover w-full h-full" />
            </div>
            <div class="relative z-10 px-6 py-10 text-center">
                <h2
                    class="flex items-center justify-center gap-3 mb-2 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200 md:text-4xl">
                    <span>{{ season.name }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium ml-2" :class="{
                        'bg-green-100 text-green-800': season.status === 'active',
                        'bg-yellow-100 text-yellow-800': season.status === 'upcoming',
                        'bg-red-100 text-red-800': season.status === 'completed'
                    }">
                        {{ season.status }}
                    </span>
                </h2>
                <p class="text-lg text-blue-100">Season details and leaderboard</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 md:grid-cols-3">
            <!-- Season Info -->
            <div
                class="relative flex flex-col p-8 overflow-hidden shadow-2xl md:col-span-1 bg-white/80 dark:bg-gray-900/80 rounded-2xl">
                <div
                    class="absolute w-24 h-24 rounded-full -top-8 -right-8 bg-gradient-to-br from-indigo-400 to-pink-400 opacity-20 blur-2xl">
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Season Details</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ new
                            Date(season.starts_at).toLocaleDateString() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Time Limit</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ season.time_limit_hours }} hours</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teams</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ season.teams.length }} teams
                            participating</dd>
                    </div>
                </dl>
                <div class="mt-8">
                    <h4 class="mb-2 font-semibold text-gray-700 text-md dark:text-gray-300">Time Remaining</h4>
                    <p v-if="season.status === 'active'"
                        class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ Math.floor(season.time_remaining / 3600) }}h {{ Math.floor((season.time_remaining % 3600) /
                            60) }}m
                    </p>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        {{ season.status === 'upcoming' ? 'Not started yet' : 'Season completed' }}
                    </p>
                </div>
                <div v-if="successMessage"
                    class="px-4 py-2 mb-4 font-semibold text-center text-green-800 bg-green-100 rounded-lg">
                    {{ successMessage }}
                </div>
                <div v-if="team && isTeamOwner() && season.status === 'upcoming'" class="mt-8">
                    <button v-if="!isTeamSignedUp()" @click="signUpTeam" :disabled="processing"
                        class="w-full px-6 py-3 text-lg font-semibold text-white transition bg-indigo-600 shadow rounded-xl hover:bg-indigo-700 disabled:opacity-50">
                        <span v-if="processing">Signing up...</span>
                        <span v-else>Sign Up Team</span>
                    </button>
                    <button v-else @click="removeSignupTeam" :disabled="processing"
                        class="w-full px-6 py-3 text-lg font-semibold text-white transition bg-red-600 shadow rounded-xl hover:bg-red-700 disabled:opacity-50">
                        <span v-if="processing">Removing...</span>
                        <span v-else>Remove Signup</span>
                    </button>
                </div>
            </div>

            <!-- Leaderboard -->
            <div
                class="relative flex flex-col p-8 overflow-hidden shadow-2xl md:col-span-2 bg-white/80 dark:bg-gray-900/80 rounded-2xl">
                <div
                    class="absolute w-24 h-24 rounded-full -bottom-8 -left-8 bg-gradient-to-br from-pink-400 to-indigo-400 opacity-20 blur-2xl">
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Leaderboard</h3>
                <div class="overflow-x-auto bg-white shadow-lg rounded-xl dark:bg-gray-900">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead
                            class="sticky top-0 z-10 bg-gradient-to-r from-indigo-200 to-pink-200 dark:from-gray-800 dark:to-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-700 uppercase dark:text-gray-300">
                                    Rank</th>
                                <th
                                    class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-700 uppercase dark:text-gray-300">
                                    Team</th>
                                <th
                                    class="px-6 py-3 text-xs font-bold tracking-wider text-left text-gray-700 uppercase dark:text-gray-300">
                                    Runs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(team, index) in leaderboard" :key="team.id" :class="[
                                'transition',
                                'hover:bg-indigo-50 dark:hover:bg-gray-800',
                                index === 0 ? 'bg-gradient-to-r from-yellow-200 to-yellow-100 dark:from-yellow-700 dark:to-yellow-800 shadow-lg' : 'bg-white dark:bg-gray-900'
                            ]">
                                <td class="px-6 py-4 align-middle whitespace-nowrap">
                                    <div class="text-lg font-bold text-indigo-700 dark:text-indigo-300">#{{ index + 1 }}
                                    </div>
                                </td>
                                <td class="flex items-center gap-3 px-6 py-4 align-middle whitespace-nowrap">
                                    <template v-if="team.logo_url">
                                        <img :src="team.logo_url"
                                            class="w-10 h-10 bg-white border-2 border-indigo-300 rounded-full dark:bg-gray-800"
                                            :alt="team.name">
                                    </template>
                                    <template v-else>
                                        <div
                                            class="flex items-center justify-center w-10 h-10 text-lg font-bold text-indigo-400 bg-indigo-100 rounded-full dark:bg-gray-700 dark:text-indigo-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </template>
                                    <span class="font-semibold text-gray-900 text-md dark:text-white">{{ team.name
                                    }}</span>
                                </td>
                                <td class="px-6 py-4 align-middle whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-md dark:text-white">{{ team.runs_count
                                    }}</div>
                                </td>
                            </tr>
                            <tr v-if="!leaderboard.length">
                                <td colspan="3" class="py-8 text-center text-gray-400">No teams yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
