@extends('layouts.layout')

@section('title', 'IT Support Ticket Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header" id="tickets-page-header">
        <div class="page-title-area">
            <h2>Support Tickets</h2>
            <p>Manage, prioritize, and track IT support inquiries.</p>
        </div>
        <div>
            <button class="btn btn-primary" id="open-modal-btn" onclick="toggleModal(true)">
                <i data-lucide="plus" style="width: 16px; height: 16px; margin-right: 0.25rem;"></i>
                Create New Ticket
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert-success" id="success-alert">
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button class="alert-close">&times;</button>
        </div>
    @endif

    <!-- Dashboard Stats Grid -->
    <section class="stats-grid" id="dashboard-stats">
        <!-- Card 1: Total Tickets -->
        <div class="stat-card card-total">
            <div class="stat-details">
                <span class="stat-title">Total Tickets</span>
                <span class="stat-value">{{ $stats['total'] }}</span>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="ticket"></i>
            </div>
        </div>

        <!-- Card 2: Open Tickets -->
        <div class="stat-card card-open">
            <div class="stat-details">
                <span class="stat-title">Open Tickets</span>
                <span class="stat-value">{{ $stats['open'] }}</span>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="shield-alert"></i>
            </div>
        </div>

        <!-- Card 3: In Progress Tickets -->
        <div class="stat-card card-progress">
            <div class="stat-details">
                <span class="stat-title">In Progress</span>
                <span class="stat-value">{{ $stats['in_progress'] }}</span>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="refresh-cw"></i>
            </div>
        </div>

        <!-- Card 4: High Priority Tickets -->
        <div class="stat-card card-high">
            <div class="stat-details">
                <span class="stat-title">High Priority</span>
                <span class="stat-value">{{ $stats['high_priority'] }}</span>
            </div>
            <div class="stat-icon-wrapper">
                <i data-lucide="alert-triangle"></i>
            </div>
        </div>
    </section>

    <!-- Filters & Control Panel -->
    <section class="control-panel" id="filters-panel">
        <form action="{{ route('tickets.index') }}" method="GET" class="filter-form">
            <!-- Search Keyword -->
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search title, description..." value="{{ request('search') }}">
            </div>

            <!-- Issue Category -->
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority -->
            <div class="form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control">
                    <option value="">All Priorities</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <!-- Sorting -->
            <div class="form-group">
                <label for="sort_by">Sort By</label>
                <div style="display: flex; gap: 0.5rem;">
                    <select name="sort_by" id="sort_by" class="form-control" style="flex: 2;">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="priority" {{ request('sort_by', 'created_at') == 'priority' ? 'selected' : '' }}>Priority</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                    <select name="sort_order" id="sort_order" class="form-control" style="flex: 1.2; padding: 0.5rem;">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </section>

    <!-- Ticket Table Section -->
    <section class="table-container" id="tickets-table-section">
        @if($tickets->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned Person</th>
                            <th>Created Date</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr id="ticket-row-{{ $ticket->id }}">
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="ticket-title-cell">
                                        {{ $ticket->title }}
                                    </a>
                                </td>
                                <td>
                                    <span style="font-weight: 500;">{{ $ticket->category }}</span>
                                </td>
                                <td>
                                    <span class="badge-priority priority-{{ strtolower($ticket->priority) }}">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'badge-open';
                                        if($ticket->status === 'In Progress') $statusClass = 'badge-in-progress';
                                        elseif($ticket->status === 'Resolved') $statusClass = 'badge-resolved';
                                        elseif($ticket->status === 'Closed') $statusClass = 'badge-closed';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="assigned-cell">
                                        @if($ticket->assigned_to)
                                            <div class="avatar">
                                                {{ strtoupper(substr($ticket->assigned_to, 0, 1)) }}
                                            </div>
                                            <span>{{ $ticket->assigned_to }}</span>
                                        @else
                                            <span class="unassigned">Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="date-cell">
                                        {{ $ticket->created_at->format('M d, Y H:i') }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <!-- View Detail -->
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn-icon" title="View Details">
                                            <i data-lucide="eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn-icon" title="Edit Ticket">
                                            <i data-lucide="edit-3"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Delete Ticket">
                                                <i data-lucide="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
                </div>
                <div class="pagination-links">
                    {{-- Previous Page --}}
                    @if ($tickets->onFirstPage())
                        <span class="pagination-link disabled">Prev</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}" class="pagination-link">Prev</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($tickets->getUrlRange(max(1, $tickets->currentPage() - 2), min($tickets->lastPage(), $tickets->currentPage() + 2)) as $page => $url)
                        @if ($page == $tickets->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}" class="pagination-link">Next</a>
                    @else
                        <span class="pagination-link disabled">Next</span>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i data-lucide="inbox"></i>
                <h3>No Tickets Found</h3>
                <p>We couldn't find any tickets matching your search query or filters.</p>
                <a href="{{ route('tickets.index') }}" class="btn btn-primary">Clear All Filters</a>
            </div>
        @endif
    </section>

    <!-- Create Ticket Modal -->
    <div class="modal-overlay" id="create-ticket-modal">
        <div class="modal-box">
            <div class="modal-header">
                <h2 class="modal-title">Create Support Ticket</h2>
                <button class="modal-close" onclick="toggleModal(false)">&times;</button>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST" id="create-ticket-form">
                @csrf
                <div class="modal-body" style="display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Title -->
                    <div class="form-group">
                        <label for="modal-title-input">Ticket Title *</label>
                        <input type="text" name="title" id="modal-title-input" class="form-control" placeholder="Brief summary of the issue" required>
                    </div>

                    <!-- Category & Priority (Row) -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="modal-category">Category *</label>
                            <select name="category" id="modal-category" class="form-control" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modal-priority">Priority *</label>
                            <select name="priority" id="modal-priority" class="form-control" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status & Assigned Person (Row) -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="modal-status">Status *</label>
                            <select name="status" id="modal-status" class="form-control" required>
                                <option value="Open" selected>Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modal-assigned">Assigned Person</label>
                            <input type="text" name="assigned_to" id="modal-assigned" class="form-control" placeholder="IT Staff Name">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="modal-description">Detailed Description</label>
                        <textarea name="description" id="modal-description" class="form-control" rows="4" placeholder="Provide details about the issue..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="toggleModal(false)">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Toggle Script -->
    <script>
        function toggleModal(show) {
            const modal = document.getElementById('create-ticket-modal');
            if (show) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Close modal when clicking on backdrop
        window.onclick = function(event) {
            const modal = document.getElementById('create-ticket-modal');
            if (event.target == modal) {
                toggleModal(false);
            }
        }
    </script>
@endsection
