<?php

namespace App\Console\Commands;

use App\Models\GuestList;
use App\Models\Host;
use App\Models\Invitation;
use App\Models\Ceramonies;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendRemindersCommand extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send automated WhatsApp reminders to guests.';

    public function handle()
    {
        $hosts = Host::where('reminders_active', true)->get();
        $this->info("Found " . $hosts->count() . " active reminder campaigns.");

        foreach ($hosts as $host) {
            $invitation = Invitation::with('venue')->where('host_id', $host->id)->latest()->first();

            if (!$invitation) {
                $this->warn("Host {$host->id} skipped: No invitation found.");
                continue;
            }

            $weddingDateString = $invitation->wedding_date ?? null;
            if (!$weddingDateString) {
                $this->warn("Host {$host->id} skipped: No wedding date found.");
                continue;
            }

            try {
                $weddingDate = Carbon::parse($weddingDateString)->startOfDay();
                $today = Carbon::now()->startOfDay();
                $daysRemaining = (int) $today->diffInDays($weddingDate, false);

                // If wedding is today or in the past, stop sending and deactivate reminders for this host
                if ($daysRemaining <= 0) {
                    $this->warn("Host {$host->id} campaign stopped: Days remaining is $daysRemaining (Wedding date: $weddingDateString).");
                    $host->reminders_active = false;
                    $host->save();
                    continue;
                }

                $guests = GuestList::where('host_id', $host->id)
                    ->where('invitation_sent', 1)
                    ->get();

                if ($guests->isEmpty()) {
                    $this->warn("Host {$host->id} skipped: No guests with invitation_sent = 1.");
                    continue;
                }

                $this->info("Processing Host {$host->id}: $daysRemaining days left, sending to {$guests->count()} guests.");

                $venueName = $invitation->venue ? $invitation->venue->venue_name : 'Our Wedding Venue';
                $venueUrl = $invitation->venue ? $invitation->venue->location_map : env('APP_URL');

                $imageUrl = env('APP_URL') . '/storage/' . $host->reminder_image;

                // Format "26 days" or "1 day"
                $daysText = $daysRemaining > 1 ? $daysRemaining . ' days' : $daysRemaining . ' day';

                // Send to each guest
                foreach ($guests as $guest) {
                    $this->sendWhatsAppReminder($guest, $invitation, $daysText, $venueName, $venueUrl, $imageUrl);
                }

                // Increment counter by the number of guests
                $host->reminders_sent_count += $guests->count();
                $host->save();

            } catch (\Exception $e) {
                Log::error("Error processing reminders for Host {$host->id}: " . $e->getMessage());
                $this->error("Error for Host {$host->id}: " . $e->getMessage());
            }
        }

        $this->info('Reminders sent successfully.');
    }

    protected function sendWhatsAppReminder($guest, $invitation, $daysRemaining, $venueName, $venueUrl, $imageUrl)
    {
        $authKey = env('MSG91_AUTH_KEY');
        $rawNumber = $guest->whatsapp_number ?? $guest->guest_number;
        $cleanNumber = preg_replace('/[^0-9]/', '', $rawNumber);
        if (strlen($cleanNumber) === 10) {
            $cleanNumber = '91' . $cleanNumber;
        }

        $relation = trim(strtolower($guest->relation ?? ''));

        // Determine name order based on relation
        $brideName = $invitation->bride_name ?? 'Bride';
        $groomName = $invitation->groom_name ?? 'Groom';

        if ($relation === 'bride' || $relation === 'bride_parent') {
            $var2 = $brideName;
            $var3 = $groomName;
        } else {
            // Default to groom first for groom relation or unknown
            $var2 = $groomName;
            $var3 = $brideName;
        }

        $safeVar2 = !empty($var2) ? (string)$var2 : 'Guest';
        $safeVar3 = !empty($var3) ? (string)$var3 : 'Guest';
        $safeVenueName = !empty($venueName) ? (string)$venueName : 'Wedding Venue';
        $safeVenueUrl = !empty($venueUrl) ? (string)$venueUrl : 'https://maps.google.com';
        
        // If testing locally, MSG91 cannot download 'localhost' images. Use a public fallback.
        if (str_contains($imageUrl, 'localhost') || str_contains($imageUrl, '127.0.0.1')) {
            $imageUrl = 'https://picsum.photos/600/400'; // Public placeholder so MSG91 doesn't fail
        }

        $payload = [
            'integrated_number' => '919360777089',
            'content_type' => 'template',
            'payload' => [
                'messaging_product' => 'whatsapp',
                'type' => 'template',
                'template' => [
                    'name' => 'remin_der',
                    'language' => ['code' => 'en', 'policy' => 'deterministic'],
                    'namespace' => 'bc3735fb_a2e9_4e83_8b62_377bca25c09f',
                    'to_and_components' => [
                        [
                            'to' => [$cleanNumber],
                            'components' => [
                                'header_1' => [
                                    'type' => 'image',
                                    'value' => $imageUrl
                                ],
                                'body_var_1' => ['type' => 'text', 'value' => (string)$daysRemaining, 'parameter_name' => 'var_1'],
                                'body_var_2' => ['type' => 'text', 'value' => $safeVar2, 'parameter_name' => 'var_2'],
                                'body_var_3' => ['type' => 'text', 'value' => $safeVar3, 'parameter_name' => 'var_3'],
                                'body_var_4' => ['type' => 'text', 'value' => $safeVenueName, 'parameter_name' => 'var_4'],
                                'body_var_5' => ['type' => 'text', 'value' => $safeVenueUrl, 'parameter_name' => 'var_5'],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $authKey,
        ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);

        $this->info("MSG91 Response for {$cleanNumber}: " . $response->body());
        Log::info("MSG91 Payload: ", $payload);
        Log::info("MSG91 Response: " . $response->body());

        if (!$response->successful()) {
            Log::error("Failed to send WhatsApp reminder to Guest {$guest->id}: " . $response->body());
            $this->error("Failed to send WhatsApp reminder to Guest {$guest->id}: " . $response->body());
        }
    }
}
