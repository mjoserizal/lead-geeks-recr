@extends('layouts.layout')

@section('title', 'Ticket Details: ' . $ticket->title)

@section('content')
    <!-- Top Action Bar -->
    <div class="actions-bar" id="detail-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px; margin-right: 0.25rem;"></i>
            Back to Dashboard
        </a>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-secondary">
                <i data-lucide="edit-3" style="width: 16px; height: 16px; margin-right: 0.25rem;"></i>
                Edit Details
            </a>
            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i data-lucide="trash-2" style="width: 16px; height: 16px; margin-right: 0.25rem;"></i>
                    Delete Ticket
                </button>
            </form>
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

    <!-- Ticket Detail Card -->
    <section class="card-detail" id="ticket-detail-card">
        <div class="detail-header">
            <div>
                <span class="badge {{ $ticket->status === 'Open' ? 'badge-open' : ($ticket->status === 'In Progress' ? 'badge-in-progress' : ($ticket->status === 'Resolved' ? 'badge-resolved' : 'badge-closed')) }}" style="margin-bottom: 0.5rem;">
                    {{ $ticket->status }}
                </span>
                <h2 style="font-size: 1.6rem; font-weight: 700; color: var(--text-primary);">
                    {{ $ticket->title }}
                </h2>
                <div class="detail-meta-row">
                    <span>Ticket ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span>•</span>
                    <span>Created: {{ $ticket->created_at->format('M d, Y H:i A') }} ({{ $ticket->created_at->diffForHumans() }})</span>
                </div>
            </div>
        </div>

        <!-- Meta Info Sidebar Grid -->
        <div class="info-sidebar">
            <!-- Category -->
            <div class="info-item">
                <span class="info-label">Category</span>
                <span class="info-value">{{ $ticket->category }}</span>
            </div>

            <!-- Priority -->
            <div class="info-item">
                <span class="info-label">Priority</span>
                <span class="info-value">
                    <span class="badge-priority priority-{{ strtolower($ticket->priority) }}">
                        {{ $ticket->priority }}
                    </span>
                </span>
            </div>

            <!-- Assigned Person -->
            <div class="info-item">
                <span class="info-label">Assigned Person</span>
                <span class="info-value" style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($ticket->assigned_to)
                        <div class="avatar" style="width: 22px; height: 22px; font-size: 0.65rem;">
                            {{ strtoupper(substr($ticket->assigned_to, 0, 1)) }}
                        </div>
                        <span>{{ $ticket->assigned_to }}</span>
                    @else
                        <span class="unassigned">Unassigned</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Description -->
        <div class="detail-body">
            <h3>Description</h3>
            <div class="detail-description">@if($ticket->description){{ $ticket->description }}@else<span style="color: var(--text-muted); font-style: italic;">No description provided.</span>@endif</div>
        </div>
    </section>

    <!-- Comments & Notes Section -->
    <section class="comments-section" id="comments-section">
        <h3 class="comments-title">
            Ticket Activity Notes & Comments ({{ $ticket->comments->count() }})
        </h3>

        <!-- Add Note Form -->
        <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="comment-form">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label for="comment-author-input">Your Name *</label>
                    <input type="text" name="author" id="comment-author-input" class="form-control" placeholder="E.g. Sophia Martinez" required>
                </div>
            </div>
            <div class="form-group">
                <label for="comment-textarea">Add Note/Log Update *</label>
                <textarea name="comment" id="comment-textarea" class="form-control" rows="3" placeholder="Add details of progress, actions taken, or questions..." required></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="send" style="width: 14px; height: 14px; margin-right: 0.25rem;"></i>
                    Post Note
                </button>
            </div>
        </form>

        <!-- Comments List -->
        <div class="comments-list">
            @if($ticket->comments->count() > 0)
                @foreach($ticket->comments as $comment)
                    <div class="comment-card">
                        <div class="comment-header">
                            <span class="comment-author">{{ $comment->author }}</span>
                            <span class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }} ({{ $comment->created_at->diffForHumans() }})</span>
                        </div>
                        <div class="comment-content">
                            {!! nl2br(e($comment->comment)) !!}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-comments">
                    <p>No activity notes have been added to this ticket yet.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
