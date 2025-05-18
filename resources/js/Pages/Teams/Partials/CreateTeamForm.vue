<script setup>
import { useForm } from '@inertiajs/vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

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
    <FormSection @submitted="createTeam">
        <template #title>
            <div class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13A4 4 0 0121 7v6m0 0a4 4 0 01-3 3.87m0 0V20m0 0h-6m-6 0v-2a4 4 0 013-3.87m0 0V7a4 4 0 013-3.87"/>
                </svg>
                Create New Team
            </div>
        </template>

        <template #description>
            <p class="text-gray-600 dark:text-gray-400">Add your team members with their Battle.net tags. Each member will be automatically added to the team with their specific role.</p>
        </template>

        <template #form>
            <div class="space-y-6">
                <div>
                    <InputLabel for="name" value="Team Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full mt-1"
                        autofocus
                        placeholder="Enter your team name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Tank -->
                    <div>
                        <InputLabel for="tank" value="Tank" />
                        <TextInput
                            id="tank"
                            v-model="form.members.tank.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="BattleTag#1234"
                        />
                        <InputError :message="form.errors['members.tank.battlenet_tag']" class="mt-2" />
                    </div>

                    <!-- Healer -->
                    <div>
                        <InputLabel for="healer" value="Healer" />
                        <TextInput
                            id="healer"
                            v-model="form.members.healer.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="BattleTag#1234"
                        />
                        <InputError :message="form.errors['members.healer.battlenet_tag']" class="mt-2" />
                    </div>

                    <!-- DPS 1 -->
                    <div>
                        <InputLabel for="dps1" value="DPS 1" />
                        <TextInput
                            id="dps1"
                            v-model="form.members.dps1.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="BattleTag#1234"
                        />
                        <InputError :message="form.errors['members.dps1.battlenet_tag']" class="mt-2" />
                    </div>

                    <!-- DPS 2 -->
                    <div>
                        <InputLabel for="dps2" value="DPS 2" />
                        <TextInput
                            id="dps2"
                            v-model="form.members.dps2.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="BattleTag#1234"
                        />
                        <InputError :message="form.errors['members.dps2.battlenet_tag']" class="mt-2" />
                    </div>

                    <!-- DPS 3 -->
                    <div>
                        <InputLabel for="dps3" value="DPS 3" />
                        <TextInput
                            id="dps3"
                            v-model="form.members.dps3.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="BattleTag#1234"
                        />
                        <InputError :message="form.errors['members.dps3.battlenet_tag']" class="mt-2" />
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-end">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="px-8">
                    Create Team
                </PrimaryButton>
            </div>
        </template>
    </FormSection>
</template>
