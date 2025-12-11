<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Finance;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $orders = Order::with('customer');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customer->name;
                })
                ->addColumn('formatted_amount', function ($row) {
                    return 'Rp ' . number_format($row->total_amount, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '<a href="' . route('orders.show', $row->id) . '" class="btn btn-info btn-sm">View</a> ';

                    if ($user->can('update', $row)) {
                        $btn .= '<a href="' . route('orders.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a> ';
                    }

                    if ($user->can('delete', $row)) {
                        $btn .= '<form action="' . route('orders.destroy', $row->id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Order::class);
        $customers = Customer::all();
        return view('orders.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create($validated);

            if ($validated['status'] === 'completed') {
                Finance::create([
                    'type' => 'income',
                    'amount' => $validated['total_amount'],
                    'description' => 'Income from Order ' . $order->order_number,
                    'date' => now(),
                    'category' => 'Order',
                    'financeable_id' => $order->id,
                    'financeable_type' => Order::class,
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['customer', 'finance']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $customers = Customer::all();
        return view('orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $order->update($validated);

            if ($oldStatus !== 'completed' && $validated['status'] === 'completed') {
                Finance::create([
                    'type' => 'income',
                    'amount' => $validated['total_amount'],
                    'description' => 'Income from Order ' . $order->order_number,
                    'date' => now(),
                    'category' => 'Order',
                    'financeable_id' => $order->id,
                    'financeable_type' => Order::class,
                ]);
            }

            if ($oldStatus === 'completed' && $validated['status'] === 'cancelled') {
                $order->finance()->delete();
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        DB::beginTransaction();
        try {
            // Hapus finance terkait jika ada
            $order->finance()->delete();
            $order->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
}
