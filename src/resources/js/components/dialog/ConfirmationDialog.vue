<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface Props {
    isOpen: boolean;
    title?: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'destructive' | 'default';
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Confirm Action',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    variant: 'destructive'
});

const emit = defineEmits<{
    'update:isOpen': [value: boolean];
    'confirm': [];
    'cancel': [];
}>();

const updateOpenState = (value: boolean) => {
    emit('update:isOpen', value);
};

const handleConfirm = () => {
    emit('update:isOpen', false);
    emit('confirm');
};

const handleCancel = () => {
    emit('update:isOpen', false);
    emit('cancel');
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="updateOpenState">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ message }}</DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="outline" @click="handleCancel">{{ cancelText }}</Button>
                <Button :variant="variant" @click="handleConfirm">{{ confirmText }}</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
