<template>
    <nav v-if="links.length > 3" class="flex items-center justify-between mt-4">
        <ul class="inline-flex -space-x-px">
            <li v-for="(link, key) in links" :key="key">
                <span
                    v-if="!link.url"
                    class="px-3 py-2 leading-tight text-gray-400 bg-white border border-gray-300 cursor-default"
                    v-html="link.label"
                />
                <a
                    v-else
                    :href="link.url"
                    class="px-3 py-2 leading-tight border border-gray-300 hover:bg-gray-100"
                    :class="{
                        'text-indigo-600 font-bold bg-gray-200': link.active,
                        'text-gray-500': !link.active
                    }"
                    v-html="link.label"
                />
            </li>
        </ul>
    </nav>
</template>

<script setup>
defineProps({
    links: Array
});
</script>
