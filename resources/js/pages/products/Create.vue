<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

interface Category {
    id: number;
    name: string;
}

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Product',
        href: '/products/create',
    },
];

const form = useForm({
    name: '',
    price: '',
    description: '',
    category_id: '',
});

const handleSubmit = () => {
    form.post('/products');
};
</script>

<template>
    <Head title="Create a Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Create a Product Page
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
                    <Label for="Category">Category</Label>
                    <Select v-model="form.category_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem :value="null">
                                    Uncategorized
                                </SelectItem>
                                <SelectItem
                                    v-for="category in props.categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.category_id"
                    >
                        {{ form.errors.category_id }}
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
                    >Add Product</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
