<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Category {
    id: number;
    name: string;
    slug: string;
}

const props = defineProps<{ category: Category }>();

const form = useForm({
    name: props.category.name,
    slug: props.category.slug,
});

const handleSubmit = () => {
    form.put(`/categories/${props.category.id}`);
};
</script>

<template>
    <Head title="Edit a Category" />

    <AppLayout
        :breadcrumbs="[
            {
                title: 'Edit a Category',
                href: `/categories/${props.category.id}/edit`,
            },
        ]"
    >
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            Edit a Category Page
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
                    >Edit Category</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
