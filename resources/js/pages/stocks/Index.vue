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

interface Stock {
    id: number;
    product_id: number;
    product: {
        name: string;
    };
    quantity: number;
}

interface Props {
    stocks: Stock[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Stocks',
        href: '/stocks',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete this stock entry?')) {
        router.delete(`/stocks/${id}`);
    }
};
</script>

<template>
    <Head title="Stocks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/stocks/create`">
                <Button>Create Stock Entry</Button>
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
                    <TableCaption>A list of your recent stock entries.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> ID </TableHead>
                            <TableHead>Product</TableHead>
                            <TableHead>Quantity</TableHead>
                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        v-for="stock in props.stocks"
                        :key="stock.id"
                    >
                        <TableRow>
                            <TableCell>{{ stock.id }}</TableCell>
                            <TableCell>{{ stock.product.name }}</TableCell>
                            <TableCell>{{ stock.quantity }}</TableCell>
                            <TableCell>
                                <div class="flex items-center justify-center space-x-2">
                                    <Link :href="`/stocks/${stock.id}/edit`">
                                        <Button size="icon" variant="secondary"><Pencil class="h-4 w-4" /></Button>
                                    </Link>
                                    <Button
                                        size="icon"
                                        variant="destructive"
                                        @click="handleDelete(stock.id)"
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
