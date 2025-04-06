<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem, type SharedData, type User} from '@/types';
import {Head, usePage} from '@inertiajs/vue3';
import { CardContent, Card, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"
import { useToast } from "@/components/ui/toast"
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { SharedPhoneBook } from "@/pages/sharedPhoneBooks/type";
import { ref } from 'vue';
import ConfirmationDialog from "@/components/dialog/ConfirmationDialog.vue";

interface Props {
    phoneBooks: SharedPhoneBook[];
}

const { phoneBooks } = defineProps<Props>();

const { toast } = useToast();
const isProcessing = ref<Set<number>>(new Set());
const isConfirmDialogOpen = ref(false);
const phoneBookToUnsubscribe = ref<number | null>(null);

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shared Phone Books',
        href: '/shared-phone-books',
    },
];

const openUnsubscribeDialog = (phoneBookId: number) => {
    phoneBookToUnsubscribe.value = phoneBookId;
    isConfirmDialogOpen.value = true;
};

const confirmUnsubscribe = async () => {
    if (!phoneBookToUnsubscribe.value) return;

    await unsubscribe(phoneBookToUnsubscribe.value);
};

const unsubscribe = async (phoneBookId: number) => {
    if (isProcessing.value.has(phoneBookId)) return;

    try {
        isProcessing.value.add(phoneBookId);

        const response = await axios.post(route('shared-phone-books.unshare', phoneBookId), {
            shared_user_id: user.id
        });

        if (response.status !== 200) {
            toast({
                title: "Error",
                description: "Failed to unsubscribe from phone book",
                variant: "destructive"
            });
        }

        toast({
            title: "Success",
            description: "Unsubscribed successfully"
        });
        router.reload();

    } catch (error) {
        toast({
            title: "Error",
            description: "Failed to unsubscribe from phone book",
            variant: "destructive"
        });
    } finally {
        isProcessing.value.delete(phoneBookId);
    }
};
</script>

<template>
    <Head title="Shared Phone Books" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <h2 class="text-xl font-semibold">Shared Phone Books</h2>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Phone Number</TableHead>
                                <TableHead>Phone Book Owner</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="phoneBook in phoneBooks" :key="phoneBook.id">
                                <TableCell>{{ phoneBook.name }}</TableCell>
                                <TableCell>{{ phoneBook.phone_number }}</TableCell>
                                <TableCell>{{ phoneBook.owner_name }}</TableCell>
                                <TableCell class="text-right">
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        :disabled="isProcessing.has(phoneBook.id)"
                                        @click="openUnsubscribeDialog(phoneBook.id)"
                                    >
                                        {{ isProcessing.has(phoneBook.id) ? 'Processing...' : 'Unsubscribe' }}
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="phoneBooks.length === 0">
                                <TableCell colspan="4" class="text-center py-4">No shared phone books found</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
    <ConfirmationDialog
        v-model:is-open="isConfirmDialogOpen"
        title="Unsubscribe from Contact"
        message="Are you sure you want to unsubscribe from this shared contact?"
        confirm-text="Yes"
        @confirm="confirmUnsubscribe"
    />
</template>
