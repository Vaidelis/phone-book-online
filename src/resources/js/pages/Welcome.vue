<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import FeatureCard from '@/components/card/welcome/FeatureCard.vue';
import AppFooter from "@/components/AppFooter.vue";
</script>

<template>
    <Head title="PhoneBook - Keep your contacts organized and shareable">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center">
        <header class="sticky top-0 z-10 w-full backdrop-blur-sm bg-[#FDFDFC]/80 dark:bg-[#0a0a0a]/80 border-b border-[#19140015] dark:border-[#3E3E3A]/30 py-3">
            <div class="mx-auto w-full max-w-[335px] lg:max-w-4xl px-4">
                <nav class="flex items-center justify-between gap-4">
                    <div class="flex items-center">
                        <AppLogo class="h-8 w-8 mr-2" />
                        <span class="font-semibold text-lg">PhoneBook</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] hover:bg-[#19140010] transition-colors dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b] dark:hover:bg-[#3E3E3A]/20"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] hover:bg-[#19140008] transition-colors dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A] dark:hover:bg-[#3E3E3A]/10"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="route('register')"
                                class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] hover:bg-[#19140010] transition-colors dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b] dark:hover:bg-[#3E3E3A]/20"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </nav>
            </div>
        </header>
        <div class="duration-750 starting:opacity-0 flex w-full flex-col items-center justify-center opacity-100 transition-opacity gap-10 lg:grow">
            <div class="text-center max-w-3xl mx-auto mt-8 px-4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Your Contacts, Organized and Shareable</h1>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Create, manage, and share phone books with friends, family, or colleagues.
                    Never lose an important contact again.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-5xl mx-auto mt-12">
                <FeatureCard
                    title="Create Phone Books"
                    description="Organize contacts by categories, projects, or any way you want."
                    icon="phone-book"
                />
                <FeatureCard
                    title="Share with Others"
                    description="Securely share contact lists with exactly who you choose."
                    icon="share"
                />
                <FeatureCard
                    title="Access Anywhere"
                    description="Your contacts stay synchronized across all your devices."
                    icon="device-mobile"
                />
            </div>

            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-8 w-full max-w-3xl mx-auto mt-12 text-center">
                <h2 class="text-2xl font-bold mb-4">Ready to Start?</h2>
                <p class="mb-6">Join thousands of users who trust PhoneBook for contact management.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-lg bg-indigo-600 px-6 py-2.5 font-medium text-white hover:bg-indigo-700"
                    >
                        Create an Account
                    </Link>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-lg border border-gray-300 dark:border-gray-600 px-6 py-2.5 font-medium hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        Sign In
                    </Link>
                </div>
            </div>
        </div>

        <AppFooter />

        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
