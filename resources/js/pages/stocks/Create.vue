<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
}

interface Props {
    products: Product[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Stock Entry',
        href: '/stocks/create',
    },
];

const form = useForm({
    product_id: '',
    quantity: 0,
});

const handleSubmit = () => {
    form.post('/stocks');
};
</script>

<template>
    <Head title="Create Stock Entry" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Create Stock Entry Page
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Product">Product</Label>
                    <Select v-model:model-value="form.product_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a product" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="product in props.products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.product_id"
                    >
                        {{ form.errors.product_id }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Quantity">Quantity</Label>
                    <Input
                        v-model="form.quantity"
                        type="number"
                        placeholder="Quantity"
                    />
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.quantity"
                    >
                        {{ form.errors.quantity }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Add Stock</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
