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

interface Customers {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
}

interface Props {
    customers: Customers[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: '/customers',
    },
];

const page = usePage();

const handleDelete = (id: number) => {
    if (confirm('Do you want to delete a customer?')) {
        router.delete(`/customers/${id}`);
    }
};
</script>

<template>
    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Link :href="`/customers/create`">
                <Button>Create a Customer</Button>
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
                    <TableCaption>A list of your recent customers.</TableCaption>
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
                        v-for="customer in props.customers"
                        :key="customer.id"
                    >
                        <TableRow>
                            <TableCell>{{ customer.id }}</TableCell>
                            <TableCell>{{ customer.name }}</TableCell>
                            <TableCell>{{ customer.email }}</TableCell>
                            <TableCell>{{ customer.phone }}</TableCell>
                            <TableCell>{{ customer.address }}</TableCell>
                            <TableCell>
                                <div class="flex items-center justify-center space-x-2">
                                    <Link :href="`/customers/${customer.id}/edit`">
                                        <Button size="icon" variant="secondary"><Pencil class="h-4 w-4" /></Button>
                                    </Link>
                                    <Button
                                        size="icon"
                                        variant="destructive"
                                        @click="handleDelete(customer.id)"
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
