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
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Trash2 } from 'lucide-vue-next';

interface Customer {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    price: number;
}

interface Item {
    product_id: number | null;
    quantity: number;
    unit_price: number;
}

interface Props {
    customers: Customer[];
    products: Product[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a Sales Order',
        href: '/sales-orders/create',
    },
];

const form = useForm({
    customer_id: null,
    order_date: new Date().toISOString().slice(0, 10),
    items: [] as Item[],
});

const newItem = ref({
    product_id: null as number | null,
    quantity: 1,
});

const addItem = () => {
    if (!newItem.value.product_id) {
        return;
    }
    const product = props.products.find(
        (p) => p.id === newItem.value.product_id,
    );

    // Check if item already exists in the list
    const existingItem = form.items.find(
        (item) => item.product_id === newItem.value.product_id,
    );

    if (existingItem) {
        existingItem.quantity += newItem.value.quantity;
    } else {
        form.items.push({
            product_id: newItem.value.product_id,
            quantity: newItem.value.quantity,
            unit_price: product ? product.price : 0,
        });
    }

    newItem.value = {
        product_id: null,
        quantity: 1,
    };
};

const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

const getProductName = (productId: number | null) => {
    if (!productId) return '';
    const product = props.products.find((p) => p.id === productId);
    return product ? product.name : '';
};

const handleSubmit = () => {
    form.post('/sales-orders');
};
</script>

<template>
    <Head title="Create a Sales Order" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-xl font-bold">Create a Sales Order</h1>
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="space-y-2">
                    <Label for="Customer">Customer</Label>
                    <Select v-model="form.customer_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a customer" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem
                                    v-for="customer in props.customers"
                                    :key="customer.id"
                                    :value="customer.id"
                                >
                                    {{ customer.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.customer_id"
                    >
                        {{ form.errors.customer_id }}
                    </div>
                </div>
                <div class="space-y-2">
                    <Label for="Order Date">Order Date</Label>
                    <Input v-model="form.order_date" type="date" />
                    <div
                        class="text-xs text-red-600"
                        v-if="form.errors.order_date"
                    >
                        {{ form.errors.order_date }}
                    </div>
                </div>

                <div
                    class="space-y-4 rounded-lg border bg-card p-4 text-card-foreground shadow-sm"
                >
                    <h3 class="text-lg font-bold">Add Item</h3>
                    <div class="grid grid-cols-5 items-end gap-4">
                        <div class="col-span-3 flex flex-col justify-end">
                            <Label for="new_product" class="mb-2"
                                >Product</Label
                            >
                            <Select v-model="newItem.product_id">
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder="Select a product"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="product in props.products"
                                            :key="product.id"
                                            :value="product.id"
                                        >
                                            {{ product.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex flex-col justify-end">
                            <Label for="new_quantity" class="mb-2"
                                >Quantity</Label
                            >
                            <Input
                                v-model="newItem.quantity"
                                type="number"
                                placeholder="Quantity"
                            />
                        </div>
                        <div class="flex flex-col justify-end">
                            <Button
                                type="button"
                                @click="addItem"
                                class="w-full"
                                >Add</Button
                            >
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm"
                >
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Order Items</h3>
                    </div>
                    <div class="p-0">
                        <Table>
                            <TableCaption
                                v-if="form.items.length === 0"
                                class="p-4"
                                >No items added yet.</TableCaption
                            >
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="p-4">Product</TableHead>
                                    <TableHead class="p-4">Quantity</TableHead>
                                    <TableHead class="p-4"
                                        >Unit Price</TableHead
                                    >
                                    <TableHead class="p-4 text-right"
                                        >Action</TableHead
                                    >
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="form.items.length === 0">
                                    <TableCell
                                        colspan="4"
                                        class="p-4 text-center"
                                        >No items have been added.</TableCell
                                    >
                                </TableRow>
                                <TableRow
                                    v-for="(item, index) in form.items"
                                    :key="index"
                                >
                                    <TableCell class="p-4">{{
                                        getProductName(item.product_id)
                                    }}</TableCell>
                                    <TableCell class="p-4">
                                        <Input
                                            v-model="item.quantity"
                                            type="number"
                                            class="w-24"
                                        />
                                    </TableCell>
                                    <TableCell class="p-4">
                                        <Input
                                            v-model="item.unit_price"
                                            type="number"
                                            step="0.01"
                                            class="w-24"
                                        />
                                    </TableCell>
                                    <TableCell class="p-4 text-right">
                                        <Button
                                            size="icon"
                                            variant="destructive"
                                            @click="removeItem(index)"
                                            ><Trash2 class="h-4 w-4" /></Button
                                        >
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>

                <Button type="submit" :disabled="form.processing" class="w-full"
                    >Create Sales Order</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
