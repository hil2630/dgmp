<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    team: Object,
    permissions: Object,
});

const form = useForm({
    name: props.team.name,
});

const updateTeamName = () => {
    form.put(route('teams.update', props.team), {
        errorBag: 'updateTeamName',
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="p-6 bg-white border border-gray-200 shadow-xl dark:bg-gray-800 rounded-2xl dark:border-gray-700">

        <div class="flex items-center gap-4 mb-8">
            <img class="object-cover w-16 h-16 border-2 border-indigo-400 rounded-full" :src="team.owner.profile_photo_url" :alt="team.owner.name">
            <div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ team.owner.name }}</div>
                <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">Team Owner</div>
            </div>
        </div>
        <form @submit.prevent="updateTeamName" class="flex flex-col gap-4">
            <div>
                <InputLabel for="name" value="Team Name" />
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full mt-2 text-lg font-medium text-gray-900 transition border border-gray-300 rounded-lg dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    :disabled="!permissions.canUpdateTeam || form.processing"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div v-if="permissions.canUpdateTeam" class="flex justify-end">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
