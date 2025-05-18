<script setup>
import { ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import DialogModal from '@/Components/DialogModal.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

const props = defineProps({
    team: Object,
    availableRoles: Array,
    userPermissions: Object,
});

const page = usePage();

const addTeamMemberForm = useForm({
    battlenet_tag: '',
    role: 'tank',
});

const updateRoleForm = useForm({
    role: null,
});

const leaveTeamForm = useForm({});
const removeTeamMemberForm = useForm({});

const currentlyManagingRole = ref(false);
const managingRoleFor = ref(null);
const confirmingLeavingTeam = ref(false);
const teamMemberBeingRemoved = ref(null);
const showAddMemberModal = ref(false);
const showSuccessMessage = ref(false);
const successMessage = ref('');

const isTeamOwner = computed(() => {
    return page.props.auth.user.id === props.team.owner.id;
});

const canManageMember = (member) => {
    // Team owner can manage everyone except themselves
    if (isTeamOwner.value && member.id !== page.props.auth.user.id) {
        return true;
    }
    return false;
};

const addTeamMember = () => {
    addTeamMemberForm.post(route('team-members.store', props.team), {
        errorBag: 'addTeamMember',
        preserveScroll: true,
        onSuccess: () => {
            addTeamMemberForm.reset();
            showAddMemberModal.value = false;
        },
    });
};

const cancelTeamInvitation = (invitation) => {
    router.delete(route('team-invitations.destroy', invitation), {
        preserveScroll: true,
    });
};

const manageRole = (teamMember) => {
    if (!canManageMember(teamMember)) return;
    managingRoleFor.value = teamMember;
    updateRoleForm.role = teamMember.membership?.role;
    currentlyManagingRole.value = true;
};

const updateRole = (user) => {
    updateRoleForm.role = user.membership?.role;
    updateRoleForm.put(route('team-members.update', [props.team.id, user.id]), {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = `Role updated to ${displayableRole(updateRoleForm.role)}`;
            showSuccessMessage.value = true;
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 3000);
        },
    });
};

const confirmLeavingTeam = () => {
    confirmingLeavingTeam.value = true;
};

const leaveTeam = () => {
    leaveTeamForm.delete(route('team-members.destroy', [props.team, page.props.auth.user]));
};

const confirmTeamMemberRemoval = (teamMember) => {
    if (!canManageMember(teamMember)) return;
    teamMemberBeingRemoved.value = teamMember;
};

const removeTeamMember = () => {
    removeTeamMemberForm.delete(route('team-members.destroy', [props.team, teamMemberBeingRemoved.value]), {
        errorBag: 'removeTeamMember',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => teamMemberBeingRemoved.value = null,
    });
};

const displayableRole = (role) => {
    if (!role) return 'Unassigned';

    // Find the role in availableRoles
    const roleObj = props.availableRoles.find(r => r.key === role);
    return roleObj ? roleObj.name : 'Unassigned';
};

const getRoleColor = (role) => {
    const colors = {
        tank: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        healer: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        dps1: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        dps2: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        dps3: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        owner: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    };
    return colors[role] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};
</script>

