<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CardContent, Card, CardHeader } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import SharePhoneBookDialog from '@/components/dialog/phoneBook/SharePhoneBookDialog.vue';
import { ref } from 'vue';
import { useToast } from '@/components/ui/toast';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { PhoneBook, PhoneBookSharing } from '@/pages/phoneBook/type';
import ConfirmationDialog from '@/components/dialog/ConfirmationDialog.vue';

interface Props {
    phoneBooks: PhoneBook[];
    users: User[];
    phoneBookSharing: PhoneBookSharing;
}

const { phoneBooks, users, phoneBookSharing } = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Phone Books',
        href: route('phonebooks.index'),
    },
];

const { toast } = useToast();
const isShareDialogOpen = ref(false);
const selectedPhoneBook = ref<number | null>(null);
const isDeleteDialogOpen = ref(false);
const phoneBookToDelete = ref<number | null>(null);

const openShareDialog = (phoneBookId: number) => {
    selectedPhoneBook.value = phoneBookId;
    isShareDialogOpen.value = true;
};

const deletePhoneBook = (phoneBookId: number) => {
    phoneBookToDelete.value = phoneBookId;
    isDeleteDialogOpen.value = true;
};

const confirmDelete = async () => {
    if (!phoneBookToDelete.value) return;

    try {
        const response = await axios.delete(route('phone-books.delete', phoneBookToDelete.value));

        if (response.status !== 200) {
            toast({
                title: 'Error',
                description: 'Failed to delete contact',
                variant: 'destructive',
            });

            return;
        }

        toast({
            title: 'Success',
            description: 'Contact deleted successfully',
        });

        router.reload();
    } catch (error) {
        toast({
            title: 'Error',
            description: 'Failed to delete contact',
            variant: 'destructive',
        });
    }
};
</script>

<template>
    <Head title="Phone Books" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <h2 class="text-xl font-semibold">Phone Books</h2>
                    <Link :href="route('phonebooks.create')" class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90">
                        Create
                    </Link>
                </CardHeader>
                <CardContent>
                    <Table v-if="phoneBooks.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Phone Number</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="phoneBook in phoneBooks" :key="phoneBook.id">
                                <TableCell>{{ phoneBook.name }}</TableCell>
                                <TableCell>{{ phoneBook.phone_number }}</TableCell>
                                <TableCell class="space-x-2 text-right">
                                    <Link
                                        :href="route('phonebooks.edit', phoneBook.id)"
                                        class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
                                    >
                                        Edit
                                    </Link>
                                    <Button variant="outline" size="sm" @click="openShareDialog(phoneBook.id)"> Share </Button>
                                    <Button variant="destructive" size="sm" @click="deletePhoneBook(phoneBook.id)"> Delete </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-else class="py-8 text-center text-gray-500">No contacts found. Create your first contact using the button above.</div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
    <SharePhoneBookDialog
        v-model:is-open="isShareDialogOpen"
        :phone-book-id="selectedPhoneBook"
        :users="users"
        :phone-book-sharing="phoneBookSharing"
    />
    <ConfirmationDialog
        v-model:is-open="isDeleteDialogOpen"
        title="Delete Contact"
        message="Are you sure you want to delete this contact?"
        confirm-text="Delete"
        @confirm="confirmDelete"
    />
</template>
