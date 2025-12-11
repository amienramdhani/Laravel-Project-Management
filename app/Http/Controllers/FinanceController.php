<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:finance-list|finance-create|finance-edit|finance-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:finance-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:finance-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:finance-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $finances = Finance::query();

            return DataTables::of($finances)
                ->addIndexColumn()
                ->addColumn('formatted_amount', function ($row) {
                    return 'Rp ' . number_format($row->amount, 0, ',', '.');
                })
                ->addColumn('type_badge', function ($row) {
                    $class = $row->type === 'income' ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . ucfirst($row->type) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '<a href="' . route('finances.show', $row->id) . '" class="btn btn-info btn-sm">View</a> ';

                    if ($user->can('update', $row)) {
                        $btn .= '<a href="' . route('finances.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a> ';
                    }

                    if ($user->can('delete', $row)) {
                        $btn .= '<form action="' . route('finances.destroy', $row->id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['type_badge', 'action'])
                ->make(true);
        }

        return view('finances.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Finance::class);
        return view('finances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Finance::class);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        Finance::create($validated);

        return redirect()->route('finances.index')->with('success', 'Finance record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance $finance)
    {
        $this->authorize('view', $finance);
        return view('finances.show', compact('finance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Finance $finance)
    {
        $this->authorize('update', $finance);
        return view('finances.edit', compact('finance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finance $finance)
    {
        $this->authorize('update', $finance);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $finance->update($validated);

        return redirect()->route('finances.index')->with('success', 'Finance record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance $finance)
    {
        $this->authorize('delete', $finance);

        $finance->delete();
        return redirect()->route('finances.index')->with('success', 'Finance record deleted successfully.');
    }
}
