<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Statistics
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'Open')->count(),
            'in_progress' => Ticket::where('status', 'In Progress')->count(),
            'high_priority' => Ticket::where('priority', 'High')->count(),
        ];

        // Query Builder
        $query = Ticket::query();

        // Search filter (searches title, assigned_to, and description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('assigned_to', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Allow sorting by priority and status with logical ordering
        if ($sortBy === 'priority') {
            if ($sortOrder === 'asc') {
                $query->orderByRaw("CASE WHEN priority = 'High' THEN 1 WHEN priority = 'Medium' THEN 2 ELSE 3 END");
            } else {
                $query->orderByRaw("CASE WHEN priority = 'Low' THEN 1 WHEN priority = 'Medium' THEN 2 ELSE 3 END");
            }
        } elseif ($sortBy === 'status') {
            if ($sortOrder === 'asc') {
                $query->orderByRaw("CASE WHEN status = 'Open' THEN 1 WHEN status = 'In Progress' THEN 2 WHEN status = 'Resolved' THEN 3 ELSE 4 END");
            } else {
                $query->orderByRaw("CASE WHEN status = 'Closed' THEN 1 WHEN status = 'Resolved' THEN 2 WHEN status = 'In Progress' THEN 3 ELSE 4 END");
            }
        } else {
            // Default to sorting by column directly
            $allowedSortColumns = ['created_at', 'title', 'category', 'assigned_to'];
            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'created_at';
            }
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $tickets = $query->paginate(8)->withQueryString();

        // Get unique categories for filter options
        $categories = ['Hardware', 'Software', 'Network', 'Security', 'Other'];

        return view('tickets.index', compact('tickets', 'stats', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'assigned_to' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $categories = ['Hardware', 'Software', 'Network', 'Security', 'Other'];
        return view('tickets.edit', compact('ticket', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'assigned_to' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
