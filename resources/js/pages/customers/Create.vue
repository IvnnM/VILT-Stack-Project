<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Customer',
        href: '/customers/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
});

const handleSubmit = () => {
    form.post('/customers');
};
</script>

<template>
    <Head title="Create a Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Create a Customer Page
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Customer Name">Customer Name</Label>
                    <Input v-model="form.name" type="text" placeholder="Name" />
                    <div class="text-xs text-red-600" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Customer Email">Email</Label>
                    <Input
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                    />
                    <div class="text-xs text-red-600" v-if="form.errors.email">
                        {{ form.errors.email }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Customer Phone">Phone</Label>
                    <Input
                        v-model="form.phone"
                        type="text"
                        placeholder="Phone"
                    />
                    <div class="text-xs text-red-600" v-if="form.errors.phone">
                        {{ form.errors.phone }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Customer Address">Address</Label>
                    <Input
                        v-model="form.address"
                        type="text"
                        placeholder="Address"
                    />
                    <div class="text-xs text-red-600" v-if="form.errors.address">
                        {{ form.errors.address }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Add Customer</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
