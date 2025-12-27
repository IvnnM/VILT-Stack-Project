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

interface Suppliers {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
}

interface Props {
    suppliers: Suppliers[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Suppliers',
        href: '/suppliers',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete a supplier?')) {
        router.delete(`/suppliers/${id}`);
    }
};
</script>

<template>
    <Head title="Suppliers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/suppliers/create`">
                <Button>Create a Supplier</Button>
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
                    <TableCaption>A list of your recent suppliers.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> ID </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Address</TableHead>
                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        v-for="supplier in props.suppliers"
                        :key="supplier.id"
                    >
                        <TableRow>
                            <TableCell>{{ supplier.id }}</TableCell>
                            <TableCell>{{ supplier.name }}</TableCell>
                            <TableCell>{{ supplier.email }}</TableCell>
                            <TableCell>{{ supplier.phone }}</TableCell>
                            <TableCell>{{ supplier.address }}</TableCell>
                            <TableCell>
                                <div class="flex items-center justify-center space-x-2">
                                    <Link :href="`/suppliers/${supplier.id}/edit`">
                                        <Button size="icon" variant="secondary"><Pencil class="h-4 w-4" /></Button>
                                    </Link>
                                    <Button
                                        size="icon"
                                        variant="destructive"
                                        @click="handleDelete(supplier.id)"
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
