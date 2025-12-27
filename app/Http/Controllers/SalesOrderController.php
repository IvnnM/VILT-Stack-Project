<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use App\Models\Stock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with('customer')->latest()->get();
        return Inertia::render('sales-orders/Index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return Inertia::render('sales-orders/Create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $salesOrder = SalesOrder::create([
                'customer_id' => $data['customer_id'],
                'order_date' => $data['order_date'],
                'status' => 'pending',
            ]);

            foreach ($data['items'] as $item) {
                $salesOrder->items()->create($item);
            }
        });

        return redirect()->route('sales-orders.index')->with('message', 'Sales Order created successfully.');
    }

    public function edit(SalesOrder $salesOrder)
    {
        $salesOrder->load('customer', 'items.product');
        $customers = Customer::all();
        $products = Product::all();
        return Inertia::render('sales-orders/Edit', compact('salesOrder', 'customers', 'products'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|in:pending,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'password' => 'nullable|string',
        ]);

        $originalStatus = $salesOrder->status;

        if ($originalStatus !== 'completed' && $data['status'] === 'completed') {
            if (!Hash::check($data['password'], $request->user()->password)) {
                throw ValidationException::withMessages([
                    'password' => 'The provided password does not match your current password.',
                ]);
            }

            foreach ($data['items'] as $item) {
                $stock = Stock::where('product_id', $item['product_id'])->first();
                if (!$stock || $stock->quantity < $item['quantity']) {
                    $product = Product::find($item['product_id']);
                    throw ValidationException::withMessages([
                        'items' => "Not enough stock for product: {$product->name}. Available: {$stock->quantity}, Required: {$item['quantity']}",
                    ]);
                }
            }
        }

        DB::transaction(function () use ($data, $salesOrder, $originalStatus) {
            $salesOrder->update([
                'customer_id' => $data['customer_id'],
                'order_date' => $data['order_date'],
                'status' => $data['status'],
            ]);

            $existingItemIds = $salesOrder->items()->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($data['items'] as $item) {
                if (isset($item['id'])) {
                    $salesOrder->items()->where('id', $item['id'])->update($item);
                    $newItemIds[] = $item['id'];
                } else {
                    $newItem = $salesOrder->items()->create($item);
                    $newItemIds[] = $newItem->id;
                }
            }
            
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            if(!empty($itemsToDelete)){
                 $salesOrder->items()->whereIn('id', $itemsToDelete)->delete();
            }

            if ($originalStatus !== 'completed' && $data['status'] === 'completed') {
                foreach ($data['items'] as $item) {
                    $stock = Stock::firstOrNew(['product_id' => $item['product_id']]);
                    $stock->quantity -= $item['quantity'];
                    $stock->save();
                }
            }
        });

        return redirect()->route('sales-orders.index')->with('message', 'Sales Order updated successfully.');
    }

    public function destroy(SalesOrder $salesOrder)
    {
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')->with('message', 'Sales Order deleted successfully.');
    }
}
