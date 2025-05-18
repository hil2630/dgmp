<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    teams: Array,
});
</script>

<template>
    <AppLayout title="Teams">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Teams
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="team in teams" :key="team.id" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ team.name }}
                                    </h3>
                                    <Link
                                        v-if="team.user_id === $page.props.auth.user.id"
                                        :href="route('teams.show', team.id)"
                                        class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >
                                        Manage Team
                                    </Link>
                                </div>

                                <div class="space-y-4">
                                    <div v-for="member in team.users" :key="member.id" class="flex items-center">
                                        <img class="h-8 w-8 rounded-full" :src="member.profile_photo_url" :alt="member.name">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ member.name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ member.membership.role || 'Member' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
