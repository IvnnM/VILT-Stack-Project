<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface LowStockItem {
    id: number;
    quantity: number;
    product: {
        id: number;
        name: string;
    };
}

interface Props {
    totalProducts: number;
    totalStock: number;
    lowStockItems: LowStockItem[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <h3 class="text-lg font-bold">Total Products</h3>
                    <p class="text-4xl font-bold">{{ totalProducts }}</p>
                </div>
                <div
                    class="relative flex flex-col justify-between overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <h3 class="text-lg font-bold">Total Stock</h3>
                    <p class="text-4xl font-bold">{{ totalStock }}</p>
                </div>
            </div>
            <div
                class="relative flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border"
            >
                <h3 class="mb-4 text-lg font-bold">Low Stock Items</h3>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Product</TableHead>
                            <TableHead>Quantity</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="lowStockItems.length === 0">
                            <TableCell colspan="2" class="text-center"
                                >No low stock items.</TableCell
                            >
                        </TableRow>
                        <TableRow
                            v-for="item in lowStockItems"
                            :key="item.id"
                        >
                            <TableCell>{{ item.product.name }}</TableCell>
                            <TableCell>{{ item.quantity }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
