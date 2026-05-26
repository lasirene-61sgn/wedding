<?php

namespace App\Services;

use App\Models\GuestList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class InvitationService
{
    public function sendBulkInvitations(GuestList $guest, array $channels, string $ceremonyNames)
    {
        $message = "Hello {$guest->guest_name}, you are cordially invited to our wedding ceremonies: {$ceremonyNames}. View details here: " . route('host.guestlist.show', $guest->id);

        foreach ($channels as $channel) {
            try {
                if ($channel === 'sms' && $guest->guest_number) {
                    $this->sendSMS($guest->guest_number, $message);
                }

                if ($channel === 'whatsapp') {
                    $this->sendWhatsApp($guest, $ceremonyNames);
                }

                if ($channel === 'email') {
                    // FIXED: Pass the full $guest model instance object, NOT a string column
                    $this->sendEmail($guest, $ceremonyNames);
                }
            } catch (\Exception $e) {
                Log::error("Failed to send invitation to {$channel} to guest {$guest->id} : " . $e->getMessage());
            }
        }
    }

    protected function sendSMS($number, $message) {}

    protected function sendWhatsApp(\App\Models\GuestList $guest, string $ceremonyNames)
    {
        $authKey = config('services.msg91.authkey', env('MSG91_AUTH_KEY'));

        // 1. Determine template name dynamically based on relation values
        $relation = trim(strtolower($guest->relation));

        if ($relation === 'bride') {
            $templateName = 'invite_bridemsg';
        } elseif ($relation === 'groom_parent' || $guest->category_id == 2) {
            // Handles your alternative Groom/Parent layout template selection rule
            $templateName = 'invit_org2';
        } else {
            // Default fallback template for general groom side invites
            $templateName = 'invit_org1';
        }

        // 2. Extract and format phone number safely (Enforce '91' prefix for India)
        $rawNumber = $guest->whatsapp_number ?? $guest->guest_number;
        $cleanNumber = preg_replace('/[^0-9]/', '', $rawNumber);

        if (strlen($cleanNumber) === 10) {
            $cleanNumber = '91' . $cleanNumber;
        }

        // 3. Construct dynamic routing parameters mapping body variables
        $bodyVariables = [
            'body_1' => ['type' => 'text', 'value' => $guest->guest_name],
            'body_2' => ['type' => 'text', 'value' => $ceremonyNames],
            'body_3' => ['type' => 'text', 'value' => 'Our Wedding Venue Address'],
            'body_4' => ['type' => 'text', 'value' => route('guest.rsvp.portal', ['id' => $guest->id])],
        ];

        // 4. Construct the structured payload array layout expected by MSG91 API engine
        $payload = [
            'integrated_number' => '919360777089',
            'content_type' => 'template',
            'payload' => [
                'messaging_product' => 'whatsapp',
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => 'en',
                        'policy' => 'deterministic'
                    ],
                    'namespace' => 'bc3735fb_a2e9_4e83_8b62_377bca25c09f',
                    'to_and_components' => [
                        [
                            'to' => [
                                $cleanNumber
                            ],
                            'components' => $bodyVariables
                        ]
                    ]
                ]
            ]
        ];

        // 5. Fire Outbound POST Network Call Synchronously
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $authKey,
        ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);

        // 6. Debugging Check: Fixed Facade scopes using fully qualified roots (\Illuminate\Support\Facades\Log)
        \Illuminate\Support\Facades\Log::info("MSG91 Log Dispatch Status for Guest ID [{$guest->id}]: " . $response->status());
        \Illuminate\Support\Facades\Log::info("MSG91 Log Response Payload Body text: " . $response->body());

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error("MSG91 WhatsApp Transaction Failure for Guest ID {$guest->id}: " . $response->body());
        }
    }

    protected function sendEmail(\App\Models\GuestList $guest, string $ceremonyNames)
    {
        try {

            /*
        |--------------------------------------------------------------------------
        | MSG91 Configuration
        |--------------------------------------------------------------------------
        */

            $authKey = env('MSG91_AUTH_KEY');

            $fromEmail = env('MAIL_FROM_ADDRESS');

            $fromName = env('MAIL_FROM_NAME');

            $mailDomain = explode('@', $fromEmail)[1] ?? 'localhost.com';

            /*
        |--------------------------------------------------------------------------
        | Fetch Invitation Data
        |--------------------------------------------------------------------------
        */

            $invitation = \App\Models\Invitation::where(
                'host_id',
                $guest->host_id
            )->latest()->first();

            if (!$invitation) {

                Log::error('Invitation data not found', [

                    'guest_id' => $guest->id
                ]);

                return;
            }

            /*
        |--------------------------------------------------------------------------
        | Ceremony Data
        |--------------------------------------------------------------------------
        */

            $ceremony = \App\Models\Ceramonies::where(
                'host_id',
                $guest->host_id
            )->latest()->first();

            /*
        |--------------------------------------------------------------------------
        | Wedding Date
        |--------------------------------------------------------------------------
        */

            $weddingDate = $ceremony->ceramony_date
                ?? $invitation->wedding_date
                ?? date('d F Y');

            /*
        |--------------------------------------------------------------------------
        | RSVP Link
        |--------------------------------------------------------------------------
        */

            $shortLink = route('guest.rsvp.portal', [
                'id' => $guest->id
            ]);

            /*
        |--------------------------------------------------------------------------
        | Relation
        |--------------------------------------------------------------------------
        */

            $relation = strtolower(trim($guest->relation ?? ''));

            /*
        |--------------------------------------------------------------------------
        | Template Variables
        |--------------------------------------------------------------------------
        */

            $templateId = '';

            $emailVariables = [];

            /*
        |--------------------------------------------------------------------------
        | Bride / Groom Invitation
        |--------------------------------------------------------------------------
        */

            if (
                $relation === 'bride'
                || $relation === 'groom'
            ) {

                $templateId = 'invite_org_6';

                $emailVariables = [

                    'brideName' => $invitation->bride_name ?? '',

                    'groomName' => $invitation->groom_name ?? '',

                    'date' => $weddingDate,

                    'shortLink' => $shortLink,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | Groom Parents Invitation
        |--------------------------------------------------------------------------
        */ elseif ($relation === 'groom_parent') {

                $templateId = 'invite_org_4';

                $emailVariables = [

                    'groomMotherName'
                    => $invitation->groom_mother_name ?? '',

                    'groomFatherName'
                    => $invitation->groom_father_name ?? '',

                    'date' => $weddingDate,

                    'shortLink' => $shortLink,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | Bride Parents Invitation
        |--------------------------------------------------------------------------
        */ elseif ($relation === 'bride_parent') {

                $templateId = 'invite_org_5';

                $emailVariables = [

                    'brideMotherName'
                    => $invitation->bride_mother_name ?? '',

                    'brideFatherName'
                    => $invitation->bride_father_name ?? '',

                    'date' => $weddingDate,

                    'shortLink' => $shortLink,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | Unknown Relation
        |--------------------------------------------------------------------------
        */ else {

                Log::warning('Unknown Relation', [

                    'guest_id' => $guest->id,

                    'relation' => $relation,
                ]);

                return;
            }

            /*
        |--------------------------------------------------------------------------
        | Debug Logs
        |--------------------------------------------------------------------------
        */

            Log::info('MSG91 Final Payload', [

                'guest_id' => $guest->id,

                'relation' => $relation,

                'template_id' => $templateId,

                'variables' => $emailVariables,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Final Payload
        |--------------------------------------------------------------------------
        */

            $payload = [

                'template_id' => $templateId,

                'domain' => $mailDomain,

                'from' => [

                    'name' => $fromName,

                    'email' => $fromEmail,
                ],

                'recipients' => [

                    [

                        'to' => [

                            [

                                'name' => $guest->guest_name,

                                'email' => $guest->guest_email,
                            ]
                        ],

                        'variables' => $emailVariables,
                    ]
                ]
            ];

            /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

            $response = \Illuminate\Support\Facades\Http::withHeaders([

                'accept' => 'application/json',

                'content-type' => 'application/json',

                'authkey' => $authKey,

            ])->post(
                'https://control.msg91.com/api/v5/email/send',
                $payload
            );

            /*
        |--------------------------------------------------------------------------
        | Response Logs
        |--------------------------------------------------------------------------
        */

            Log::info(
                "MSG91 Email Status for Guest ID [{$guest->id}]: "
                    . $response->status()
            );

            Log::info(
                "MSG91 Email Response Body: "
                    . $response->body()
            );

            /*
        |--------------------------------------------------------------------------
        | Failure Logs
        |--------------------------------------------------------------------------
        */

            if (!$response->successful()) {

                Log::error('MSG91 Email Failed', [

                    'guest_id' => $guest->id,

                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {

            Log::error('MSG91 Email Exception', [

                'guest_id' => $guest->id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),
            ]);
        }
    }
}
