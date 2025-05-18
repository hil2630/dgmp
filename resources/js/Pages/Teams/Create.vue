<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    name: '',
    members: {
        tank: { battlenet_tag: '' },
        healer: { battlenet_tag: '' },
        dps1: { battlenet_tag: '' },
        dps2: { battlenet_tag: '' },
        dps3: { battlenet_tag: '' },
    }
});

const createTeam = () => {
    form.post(route('teams.store'), {
        errorBag: 'createTeam',
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Create Team">
        <!-- Hero Section -->
        <div class="relative bg-cover bg-center min-h-[300px] flex flex-col justify-center items-center rounded-b-3xl shadow-xl mb-12 overflow-hidden" style="background-image: url('https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png')">
            <div class="absolute inset-0 pointer-events-none bg-black/50"></div>
            <div class="relative z-10 px-6 py-16 text-center">
                <h1 class="mb-4 text-4xl font-extrabold text-white md:text-5xl drop-shadow-lg">Create Your Team</h1>
                <p class="mb-6 text-lg text-indigo-100 md:text-2xl">Assemble your M+ team and start competing!</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8">
            <form @submit.prevent="createTeam" class="space-y-8">
                <!-- Team Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Team Name</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Enter your team name"
                        required
                    />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <!-- Team Members Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Tank -->
                    <div class="relative">
                        <label for="tank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tank</label>
                        <div class="relative mt-1 rounded-lg shadow-sm">
                            <input
                                type="text"
                                id="tank"
                                v-model="form.members.tank.battlenet_tag"
                                class="block w-full pl-10 border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="BattleTag#1234"
                                required
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <p v-if="form.errors['members.tank.battlenet_tag']" class="mt-2 text-sm text-red-600">{{ form.errors['members.tank.battlenet_tag'] }}</p>
                    </div>

                    <!-- Healer -->
                    <div class="relative">
                        <label for="healer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Healer</label>
                        <div class="relative mt-1 rounded-lg shadow-sm">
                            <input
                                type="text"
                                id="healer"
                                v-model="form.members.healer.battlenet_tag"
                                class="block w-full pl-10 border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="BattleTag#1234"
                                required
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <p v-if="form.errors['members.healer.battlenet_tag']" class="mt-2 text-sm text-red-600">{{ form.errors['members.healer.battlenet_tag'] }}</p>
                    </div>

                    <!-- DPS 1 -->
                    <div class="relative">
                        <label for="dps1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DPS 1</label>
                        <div class="relative mt-1 rounded-lg shadow-sm">
                            <input
                                type="text"
                                id="dps1"
                                v-model="form.members.dps1.battlenet_tag"
                                class="block w-full pl-10 border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="BattleTag#1234"
                                required
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <p v-if="form.errors['members.dps1.battlenet_tag']" class="mt-2 text-sm text-red-600">{{ form.errors['members.dps1.battlenet_tag'] }}</p>
                    </div>

                    <!-- DPS 2 -->
                    <div class="relative">
                        <label for="dps2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DPS 2</label>
                        <div class="relative mt-1 rounded-lg shadow-sm">
                            <input
                                type="text"
                                id="dps2"
                                v-model="form.members.dps2.battlenet_tag"
                                class="block w-full pl-10 border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="BattleTag#1234"
                                required
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <p v-if="form.errors['members.dps2.battlenet_tag']" class="mt-2 text-sm text-red-600">{{ form.errors['members.dps2.battlenet_tag'] }}</p>
                    </div>

                    <!-- DPS 3 -->
                    <div class="relative">
                        <label for="dps3" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DPS 3</label>
                        <div class="relative mt-1 rounded-lg shadow-sm">
                            <input
                                type="text"
                                id="dps3"
                                v-model="form.members.dps3.battlenet_tag"
                                class="block w-full pl-10 border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="BattleTag#1234"
                                required
                            />
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <p v-if="form.errors['members.dps3.battlenet_tag']" class="mt-2 text-sm text-red-600">{{ form.errors['members.dps3.battlenet_tag'] }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Create Team
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