<template>
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Team Members</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your team members and their roles</p>
            </div>
            <div v-if="isTeamOwner" class="flex items-center space-x-4">
                <button
                    @click="showAddMemberModal = true"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Member
                </button>
            </div>
        </div>

        <!-- Team Members List -->
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li v-for="user in team.users" :key="user.id" class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img class="w-10 h-10 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ user.name }}
                                </div>
                                <div class="flex items-center mt-1 space-x-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getRoleColor(user.membership?.role)"
                                    >
                                        {{ displayableRole(user.membership?.role) }}
                                    </span>
                                    <span v-if="user.id === team.owner.id" class="text-xs text-gray-500 dark:text-gray-400">
                                        Team Owner
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <template v-if="canManageMember(user)">
                                <select
                                    v-model="user.membership.role"
                                    @change="updateRole(user)"
                                    class="text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option v-for="role in availableRoles" :key="role.key" :value="role.key">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </template>
                            <button
                                v-if="$page.props.auth.user.id === user.id"
                                class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                @click="confirmLeavingTeam"
                            >
                                Leave
                            </button>
                            <button
                                v-else-if="canManageMember(user)"
                                class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                @click="confirmTeamMemberRemoval(user)"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Pending Invitations -->
        <div v-if="team.team_invitations?.length > 0 && isTeamOwner" class="mt-8">
            <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Pending Invitations</h3>
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="invitation in team.team_invitations" :key="invitation.id" class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full dark:bg-gray-700">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ invitation.battlenet_tag }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Pending Invitation
                                    </div>
                                </div>
                            </div>
                            <button
                                class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                @click="cancelTeamInvitation(invitation)"
                            >
                                Cancel
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Add Member Modal -->
        <DialogModal :show="showAddMemberModal" @close="showAddMemberModal = false">
            <template #title>
                Add Team Member
            </template>

            <template #content>
                <form @submit.prevent="addTeamMember" class="space-y-4">
                    <div>
                        <InputLabel for="battlenet_tag" value="Battle.net Tag" />
                        <TextInput
                            id="battlenet_tag"
                            v-model="addTeamMemberForm.battlenet_tag"
                            type="text"
                            class="block w-full mt-1"
                            placeholder="Username#1234"
                        />
                        <InputError :message="addTeamMemberForm.errors.battlenet_tag" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Role" />
                        <SelectInput
                            id="role"
                            v-model="addTeamMemberForm.role"
                            class="block w-full mt-1"
                        >
                            <option value="tank">Tank</option>
                            <option value="healer">Healer</option>
                            <option value="dps1">DPS</option>
                            <option value="dps2">DPS</option>
                            <option value="dps3">DPS</option>
                        </SelectInput>
                        <InputError :message="addTeamMemberForm.errors.role" class="mt-2" />
                    </div>
                </form>
            </template>

            <template #footer>
                <SecondaryButton @click="showAddMemberModal = false">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': addTeamMemberForm.processing }"
                    :disabled="addTeamMemberForm.processing"
                    @click="addTeamMember"
                >
                    Add Member
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Role Management Modal -->
        <DialogModal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
            <template #title>
                Change Role for {{ managingRoleFor?.name }}
            </template>

            <template #content>
                <div v-if="managingRoleFor" class="space-y-4">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer dark:border-gray-700">
                        <button
                            v-for="role in availableRoles"
                            :key="role.key"
                            type="button"
                            class="relative inline-flex w-full px-4 py-3 rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            :class="{
                                'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': role !== availableRoles[0],
                                'rounded-b-none': role !== availableRoles[availableRoles.length - 1],
                                'bg-indigo-50 dark:bg-indigo-900/20': updateRoleForm.role === role.key
                            }"
                            @click="updateRoleForm.role = role.key"
                        >
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400" :class="{ 'font-semibold': updateRoleForm.role === role.key }">
                                    {{ displayableRole(role.key) }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="currentlyManagingRole = false">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateRoleForm.processing }"
                    :disabled="updateRoleForm.processing"
                    @click="updateRole"
                >
                    Save Changes
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Leave Team Confirmation Modal -->
        <ConfirmationModal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
            <template #title>
                Leave Team
            </template>

            <template #content>
                Are you sure you would like to leave this team?
            </template>

            <template #footer>
                <SecondaryButton @click="confirmingLeavingTeam = false">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': leaveTeamForm.processing }"
                    :disabled="leaveTeamForm.processing"
                    @click="leaveTeam"
                >
                    Leave Team
                </PrimaryButton>
            </template>
        </ConfirmationModal>

        <!-- Remove Team Member Confirmation Modal -->
        <ConfirmationModal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                Remove Team Member
            </template>

            <template #content>
                Are you sure you would like to remove this person from the team?
            </template>

            <template #footer>
                <SecondaryButton @click="teamMemberBeingRemoved = null">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': removeTeamMemberForm.processing }"
                    :disabled="removeTeamMemberForm.processing"
                    @click="removeTeamMember"
                >
                    Remove Member
                </PrimaryButton>
            </template>
        </ConfirmationModal>

        <!-- Success Message -->
        <div v-if="showSuccessMessage" class="fixed px-4 py-2 text-white bg-green-500 rounded-lg shadow-lg bottom-4 right-4">
            {{ successMessage }}
        </div>
    </div>
</template>
