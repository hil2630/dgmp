<script setup>
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const isLoggedIn = page.props.auth && page.props.auth.user;

const donationForm = useForm({
    amount: '',
    guild: '',
    is_anonymous: false
});

const showDonationForm = ref(false);

const submitDonation = () => {
    donationForm.post(route('donations.store'), {
        onSuccess: () => {
            showDonationForm.value = false;
            donationForm.reset();
        }
    });
};

// Get data from props
const currentSeasonPot = page.props.currentSeasonPot;
const topDonators = page.props.topDonators;
</script>

<template>
  <Head title="Donations" />
  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-blue-900 to-gray-900">
    <!-- Navigation -->
    <nav class="fixed top-0 z-50 w-full border-b bg-gray-900/80 backdrop-blur-sm border-blue-500/20">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <img src="https://play-lh.googleusercontent.com/PuPFgmLam2WNyul3lUQywQT5Y5sPgL6VzWSUAdXOS1oIQwHYnrB_MyfXCOrR4LzZcjeP" alt="Battle.net" class="w-8 h-8" />
            <span class="ml-2 text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">DGMP</span>
          </div>
          <div class="flex items-center space-x-4">
            <a
              :href="route('donations')"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-100 transition-all duration-200 border border-blue-400 hover:bg-blue-500/20 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Donations
            </a>
            <a
              :href="isLoggedIn ? route('dashboard') : route('login.battlenet')"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <img src="https://play-lh.googleusercontent.com/PuPFgmLam2WNyul3lUQywQT5Y5sPgL6VzWSUAdXOS1oIQwHYnrB_MyfXCOrR4LzZcjeP" class="w-5 h-5 mr-2" alt="Battle.net" />
              {{ isLoggedIn ? 'Go to Dashboard' : 'Login' }}
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 overflow-hidden">
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/50 to-gray-900/50 mix-blend-multiply"></div>
        <img src="https://bnetcmsus-a.akamaihd.net/cms/blog_thumbnail/9k/9K28JRG5Z8V81717003856942.png" alt="Background" class="object-cover w-full h-full" />
      </div>
      <div class="relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-5xl font-extrabold tracking-tight text-transparent sm:text-6xl lg:text-7xl bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
            Tournament Donations
          </h1>
          <p class="max-w-2xl mx-auto mt-6 text-xl text-blue-100">
            Support the tournament and contribute to the prize pool. Donations are split between main tournament winners and fun on-day tournaments.
          </p>
          <div class="mt-8">
            <button
              v-if="isLoggedIn"
              @click="showDonationForm = true"
              class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Make a Donation
            </button>
            <a
              v-else
              :href="route('login.battlenet')"
              class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Login to Donate
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Donation Form Modal -->
    <div v-if="showDonationForm" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>

        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
              Make a Donation
            </h3>
            <form @submit.prevent="submitDonation" class="mt-6">
              <div class="space-y-4">
                <div>
                  <label for="amount" class="block text-sm font-medium text-blue-100">Amount (Gold)</label>
                  <input
                    type="number"
                    id="amount"
                    v-model="donationForm.amount"
                    class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    required
                    min="1"
                  />
                </div>

                <div>
                  <label for="guild" class="block text-sm font-medium text-blue-100">Guild (Optional)</label>
                  <input
                    type="text"
                    id="guild"
                    v-model="donationForm.guild"
                    class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <div class="flex items-center">
                  <input
                    type="checkbox"
                    id="is_anonymous"
                    v-model="donationForm.is_anonymous"
                    class="w-4 h-4 text-blue-600 border-gray-600 rounded focus:ring-blue-500"
                  />
                  <label for="is_anonymous" class="block ml-2 text-sm text-blue-100">
                    Make this donation anonymous
                  </label>
                </div>
              </div>

              <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse">
                <button
                  type="submit"
                  class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                  :disabled="donationForm.processing"
                >
                  {{ donationForm.processing ? 'Processing...' : 'Submit Donation' }}
                </button>
                <button
                  type="button"
                  @click="showDonationForm = false"
                  class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-blue-100 transition-all duration-200 bg-gray-700 border border-gray-600 rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Current Season Pot -->
    <div class="py-20 bg-gray-900">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold text-transparent sm:text-5xl bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
            Current Season Pot
          </h2>
          <p class="max-w-2xl mx-auto mt-4 text-xl text-blue-100">
            {{ currentSeasonPot?.season || 'No Active Season' }} - Total Prize Pool
          </p>
        </div>

        <div class="mt-16">
          <div class="p-8 transition-all duration-200 border bg-gray-800/50 hover:bg-gray-800/80 border-blue-500/20">
            <div class="text-center">
              <div class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                {{ currentSeasonPot?.total?.toLocaleString() || 0 }} Gold
              </div>
              <p class="mt-4 text-xl text-blue-100">Total Prize Pool</p>
            </div>

            <!-- Main Tournament Distribution -->
            <div class="mt-16">
              <h3 class="mb-8 text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
                Main Tournament Prizes ({{ currentSeasonPot?.mainTournament?.total?.toLocaleString() || 0 }} Gold)
              </h3>
              <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                <!-- First Place -->
                <div class="p-6 text-center transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 border-blue-500/20">
                  <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                    {{ currentSeasonPot?.mainTournament?.distribution?.first?.toLocaleString() || 0 }} Gold
                  </div>
                  <p class="mt-2 text-lg text-blue-100">First Place (50%)</p>
                </div>

                <!-- Second Place -->
                <div class="p-6 text-center transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 border-blue-500/20">
                  <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                    {{ currentSeasonPot?.mainTournament?.distribution?.second?.toLocaleString() || 0 }} Gold
                  </div>
                  <p class="mt-2 text-lg text-blue-100">Second Place (30%)</p>
                </div>

                <!-- Third Place -->
                <div class="p-6 text-center transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 border-blue-500/20">
                  <div class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                    {{ currentSeasonPot?.mainTournament?.distribution?.third?.toLocaleString() || 0 }} Gold
                  </div>
                  <p class="mt-2 text-lg text-blue-100">Third Place (20%)</p>
                </div>
              </div>
            </div>

            <!-- On-Day Tournaments -->
            <div class="mt-16">
              <h3 class="mb-8 text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
                On-Day Tournament Events ({{ currentSeasonPot?.onDayTournaments?.total?.toLocaleString() || 0 }} Gold)
              </h3>
              <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div v-for="tournament in currentSeasonPot?.onDayTournaments?.upcomingTournaments || []" :key="tournament.name"
                     class="p-6 text-center transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 border-blue-500/20">
                  <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                    {{ tournament.prize?.toLocaleString() || 0 }} Gold
                  </div>
                  <h4 class="mt-2 text-lg font-medium text-blue-100">{{ tournament.name }}</h4>
                  <p class="mt-2 text-sm text-blue-200">{{ tournament.description }}</p>
                  <p class="mt-4 text-sm text-blue-300">Tournament Day: {{ new Date(tournament.date).toLocaleDateString() }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Donators -->
    <div class="py-20 bg-gray-800">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold text-transparent sm:text-5xl bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
            Top Donators
          </h2>
          <p class="max-w-2xl mx-auto mt-4 text-xl text-blue-100">
            Our generous contributors who make these tournaments possible
          </p>
        </div>

        <div class="mt-16">
          <div class="overflow-hidden border border-blue-500/20">
            <table class="min-w-full divide-y divide-blue-500/20">
              <thead class="bg-gray-900/50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-blue-100 uppercase">
                    Rank
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-blue-100 uppercase">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-blue-100 uppercase">
                    Guild
                  </th>
                  <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-blue-100 uppercase">
                    Amount
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-blue-500/20 bg-gray-800/50">
                <tr v-for="(donator, index) in topDonators" :key="index" class="hover:bg-gray-700/50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-blue-100">
                      #{{ index + 1 }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-blue-100">
                      {{ donator.name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-blue-100">
                      {{ donator.guild }}
                    </div>
                  </td>
                  <td class="px-6 py-4 text-right whitespace-nowrap">
                    <div class="text-sm font-medium text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                      {{ donator.amount.toLocaleString() }} Gold
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="py-12 bg-gray-900 border-t border-blue-500/20">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <p class="text-blue-100">
            © 2024 Danish Guild Mythic Plus. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
