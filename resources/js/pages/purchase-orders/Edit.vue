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

interface Supplier {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    price: number;
}

interface Item {
    id?: number;
    product_id: number | null;
    quantity: number;
    unit_price: number;
}

interface PurchaseOrder {
    id: number;
    supplier_id: number;
    order_date: string;
    status: string;
    items: Item[];
}

interface Props {
    purchaseOrder: PurchaseOrder;
    suppliers: Supplier[];
    products: Product[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit Purchase Order',
        href: `/purchase-orders/${props.purchaseOrder.id}/edit`,
    },
];

const form = useForm({
    supplier_id: props.purchaseOrder.supplier_id,
    order_date: props.purchaseOrder.order_date,
    status: props.purchaseOrder.status,
    items: props.purchaseOrder.items.map((item) => ({ ...item })),
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
    form.put(`/purchase-orders/${props.purchaseOrder.id}`);
};
</script>

<template>
    <Head title="Edit Purchase Order" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-xl font-bold">
                Edit Purchase Order #{{ purchaseOrder.id }}
            </h1>
            <form @submit.prevent="handleSubmit" class="space-y-4" action="">
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <Label for="Supplier">Supplier</Label>
                        <Select v-model="form.supplier_id">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a supplier" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="supplier in props.suppliers"
                                        :key="supplier.id"
                                        :value="supplier.id"
                                    >
                                        {{ supplier.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label for="Order Date">Order Date</Label>
                        <Input v-model="form.order_date" type="date" />
                    </div>
                    <div class="space-y-2">
                        <Label for="Status">Status</Label>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="pending"
                                        >Pending</SelectItem
                                    >
                                    <SelectItem value="approved"
                                        >Approved</SelectItem
                                    >
                                    <SelectItem value="cancelled"
                                        >Cancelled</SelectItem
                                    >
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div
                    class="space-y-4 rounded-lg border bg-card p-4 text-card-foreground shadow-sm"
                >
                    <h3 class="text-lg font-bold">Add Item</h3>
                    <div class="grid grid-cols-5 items-end gap-4">
                        <div class="col-span-3">
                            <Label for="new_product" class="mb-2 block"
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
                        <div>
                            <Label for="new_quantity" class="mb-2 block"
                                >Quantity</Label
                            >
                            <Input
                                v-model="newItem.quantity"
                                type="number"
                                placeholder="Quantity"
                            />
                        </div>
                        <div>
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
                                    :key="item.id || index"
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
                                            class="bg-red-600"
                                            @click="removeItem(index)"
                                            >Remove</Button
                                        >
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>

                <Button type="submit" :disabled="form.processing" class="w-full"
                    >Update Purchase Order</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
