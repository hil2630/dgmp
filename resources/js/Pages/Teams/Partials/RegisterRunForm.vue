<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    team: Object,
    currentSeason: Object,
    dungeons: Array,
});

const form = useForm({
    season_id: props.currentSeason?.id || '',
    dungeon_name: '',
    key_level: '',
    time_taken_seconds: '',
    warcraft_log_url: '',
    status: 'completed',
});

const timeMinutes = ref('');
const timeSeconds = ref('');

// Convert minutes and seconds to total seconds
const updateTimeInSeconds = () => {
    const minutes = parseInt(timeMinutes.value) || 0;
    const seconds = parseInt(timeSeconds.value) || 0;
    form.time_taken_seconds = (minutes * 60) + seconds;
};

const registerRun = () => {
    updateTimeInSeconds();
    form.post(route('runs.store'), {
        errorBag: 'registerRun',
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            timeMinutes.value = '';
            timeSeconds.value = '';
        },
    });
};
</script>

<template>
    <FormSection @submitted="registerRun">
        <template #title>
            Register New Run
        </template>

        <template #description>
            Register a new mythic plus run for your team. All runs will be reviewed by administrators before being
            approved.
        </template>

        <template #form>
            <!-- Season (read-only) -->
            <div class="col-span-6">
                <InputLabel for="season" value="Season" />
                <div
                    class="px-3 py-2 mt-1 text-gray-900 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    {{ currentSeason.name }}
                </div>
            </div>

            <!-- Dungeon -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="dungeon_name" value="Dungeon" />
                <select id="dungeon_name" v-model="form.dungeon_name"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="">Select a dungeon</option>
                    <option v-for="dungeon in dungeons" :key="dungeon" :value="dungeon">
                        {{ dungeon }}
                    </option>
                </select>
                <InputError :message="form.errors.dungeon_name" class="mt-2" />
            </div>

            <!-- Key Level -->
            <div class="col-span-6 sm:col-span-2">
                <InputLabel for="key_level" value="Key Level" />
                <TextInput id="key_level" v-model="form.key_level" type="number" class="block w-full mt-1" min="1"
                    placeholder="15" />
                <InputError :message="form.errors.key_level" class="mt-2" />
            </div>

            <!-- Time Taken -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel value="Time Taken" />
                <div class="flex mt-1 space-x-2">
                    <div class="flex-1">
                        <TextInput v-model="timeMinutes" type="number" class="block w-full" placeholder="Minutes"
                            min="0" @input="updateTimeInSeconds" />
                    </div>
                    <span class="flex items-center text-gray-500">:</span>
                    <div class="flex-1">
                        <TextInput v-model="timeSeconds" type="number" class="block w-full" placeholder="Seconds"
                            min="0" max="59" @input="updateTimeInSeconds" />
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Enter the total time taken to complete the dungeon</p>
                <InputError :message="form.errors.time_taken_seconds" class="mt-2" />
            </div>

            <!-- Warcraft Log URL -->
            <div class="col-span-6">
                <InputLabel for="warcraft_log_url" value="Warcraft Log URL (Optional)" />
                <TextInput id="warcraft_log_url" v-model="form.warcraft_log_url" type="url" class="block w-full mt-1"
                    placeholder="https://www.warcraftlogs.com/reports/..." />
                <InputError :message="form.errors.warcraft_log_url" class="mt-2" />
            </div>

            <!-- Status -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="status" value="Status" />
                <select id="status" v-model="form.status"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="in_progress">In Progress</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Run registered successfully! It will be reviewed by administrators.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register Run
            </PrimaryButton>
        </template>
    </FormSection>
</template>
