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

interface Products {
    id: number;
    name: string;
    price: number;
    description: string;
    category: {
        id: number;
        name: string;
    };
}

interface Props {
    products: Products[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete a product?')) {
        router.delete(`/products/${id}`);
    }
};
</script>

<template>
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/products/create`">
                <Button>Create a Product</Button>
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
                    <TableCaption>A list of your recent products.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> ID </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        v-for="product in props.products"
                        :key="product.id"
                    >
                        <TableRow>
                            <TableCell>{{ product.id }}</TableCell>
                            <TableCell>{{ product.name }}</TableCell>
                            <TableCell>{{ product.price }}</TableCell>
                            <TableCell>{{ product.description }}</TableCell>
                            <TableCell>{{
                                product.category
                                    ? product.category.name
                                    : 'Uncategorized'
                            }}</TableCell>
                            <TableCell class="space-x-2 text-center">
                                <Link :href="`/products/${product.id}/edit`">
                                    <Button size="icon" variant="secondary"><Pencil class="h-4 w-4" /></Button>
                                </Link>
                                <Button
                                    size="icon"
                                    variant="destructive"
                                    @click="handleDelete(product.id)"
                                    ><Trash2 class="h-4 w-4" /></Button
                                >
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
