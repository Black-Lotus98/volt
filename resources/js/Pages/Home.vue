<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DarkModeBtn from "@/Components/ui/DarkModeBtn.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});


function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>


<template>

    <Head title="Welcome" />
    <div class="relative bg-gradient-image-light min-h-screen bg-cover
    bg-center flex flex-col items-center justify-center text-black/90">
        <header class="absolute top-6 items-center w-11/12">
            <nav v-if="canLogin" class="hidden lg:grid lg:grid-cols-2">
                <div v-if="$page.props.auth.user" class="col-start-2 flex justify-end mr-8 ">
                    <Link :href="route('dashboard')"
                        class="rounded-md px-3 py-2' text-white  ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                    Dashboard
                    </Link>
                </div>
                <template v-else>
                    <div class="col-start-2 flex justify-end mr-8">
                        <Link :href="route('login')"
                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                        Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]">
                        Register
                        </Link>
                    </div>
                </template>
            </nav>
        </header>

        <main class=" flex w-full justify-center align-middle">

            <div class="flex-grow flex flex-col justify-start m-5
            xsm:w-3/4 md:w-1/3 xsm:flex-grow-0
            relative bg-white/10 border border-white/70 shadow-md py-10 px-4
             text-white backdrop-blur-lg drop-shadow-[0px_4px_34px_rgba(255,255,255,0.25)]
              overflow-hidden rounded-2xl">

                <h1 class="text-center text-2xl font-semibold mb-6">
                    <span v-if="$page.props.auth.user">
                        Please use the dashboard to access the system
                    </span>
                    <h1 v-else>
                        Volt
                    </h1>
                </h1>

                <div class="grid gap-y-5 mb-4 text-center">
                    <template v-if="$page.props.auth.user">

                        <Link :href="route('dashboard')"
                            class="w-full  p-4 mb-4 bg-white text-black-2 rounded-full font-medium cursor-pointer">
                        Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="w-full text-white p-4 cursor-pointer border-2 border-solid border-white/70
                            rounded-full text-lg">
                        Login
                        </Link>
                        <Link :href="route('register')"
                            class="w-full  p-4 mb-4 bg-white text-black-2 rounded-full font-medium cursor-pointer">
                        Register Request
                        </Link>
                    </template>
                </div>
                <div class="flex flex-col gap-3 w-full items-center justify-center dark:text-white">

                </div>
            </div>
        </main>
    </div>
</template>
