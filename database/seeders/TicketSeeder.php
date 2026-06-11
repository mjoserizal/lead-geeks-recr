<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\Comment;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticketsData = [
            [
                'title' => 'VPN Connection Failing on macOS',
                'category' => 'Network',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_to' => 'Alex Rivera',
                'description' => 'Several remote workers on macOS Sequoia are reporting that the FortiClient VPN connection times out immediately after MFA approval. It seems to affect only macOS clients.',
                'created_at' => Carbon::now()->subHours(4),
                'comments' => [
                    ['author' => 'Alex Rivera', 'comment' => 'Investigated logs. It seems like a DNS resolution issue inside the tunnel. Checking routing tables.', 'created_at' => Carbon::now()->subHours(3)],
                    ['author' => 'User Jane', 'comment' => 'Confirmed it works on Windows, only macOS is having this issue.', 'created_at' => Carbon::now()->subHours(2)],
                ]
            ],
            [
                'title' => 'Printer in HR Office Jammed',
                'category' => 'Hardware',
                'priority' => 'Low',
                'status' => 'Open',
                'assigned_to' => 'Marcus Chen',
                'description' => 'The main laser printer in the HR department on the 3rd floor (HP LaserJet Pro) is displaying a "Paper Jam in Tray 2" error, and we cannot clear it manually.',
                'created_at' => Carbon::now()->subHours(8),
                'comments' => []
            ],
            [
                'title' => 'Password Reset for Adobe Creative Cloud',
                'category' => 'Software',
                'priority' => 'Medium',
                'status' => 'Resolved',
                'assigned_to' => 'Sophia Martinez',
                'description' => 'Marketing executive needs Adobe account unlocked and password reset as she exceeded the password attempts limit.',
                'created_at' => Carbon::now()->subDays(2),
                'comments' => [
                    ['author' => 'Sophia Martinez', 'comment' => 'Reset link sent to user email. Waiting for confirmation.', 'created_at' => Carbon::now()->subDays(2)->addHours(1)],
                    ['author' => 'Sophia Martinez', 'comment' => 'User confirmed she has logged in successfully. Closing ticket.', 'created_at' => Carbon::now()->subDays(2)->addHours(3)],
                ]
            ],
            [
                'title' => 'Phishing Email Investigation',
                'category' => 'Security',
                'priority' => 'High',
                'status' => 'Open',
                'assigned_to' => null,
                'description' => 'Suspected phishing email received by the Finance Director. Email claims to be from the CEO requesting an urgent wire transfer. No links were clicked, but we need to run a scan and block the domain.',
                'created_at' => Carbon::now()->subMinutes(45),
                'comments' => []
            ],
            [
                'title' => 'WiFi Signal Drop in Conference Room B',
                'category' => 'Network',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'assigned_to' => 'Alex Rivera',
                'description' => 'During executive meetings, the WiFi connection drops completely or becomes extremely slow (under 1 Mbps). This has happened 3 times this week.',
                'created_at' => Carbon::now()->subDays(1),
                'comments' => [
                    ['author' => 'Alex Rivera', 'comment' => 'Access point firmware updated. Planning to run site survey later today to check signal strength.', 'created_at' => Carbon::now()->subHours(12)]
                ]
            ],
            [
                'title' => 'Slack Desktop App Crashes on Launch',
                'category' => 'Software',
                'priority' => 'Low',
                'status' => 'Closed',
                'assigned_to' => 'Sophia Martinez',
                'description' => 'Slack desktop client immediately crashes upon opening on Windows 11. Tried reinstalling but same behavior.',
                'created_at' => Carbon::now()->subDays(4),
                'comments' => [
                    ['author' => 'Sophia Martinez', 'comment' => 'Cleared Slack app cache in %AppData%\\Slack. Issue resolved.', 'created_at' => Carbon::now()->subDays(4)->addHours(2)]
                ]
            ],
            [
                'title' => 'New Starter Laptop Provisioning',
                'category' => 'Hardware',
                'priority' => 'High',
                'status' => 'Open',
                'assigned_to' => 'Marcus Chen',
                'description' => 'A new developer is starting next Monday. Need to configure a Lenovo ThinkPad with Docker, VS Code, Git, and company credentials.',
                'created_at' => Carbon::now()->subHours(2),
                'comments' => []
            ],
            [
                'title' => 'GitHub Access Request for Intern',
                'category' => 'Security',
                'priority' => 'Low',
                'status' => 'Resolved',
                'assigned_to' => 'Sophia Martinez',
                'description' => 'Add the new engineering intern (github user: intern_dev) to the LeadGeeks organization repository.',
                'created_at' => Carbon::now()->subDays(3),
                'comments' => [
                    ['author' => 'Sophia Martinez', 'comment' => 'Invitation sent on GitHub.', 'created_at' => Carbon::now()->subDays(3)->addHours(1)],
                    ['author' => 'Sophia Martinez', 'comment' => 'Intern accepted invitation. Ticket resolved.', 'created_at' => Carbon::now()->subDays(3)->addHours(2)]
                ]
            ]
        ];

        foreach ($ticketsData as $ticketItem) {
            $comments = $ticketItem['comments'];
            unset($ticketItem['comments']);

            $ticket = Ticket::create($ticketItem);

            foreach ($comments as $commentItem) {
                $commentItem['ticket_id'] = $ticket->id;
                Comment::create($commentItem);
            }
        }
    }
}
