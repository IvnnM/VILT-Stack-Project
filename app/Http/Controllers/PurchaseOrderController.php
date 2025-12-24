<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->get();
        return Inertia::render('purchase-orders/Index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return Inertia::render('purchase-orders/Create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'order_date' => $data['order_date'],
            ]);

            foreach ($data['items'] as $item) {
                $purchaseOrder->items()->create($item);
            }
        });

        return redirect()->route('purchase-orders.index')->with('message', 'Purchase Order created successfully.');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product');
        $suppliers = Supplier::all();
        $products = Product::all();
        return Inertia::render('purchase-orders/Edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|in:pending,approved,cancelled',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|integer',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $purchaseOrder) {
            $purchaseOrder->update([
                'supplier_id' => $data['supplier_id'],
                'order_date' => $data['order_date'],
                'status' => $data['status'],
            ]);

            $existingItemIds = $purchaseOrder->items()->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($data['items'] as $item) {
                if (isset($item['id'])) {
                    $purchaseOrder->items()->where('id', $item['id'])->update($item);
                    $newItemIds[] = $item['id'];
                } else {
                    $newItem = $purchaseOrder->items()->create($item);
                    $newItemIds[] = $newItem->id;
                }
            }
            
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            if(!empty($itemsToDelete)){
                 $purchaseOrder->items()->whereIn('id', $itemsToDelete)->delete();
            }
        });

        return redirect()->route('purchase-orders.index')->with('message', 'Purchase Order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('message', 'Purchase Order deleted successfully.');
    }
}