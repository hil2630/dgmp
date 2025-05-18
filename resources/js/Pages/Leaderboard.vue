<template>
    <AppLayout title="Leaderboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ bracket ? bracket.name + ' Leaderboard' : 'Mythic+ Leaderboard' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <input
                                v-model="search"
                                type="search"
                                placeholder="Search teams or dungeons..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        v-for="column in columns"
                                        :key="column.field"
                                        @click="sortBy(column.field)"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                    >
                                        {{ column.label }}
                                        <span v-if="sortField === column.field">
                                            {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="run in runs.data" :key="run.id">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ run.team.name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ run.dungeon_name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        +{{ run.key_level }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ formatTime(run.time_taken) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ formatDateTime(run.completed_at) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <Pagination :links="runs.links" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    bracket: Object,
    brackets: Array,
    runs: Object,
});

const search = ref('');
const sortField = ref('key_level');
const sortDirection = ref('desc');

const columns = [
    { field: 'team.name', label: 'Team' },
    { field: 'dungeon_name', label: 'Dungeon' },
    { field: 'key_level', label: 'Key Level' },
    { field: 'time_taken', label: 'Time' },
    { field: 'completed_at', label: 'Completed At' },
];

const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'desc';
    }
};

const formatTime = (seconds) => {
    return new Date(seconds * 1000).toISOString().substr(11, 8);
};

const formatDateTime = (date) => {
    return new Date(date).toLocaleString();
};

watch([search, sortField, sortDirection], () => {
    router.get(
        route('leaderboard', props.bracket?.id),
        {
            search: search.value,
            sort_field: sortField.value,
            sort_direction: sortDirection.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['runs'],
        }
    );
});
</script>
