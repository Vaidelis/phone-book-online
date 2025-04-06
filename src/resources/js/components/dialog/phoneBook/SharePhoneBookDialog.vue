<script setup lang="ts">
import { ref } from 'vue';
import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogDescription } from "@/components/ui/dialog";
import { type User } from '@/types';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useToast } from '@/components/ui/toast';
import { PhoneBookSharing } from "@/pages/phoneBook/type";

interface Props {
    isOpen: boolean;
    phoneBookId: number | null;
    users: User[];
    phoneBookSharing: PhoneBookSharing;
}

const { isOpen, phoneBookId, users, phoneBookSharing } = defineProps<Props>();

const emit = defineEmits<{
    'update:isOpen': [value: boolean];
}>();

const { toast } = useToast();

const isProcessing = ref(false);

const toggleSharing = async (userId: number) => {
    if (!phoneBookId) return;

    try {
        isProcessing.value = true;

        const isCurrentlyShared = isSharedWithUser(userId);
        let response;

        if (isCurrentlyShared) {
            response = await axios.delete(route('shared-phone-books.unshare', phoneBookId), {
                data: {
                    shared_user_id: userId
                }
            });
        } else {
            response = await axios.post(route('shared-phone-books.share', phoneBookId), {
                shared_user_id: userId
            });
        }

        if (response.status !== 200) {
            toast({
                title: 'Error',
                description: 'Failed to update sharing settings',
                variant: 'destructive',
            });
            return;
        }

        toast({
            title: 'Success',
            description: isCurrentlyShared
                ? 'Contact unshared successfully'
                : 'Contact shared successfully',
        });

        router.reload();
    } catch (error) {
        toast({
            title: 'Error',
            description: 'Failed to update sharing settings',
            variant: 'destructive',
        });
    } finally {
        isProcessing.value = false;
    }
};

const isSharedWithUser = (userId: number): boolean => {
    if (!phoneBookId) return false;
    const sharedWith = phoneBookSharing[phoneBookId] || [];
    return sharedWith.includes(userId);
};

const updateOpenState = (value: boolean) => {
    emit('update:isOpen', value);
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="updateOpenState">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Share Contact</DialogTitle>
                <DialogDescription>
                    Select users to share this contact with or remove access.
                </DialogDescription>
            </DialogHeader>

            <div class="py-4">
                <div v-if="users.length === 0" class="text-center py-4 text-gray-500">
                    No other users available to share with
                </div>

                <div v-else class="max-h-[300px] overflow-y-auto">
                    <div v-for="user in users" :key="user.id" class="flex items-center justify-between py-2 border-b">
                        <div class="flex-1">
                            <div class="font-medium">{{ user.name }}</div>
                            <div class="text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                        <Button
                            size="sm"
                            :variant="isSharedWithUser(user.id) ? 'destructive' : 'default'"
                            :disabled="isProcessing"
                            @click="toggleSharing(user.id)"
                        >
                            {{ isSharedWithUser(user.id) ? 'Unshare' : 'Share' }}
                        </Button>
                    </div>
                </div>
            </div>

            <DialogFooter>
                <Button @click="updateOpenState(false)" variant="outline">
                    Close
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
