<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Supplier {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
}

const props = defineProps<{ supplier: Supplier }>();

const form = useForm({
    name: props.supplier.name,
    email: props.supplier.email,
    phone: props.supplier.phone,
    address: props.supplier.address,
});

const handleSubmit = () => {
    form.put(`/suppliers/${props.supplier.id}`);
};
</script>

<template>
    <Head title="Edit a Supplier" />

    <AppLayout
        :breadcrumbs="[
            {
                title: 'Edit a Supplier',
                href: `/suppliers/${props.supplier.id}/edit`,
            },
        ]"
    >
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Edit a Supplier Page
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Supplier Name">Supplier Name</Label>
                    <Input v-model="form.name" type="text" placeholder="Name" />
                    <div class="text-xs text-red-600" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Supplier Email">Email</Label>
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
                    <Label for="Supplier Phone">Phone</Label>
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
                    <Label for="Supplier Address">Address</Label>
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
                    >Edit Supplier</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
