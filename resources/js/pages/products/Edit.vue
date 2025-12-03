<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    price: number;
    description: string;
}

const props = defineProps<{ product: Product }>();

const form = useForm({
    name: props.product.name,
    price: props.product.price,
    description: props.product.description,
});

const handleSubmit = () => {
    form.put(`/products/${props.product.id}`);
};
</script>

<template>
    <Head title="Edit a Product" />

    <AppLayout
        :breadcrumbs="[
            {
                title: 'Edit a Product',
                href: `/products/${props.product.id}/edit`,
            },
        ]"
    >
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Edit a Product Page
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Product Name">Product Name</Label>
                    <Input v-model="form.name" type="text" placeholder="Name" />
                    <div class="text-xs text-red-600" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Product Price">Price</Label>
                    <Input
                        v-model="form.price"
                        type="number"
                        placeholder="Price"
                    />
                    <div class="text-xs text-red-600" v-if="form.errors.price">
                        {{ form.errors.price }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Product Description">Description</Label>
                    <Input
                        v-model="form.description"
                        type="text"
                        placeholder="Description"
                    />
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.description"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Edit Product</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
