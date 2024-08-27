<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps(['items', 'currentUrl'])

const isOpen = ref(false)

const page = usePage()
const currentUrl = page.url

const isActive = (href) => {
    return currentUrl.startsWith(href) || currentUrl === href;
};

</script>

<template>
    <ul class="pl-4">
        <li v-for="(child, index) in items" :key="index" class="mb-2">
            <Link :href="child.route"
                class="flex items-center gap-2.5 py-2 px-4 text-sm  duration-300 ease-in-out hover:text-bodydark1"
                :class="{
                    'font-extrabold text-bodydark1': currentUrl === child.route,
                    'text-bodydark2': currentUrl !== child.route
                }">
            <span v-html="child.icon"></span>
            {{ $t(child.label) }}
            </Link>
        </li>
    </ul>
</template>
