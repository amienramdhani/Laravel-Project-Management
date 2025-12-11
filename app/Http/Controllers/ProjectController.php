<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:project-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:project-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:project-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            // Staff hanya bisa lihat project mereka
            if ($user->hasRole('Staff')) {
                $projects = Project::with(['manager', 'staff'])
                    ->assignedToUser($user->id);
            } else {
                $projects = Project::with(['manager', 'staff']);
            }

            return DataTables::of($projects)
                ->addIndexColumn()
                ->addColumn('manager_name', function ($row) {
                    return $row->manager->name;
                })
                ->addColumn('staff_count', function ($row) {
                    return $row->staff->count();
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '<a href="' . route('projects.show', $row->id) . '" class="btn btn-info btn-sm">View</a> ';

                    if ($user->can('update', $row)) {
                        $btn .= '<a href="' . route('projects.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a> ';
                    }

                    if ($user->can('delete', $row)) {
                        $btn .= '<form action="' . route('projects.destroy', $row->id) . '" method="POST" style="display:inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        $managers = User::role('Manager')->orWhere(function($query) {
            $query->role('Super Admin');
        })->get();

        $staff = User::role('Staff')->get();

        return view('projects.create', compact('managers', 'staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'staff' => 'nullable|array',
            'staff.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $project = Project::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'manager_id' => $validated['manager_id'],
                'status' => $validated['status'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            if (isset($validated['staff'])) {
                $project->staff()->attach($validated['staff']);
            }

            DB::commit();
            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['manager', 'staff', 'tasks']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $managers = User::role('Manager')->orWhere(function($query) {
            $query->role('Super Admin');
        })->get();

        $staff = User::role('Staff')->get();

        return view('projects.edit', compact('project', 'managers', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'staff' => 'nullable|array',
            'staff.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $project->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'manager_id' => $validated['manager_id'],
                'status' => $validated['status'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            $project->staff()->sync($validated['staff'] ?? []);

            DB::commit();
            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update project: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
         $this->authorize('delete', $project);

        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete project: ' . $e->getMessage());
        }
    }
}
