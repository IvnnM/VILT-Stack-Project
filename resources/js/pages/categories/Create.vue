<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Category',
        href: '/categories/create',
    },
];

const form = useForm({
    name: '',
    slug: '',
});

const handleSubmit = () => {
    form.post('/categories');
};
</script>

<template>
    <Head title="Create a Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Create a Category Page
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Category Name">Category Name</Label>
                    <Input v-model="form.name" type="text" placeholder="Name" />
                    <div class="text-xs text-red-600" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Category Slug">Slug</Label>
                    <Input
                        v-model="form.slug"
                        type="text"
                        placeholder="Slug"
                    />
                    <div class="text-xs text-red-600" v-if="form.errors.slug">
                        {{ form.errors.slug }}
                    </div>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Add Category</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
