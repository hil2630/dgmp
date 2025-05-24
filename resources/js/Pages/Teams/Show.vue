<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue';
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue';
import RegisterRunForm from '@/Pages/Teams/Partials/RegisterRunForm.vue';

defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
    currentSeason: Object,
    isSignedUpForSeason: Boolean,
    dungeons: Array,
});
</script>

<template>
    <AppLayout title="Team Settings">
        <!-- Full-width Hero Section (now inside AppLayout, below nav) -->
        <div class="relative w-full overflow-hidden shadow-xl">
            <img class="absolute inset-0 object-cover w-full h-72 sm:h-96"
                src="https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png"
                alt="Team background">
            <div
                class="absolute inset-0 bg-gradient-to-b from-gray-900/90 via-gray-900/60 to-gray-900/30 backdrop-blur-sm">
            </div>
            <div class="relative z-10 flex flex-col items-center justify-center text-center h-72 sm:h-96">
                <div class="flex flex-col items-center justify-center">
                    <div
                        class="flex items-center justify-center w-24 h-24 mb-4 border-4 border-indigo-500 rounded-full shadow-lg bg-white/10">
                        <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-white sm:text-5xl drop-shadow-lg">{{ team.name }}</h1>
                    <p class="mt-2 text-lg text-indigo-100 sm:text-2xl">Manage your team settings and members</p>
                    <span
                        class="inline-block px-5 py-2 mt-4 text-base font-semibold text-white bg-indigo-600 rounded-full shadow-lg">
                        {{ team.users.length }} Members
                    </span>
                </div>
            </div>
        </div>

        <!-- Main App Content -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Team Information -->
                    <div class="lg:col-span-1">
                        <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Team Information</h2>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your team's basic
                                    information</p>
                                <div class="mt-6">
                                    <UpdateTeamNameForm :team="team" :permissions="permissions" />
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div v-if="permissions.canDeleteTeam && !team.personal_team" class="mt-8">
                            <div class="overflow-hidden shadow bg-red-50 dark:bg-red-900/20 sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-red-600 dark:text-red-400">Danger Zone</h2>
                                    <p class="mt-1 text-sm text-red-500 dark:text-red-400">Permanently delete your team
                                        and all of its data</p>
                                    <div class="mt-6">
                                        <DeleteTeamForm :team="team" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Content -->
                    <div class="space-y-8 lg:col-span-2">
                        <!-- Run Registration (only for team owners, active season, and signed up teams) -->
                        <div v-if="permissions.canUpdateTeam && currentSeason && isSignedUpForSeason">
                            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                                <div class="p-6">
                                    <RegisterRunForm :team="team" :current-season="currentSeason"
                                        :dungeons="dungeons" />
                                </div>
                            </div>
                        </div>

                        <!-- Run Registration Not Available Message -->
                        <div v-else-if="permissions.canUpdateTeam"
                            class="overflow-hidden shadow bg-yellow-50 dark:bg-yellow-900/20 sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200">Run Registration
                                    Unavailable</h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p v-if="!currentSeason">
                                        There is no active season currently. Run registration will be available when a
                                        season is active.
                                    </p>
                                    <p v-else-if="!isSignedUpForSeason">
                                        Your team must be signed up for the current season ({{ currentSeason.name }}) to
                                        register runs.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Team Members -->
                        <div>
                            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                                <div class="p-6">
                                    <TeamMemberManager :team="team" :available-roles="availableRoles"
                                        :user-permissions="permissions" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
