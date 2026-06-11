<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'author' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        $comment = new Comment($validated);
        $comment->ticket_id = $ticket->id;
        $comment->save();

        return redirect()->route('tickets.show', $ticket)->with('success', 'Note added successfully.');
    }
}
