<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem, type SharedData, type User} from '@/types';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import axios from 'axios';
import InputError from "@/components/InputError.vue";
import { useToast } from '@/components/ui/toast';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Phone Books',
        href: route('phonebooks.index'),
    },
    {
        title: 'Create Phone Book',
        href: route('phonebooks.create'),
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const { toast } = useToast();

const form = useForm({
    name: '',
    phone_number: '',
});

const submit = async () => {
    try {
        form.processing = true;

        const response = await axios.post(route('phone-books.store'), {
            name: form.name,
            phone_number: form.phone_number,
            user_id: user.id,
        });

        if (response.status === 200) {
            toast({
                title: 'Success',
                description: 'Contact created successfully',
            });

            router.visit(route('phonebooks.index'));
        }
    } catch (error: any) {
        if (error.response?.status === 422) {
            form.errors = error.response.data.errors;
        } else {
            toast({
                title: 'Error',
                description: 'Failed to create contact',
                variant: 'destructive',
            });
        }
    } finally {
        form.processing = false;
    }
};
</script>

<template>
    <Head title="Create Phone Book" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <h2 class="text-xl font-semibold">Create New Contact</h2>
                </CardHeader>
                <form @submit.prevent="submit">
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                :disabled="form.processing"
                                :class="{ 'border-red-500': form.errors.name }"
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="phone_number">Phone Number</Label>
                            <Input
                                id="phone_number"
                                v-model="form.phone_number"
                                type="text"
                                :disabled="form.processing"
                                :class="{ 'border-red-500': form.errors.phone_number }"
                            />
                            <InputError :message="form.errors.phone_number" />
                        </div>
                    </CardContent>
                    <CardFooter class="flex justify-end space-x-2">
                        <Button
                            type="button"
                            variant="outline"
                            :disabled="form.processing"
                            @click="$inertia.visit(route('phonebooks.index'))"
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                        >
                            Create
                        </Button>
                    </CardFooter>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
