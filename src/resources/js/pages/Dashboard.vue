<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
import { PhoneBookStats } from '@/pages/type';

const { total, sharedByUser, sharedWithUser } = defineProps<PhoneBookStats>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card class="flex flex-col">
                    <CardHeader class="pb-2">
                        <h3 class="text-lg font-medium">Your Phone Books</h3>
                    </CardHeader>
                    <CardContent class="flex flex-1 flex-col justify-between">
                        <div class="text-3xl font-bold">{{ total }}</div>
                        <p class="text-sm text-muted-foreground">Total contacts you've created</p>
                        <Link :href="route('phonebooks.index')" class="mt-4 text-sm text-primary hover:underline">
                            View all contacts →
                        </Link>
                    </CardContent>
                </Card>

                <Card class="flex flex-col">
                    <CardHeader class="pb-2">
                        <h3 class="text-lg font-medium">Shared by You</h3>
                    </CardHeader>
                    <CardContent class="flex flex-1 flex-col justify-between">
                        <div class="text-3xl font-bold">{{ sharedByUser }}</div>
                        <p class="text-sm text-muted-foreground">Contacts you've shared with others</p>
                        <Link :href="route('phonebooks.index')" class="mt-4 text-sm text-primary hover:underline">
                            Manage sharing →
                        </Link>
                    </CardContent>
                </Card>

                <Card class="flex flex-col">
                    <CardHeader class="pb-2">
                        <h3 class="text-lg font-medium">Shared with You</h3>
                    </CardHeader>
                    <CardContent class="flex flex-1 flex-col justify-between">
                        <div class="text-3xl font-bold">{{ sharedWithUser }}</div>
                        <p class="text-sm text-muted-foreground">Contacts others have shared with you</p>
                        <Link :href="route('sharedPhonebooks.index')" class="mt-4 text-sm text-primary hover:underline">
                            View shared contacts →
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <Card class="flex-1">
                <CardHeader>
                    <h2 class="text-xl font-semibold">Recent Activity</h2>
                </CardHeader>
                <CardContent>
                    <p class="text-center text-muted-foreground py-12">Your recent activity will appear here</p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
