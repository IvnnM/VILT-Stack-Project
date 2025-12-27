<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import Button from '@/components/ui/button/Button.vue';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Pencil, Rocket, Trash2 } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    slug: string;
}

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/categories',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete a category?')) {
        router.delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/categories/create`">
                <Button>Create a Category</Button>
            </Link>

            <div v-if="page.props.flash?.message" class="mb-4">
                <Alert class="bg-blue-200">
                    <Rocket class="h-4 w-4" />
                    <AlertTitle>Heads up!</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash.message }}
                    </AlertDescription>
                </Alert>
            </div>

            <div>
                <Table>
                    <TableCaption>A list of your recent categories.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> ID </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Slug</TableHead>
                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        v-for="category in props.categories"
                        :key="category.id"
                    >
                        <TableRow>
                            <TableCell>{{ category.id }}</TableCell>
                            <TableCell>{{ category.name }}</TableCell>
                            <TableCell>{{ category.slug }}</TableCell>
                            <TableCell>
                                <div class="flex items-center justify-center space-x-2">
                                    <Link :href="`/categories/${category.id}/edit`">
                                        <Button size="icon" variant="secondary"><Pencil class="h-4 w-4" /></Button>
                                    </Link>
                                    <Button
                                        size="icon"
                                        variant="destructive"
                                        @click="handleDelete(category.id)"
                                        ><Trash2 class="h-4 w-4"
                                    /></Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
