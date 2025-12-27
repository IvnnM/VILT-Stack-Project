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

interface SalesOrder {
    id: number;
    customer: {
        id: number;
        name: string;
    };
    order_date: string;
    status: string;
}

interface Props {
    salesOrders: SalesOrder[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sales Orders',
        href: '/sales-orders',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete this sales order?')) {
        router.delete(`/sales-orders/${id}`);
    }
};
</script>

<template>
    <Head title="Sales Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/sales-orders/create`">
                <Button>Create a Sales Order</Button>
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
                    <TableCaption
                        >A list of your recent sales orders.</TableCaption
                    >
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> ID </TableHead>
                            <TableHead>Customer</TableHead>
                            <TableHead>Order Date</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody v-for="so in props.salesOrders" :key="so.id">
                        <TableRow>
                            <TableCell>{{ so.id }}</TableCell>
                            <TableCell>{{ so.customer.name }}</TableCell>
                            <TableCell>{{ so.order_date }}</TableCell>
                            <TableCell>{{ so.status }}</TableCell>
                            <TableCell>
                                <div class="flex items-center justify-center space-x-2">
                                    <Link :href="`/sales-orders/${so.id}/edit`">
                                        <Button size="icon" variant="secondary"
                                            ><Pencil class="h-4 w-4"
                                        /></Button>
                                    </Link>
                                    <Button
                                        size="icon"
                                        variant="destructive"
                                        @click="handleDelete(so.id)"
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
