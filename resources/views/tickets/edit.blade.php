@extends('layouts.layout')

@section('title', 'Edit Ticket: ' . $ticket->title)

@section('content')
    <!-- Top Action Bar -->
    <div class="actions-bar" id="edit-actions" style="margin-bottom: 2rem;">
        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px; margin-right: 0.25rem;"></i>
            Cancel & Back
        </a>
    </div>

    <!-- Edit Ticket Form Card -->
    <section class="card-detail" id="edit-ticket-card" style="max-width: 800px; margin: 0 auto;">
        <div class="detail-header" style="margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">
                    Edit Support Ticket #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Modify ticket metadata, status updates, or assignments.</p>
            </div>
        </div>

        <form action="{{ route('tickets.update', $ticket) }}" method="POST" id="edit-ticket-form">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <!-- Title -->
                <div class="form-group">
                    <label for="edit-title">Ticket Title *</label>
                    <input type="text" name="title" id="edit-title" class="form-control" value="{{ old('title', $ticket->title) }}" placeholder="Brief summary of the issue" required>
                    @error('title')
                        <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category & Priority (Row) -->
                <div class="grid-2">
                    <div class="form-group">
                        <label for="edit-category">Category *</label>
                        <select name="category" id="edit-category" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $ticket->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-priority">Priority *</label>
                        <select name="priority" id="edit-priority" class="form-control" required>
                            <option value="Low" {{ old('priority', $ticket->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', $ticket->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority', $ticket->priority) == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status & Assigned Person (Row) -->
                <div class="grid-2">
                    <div class="form-group">
                        <label for="edit-status">Status *</label>
                        <select name="status" id="edit-status" class="form-control" required>
                            <option value="Open" {{ old('status', $ticket->status) == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ old('status', $ticket->status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ old('status', $ticket->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-assigned">Assigned Person</label>
                        <input type="text" name="assigned_to" id="edit-assigned" class="form-control" value="{{ old('assigned_to', $ticket->assigned_to) }}" placeholder="IT Staff Name">
                        @error('assigned_to')
                            <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="edit-description">Detailed Description</label>
                    <textarea name="description" id="edit-description" class="form-control" rows="5" placeholder="Provide details about the issue...">{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                        <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="form-actions" style="justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 0.5rem;">
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </section>
@endsection
