<script setup>
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const isLoggedIn = page.props.auth && page.props.auth.user;

// Get active seasons from the page props
const activeSeasons = page.props.seasons || [];
</script>

<template>
  <Head title="Welcome" />
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
            Danish Guild Mythic Plus
          </h1>
          <p class="max-w-2xl mx-auto mt-6 text-xl text-blue-100">
            Compete for the highest key and prove your worth in the most challenging dungeons.
          </p>
          <div class="flex flex-col items-center justify-center mt-10 space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
      <a
              :href="isLoggedIn ? route('dashboard') : route('login.battlenet')"
              class="inline-flex items-center px-8 py-4 text-lg font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        <img src="https://play-lh.googleusercontent.com/PuPFgmLam2WNyul3lUQywQT5Y5sPgL6VzWSUAdXOS1oIQwHYnrB_MyfXCOrR4LzZcjeP" class="w-6 h-6 mr-2" alt="Battle.net" />
              {{ isLoggedIn ? 'Go to Dashboard' : 'Get Started with Battle.net' }}
            </a>
            <a href="#current-season" class="inline-flex items-center px-8 py-4 text-lg font-medium text-blue-100 transition-all duration-200 border border-blue-400 hover:bg-blue-500/20 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Learn More
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-gray-800">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold text-transparent sm:text-5xl bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
            Tournament Features
          </h2>
          <p class="max-w-2xl mx-auto mt-4 text-xl text-blue-100">
            Everything you need to know about the DGMP Tournament.
          </p>
        </div>

        <div class="mt-16">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
            <!-- Feature 1 -->
            <div class="p-8 transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 hover:scale-105 border-blue-500/20">
              <h3 class="text-xl font-bold text-blue-400">Team Competition</h3>
              <p class="mt-4 text-blue-100">
                Form teams with your guild members and compete together. Coordinate strategies and climb the ranks as a unit.
              </p>
            </div>

            <!-- Feature 2 -->
            <div class="p-8 transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 hover:scale-105 border-blue-500/20">
              <h3 class="text-xl font-bold text-blue-400">Seasonal Rewards</h3>
              <p class="mt-4 text-blue-100">
                Earn exclusive rewards based on your team's performance. From unique titles to special mounts, there's something for everyone.
              </p>
            </div>

            <!-- Feature 3 -->
            <div class="p-8 transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 hover:scale-105 border-blue-500/20">
              <h3 class="text-xl font-bold text-blue-400">Real-time Tracking</h3>
              <p class="mt-4 text-blue-100">
                Track your team's progress and compare performance with other teams in real-time. Get instant updates on rankings and achievements.
              </p>
            </div>

            <!-- Feature 4 -->
            <div class="p-8 transition-all duration-200 border bg-gray-900/50 hover:bg-gray-900/80 hover:scale-105 border-blue-500/20">
              <h3 class="text-xl font-bold text-blue-400">Community Events</h3>
              <p class="mt-4 text-blue-100">
                Participate in special community events and challenges. Win additional prizes and make your mark in the tournament history.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Current Season Section -->
    <div id="current-season" class="py-20 bg-gray-900">
      <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-4xl font-extrabold text-transparent sm:text-5xl bg-clip-text bg-gradient-to-r from-blue-400 to-blue-200">
            Current Seasons
          </h2>
          <p class="max-w-2xl mx-auto mt-4 text-xl text-blue-100">
            Join the competition and climb the ranks in the current Mythic+ seasons.
          </p>
        </div>

        <div class="mt-16">
          <div v-if="activeSeasons.length" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="season in activeSeasons" :key="season.id" class="p-8 transition-all duration-200 border bg-gray-800/50 hover:bg-gray-800/80 hover:scale-105 border-blue-500/20">
              <div class="flex items-center justify-center w-12 h-12 mb-6 bg-blue-500/20">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-blue-400">{{ season.name }}</h3>
              <p class="mt-4 text-blue-100">{{ season.description }}</p>
              <div class="mt-6 space-y-2">
                <div class="flex items-center text-sm text-blue-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Starts: {{ new Date(season.starts_at).toLocaleDateString() }}
                </div>
                <div class="flex items-center text-sm text-blue-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Time Limit: {{ season.time_limit_hours }} hours
                </div>
                <div class="flex items-center text-sm text-blue-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  Teams: {{ season.teams?.length || 0 }}
                </div>
              </div>
              <a
                v-if="isLoggedIn"
                :href="route('seasons.show', season.id)"
                class="inline-flex items-center justify-center w-full px-4 py-2 mt-6 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                View Season
              </a>
              <a
                v-else
                :href="route('login.battlenet')"
                class="inline-flex items-center justify-center w-full px-4 py-2 mt-6 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent hover:bg-blue-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <img src="https://play-lh.googleusercontent.com/PuPFgmLam2WNyul3lUQywQT5Y5sPgL6VzWSUAdXOS1oIQwHYnrB_MyfXCOrR4LzZcjeP" class="w-5 h-5 mr-2" alt="Battle.net" />
                Login to View
              </a>
            </div>
          </div>
          <div v-else class="p-8 text-center text-blue-100">
            No active seasons at the moment. Check back soon!
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
