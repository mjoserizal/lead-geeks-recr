<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test displaying dashboard statistics and ticket listing.
     */
    public function test_can_view_ticket_dashboard_and_stats(): void
    {
        Ticket::create([
            'title' => 'Open Hardware Issue',
            'category' => 'Hardware',
            'priority' => 'High',
            'status' => 'Open',
            'assigned_to' => 'Alex Rivera',
            'description' => 'Test description'
        ]);

        Ticket::create([
            'title' => 'In Progress Software Issue',
            'category' => 'Software',
            'priority' => 'Medium',
            'status' => 'In Progress',
            'assigned_to' => 'Sophia Martinez',
            'description' => 'Test description'
        ]);

        Ticket::create([
            'title' => 'Resolved Security Issue',
            'category' => 'Security',
            'priority' => 'Low',
            'status' => 'Resolved',
            'assigned_to' => 'Marcus Chen',
            'description' => 'Test description'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('IT Support Ticket Dashboard');
        $response->assertSee('Open Hardware Issue');
        $response->assertSee('In Progress Software Issue');
        $response->assertSee('Resolved Security Issue');

        $response->assertViewHas('stats', function ($stats) {
            return $stats['total'] === 3 &&
                   $stats['open'] === 1 &&
                   $stats['in_progress'] === 1 &&
                   $stats['high_priority'] === 1;
        });
    }

    /**
     * Test validation and ticket creation.
     */
    public function test_can_create_a_ticket(): void
    {
        $ticketData = [
            'title' => 'New Internet Failure',
            'category' => 'Network',
            'priority' => 'High',
            'status' => 'Open',
            'assigned_to' => 'Marcus Chen',
            'description' => 'The whole floor has no connection.'
        ];

        $response = $this->post(route('tickets.store'), $ticketData);

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseHas('tickets', [
            'title' => 'New Internet Failure',
            'category' => 'Network',
            'priority' => 'High',
            'status' => 'Open'
        ]);
    }

    /**
     * Test viewing details and posting comments.
     */
    public function test_can_view_ticket_details_and_add_comment(): void
    {
        $ticket = Ticket::create([
            'title' => 'Unassigned Issue',
            'category' => 'Other',
            'priority' => 'Low',
            'status' => 'Open',
            'description' => 'A basic support request.'
        ]);

        $response = $this->get(route('tickets.show', $ticket));

        $response->assertStatus(200);
        $response->assertSee('Unassigned Issue');
        $response->assertSee('Unassigned');

        $commentData = [
            'author' => 'Agent Antigravity',
            'comment' => 'This is a test comment.'
        ];

        $commentResponse = $this->post(route('tickets.comments.store', $ticket), $commentData);

        $commentResponse->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'author' => 'Agent Antigravity',
            'comment' => 'This is a test comment.'
        ]);
    }

    /**
     * Test ticket updates.
     */
    public function test_can_edit_and_update_ticket(): void
    {
        $ticket = Ticket::create([
            'title' => 'Old Title',
            'category' => 'Software',
            'priority' => 'Medium',
            'status' => 'Open'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'category' => 'Hardware',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => 'Marcus Chen',
            'description' => 'New details'
        ];

        $response = $this->put(route('tickets.update', $ticket), $updateData);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Updated Title',
            'category' => 'Hardware',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => 'Marcus Chen',
            'description' => 'New details'
        ]);
    }

    /**
     * Test ticket deletion.
     */
    public function test_can_delete_ticket(): void
    {
        $ticket = Ticket::create([
            'title' => 'Temporary Ticket',
            'category' => 'Other',
            'priority' => 'Low',
            'status' => 'Closed'
        ]);

        $response = $this->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id
        ]);
    }
}
