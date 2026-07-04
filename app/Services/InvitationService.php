<?php

namespace App\Services;

use App\Models\Ceramonies;
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
                    $this->sendSMS($guest);
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

    /**
     * Core SMS Engine utilizing the unified MSG91 Flow API endpoint.
     * Backed by hardcoded fallback parameters to guarantee execution if the .env is cached.
     *
     * @param \App\Models\GuestList $guest
     * @return void
     */
    protected function sendSMS(\App\Models\GuestList $guest)
    {
        try {
            $authKey = env('MSG91_AUTH_KEY');

            $invitation = \App\Models\Invitation::where('host_id', $guest->host_id)->latest()->first();
            if (!$invitation) {
                \Illuminate\Support\Facades\Log::error("SMS Engine Failure: Invitation metadata record missing for Host ID [{$guest->host_id}]");
                return;
            }

            // Note: Change 'Ceremonies' back to 'Ceramonies' if your model filename uses an 'a'
            $ceremony = Ceramonies::where('host_id', $guest->host_id)->latest()->first();
            $weddingDate = $ceremony->ceramony_date ?? $invitation->wedding_date ?? date('d F Y');

            $rawNumber = $guest->guest_number;
            $cleanNumber = preg_replace('/[^0-9]/', '', $rawNumber);
            if (strlen($cleanNumber) === 10) {
                $cleanNumber = '91' . $cleanNumber;
            }

            $relation = strtolower(trim($guest->relation ?? ''));
            $shortLink = route('guest.rsvp.portal', ['id' => $guest->id]);

            // 1. Determine who is creating the invitation and put their name first
            if ($relation === 'bride' || $relation === 'groom') {
                // For bride_groom_invit template
                $msg91TemplateId = env('MSG91_FLOW_COUPLE_ID', '6a17d05b5e19870c280f5242');
                $smsVariables = [
                    'var1' => $invitation->bride_name ?? 'Bride',
                    'var2' => $invitation->groom_name ?? 'Groom',
                    'var3' => $shortLink,
                ];
            } elseif ($relation === 'bride_parent') {
                // For Invite_Bride template
                $msg91TemplateId = env('MSG91_FLOW_BRIDE_PARENT_ID', '68a577370fc63d1e78442d26');
                $smsVariables = [
                    'var1' => $invitation->bride_mother_name ?? '',
                    'var2' => $invitation->bride_father_name ?? '',
                    'var3' => $shortLink,
                ];
            } elseif ($relation === 'groom_parent') {
                // For Invite_Groom template
                $msg91TemplateId = env('MSG91_FLOW_GROOM_PARENT_ID', '68a598a35af05446a26303f9');
                $smsVariables = [
                    'var1' => $invitation->groom_mother_name ?? '',
                    'var2' => $invitation->groom_father_name ?? '',
                    'var3' => $shortLink,
                ];
            } else {
                return; // Unknown relation, abort
            }

            // Also keep legacy variables just in case the template still uses them
            $smsVariables['inviterName'] = $smsVariables['var1'] . ' & ' . $smsVariables['var2'];
            $smsVariables['weddingDate'] = $weddingDate;
            $smsVariables['shortLink'] = $shortLink;

            $recipient = array_merge(['mobiles' => $cleanNumber], $smsVariables);

            $payload = [
                'template_id' => $msg91TemplateId,
                'short_url'   => 0,
                'recipients'  => [$recipient]
            ];

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'authkey'      => $authKey,
                'accept'       => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/flow/', $payload);

            \Illuminate\Support\Facades\Log::info("MSG91 API Dispatched [Guest ID: {$guest->id}]. HTTP Status Code: " . $response->status());
            \Illuminate\Support\Facades\Log::info("MSG91 API Response Body: " . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Fatal execution runtime failure inside sendSMS: " . $e->getMessage());
        }
    }

    protected function sendWhatsApp(\App\Models\GuestList $guest, string $ceremonyNames)
    {
        $authKey = config('services.msg91.authkey', env('MSG91_AUTH_KEY'));

        $invitation = \App\Models\Invitation::where('host_id', $guest->host_id)->latest()->first();
        if (!$invitation) return;

        $ceremony = \App\Models\Ceramonies::where('host_id', $guest->host_id)->latest()->first();
        $weddingDate = $ceremony->ceramony_date ?? $invitation->wedding_date ?? date('d F Y');

        $shortLink = route('guest.rsvp.portal', ['id' => $guest->id]);
        $relation = trim(strtolower($guest->relation ?? ''));

        $rawNumber = $guest->whatsapp_number ?? $guest->guest_number;
        $cleanNumber = preg_replace('/[^0-9]/', '', $rawNumber);
        if (strlen($cleanNumber) === 10) {
            $cleanNumber = '91' . $cleanNumber;
        }

        // Mirror sendEmail logic for variables
        $val1 = '';
        $val2 = '';
        $templateName = '';

        if ($relation === 'bride' || $relation === 'groom') {
            $templateName = 'invit_org2';
            $val1 = $invitation->bride_name ?? '';
            $val2 = $invitation->groom_name ?? '';
        } elseif ($relation === 'groom_parent') {
            $templateName = 'invit_org1';
            $val1 = $invitation->groom_mother_name ?? '';
            $val2 = $invitation->groom_father_name ?? '';
        } elseif ($relation === 'bride_parent') {
            $templateName = 'invite_bridemsg';
            $val1 = $invitation->bride_mother_name ?? '';
            $val2 = $invitation->bride_father_name ?? '';
        } else {
            return;
        }

        $bodyVariables = [
            'body_1' => ['type' => 'text', 'value' => $val1],
            'body_2' => ['type' => 'text', 'value' => $val2],
            'body_3' => ['type' => 'text', 'value' => $weddingDate],
            'body_4' => ['type' => 'text', 'value' => $shortLink],
        ];

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
                            'to' => [$cleanNumber],
                            'components' => $bodyVariables
                        ]
                    ]
                ]
            ]
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'authkey' => $authKey,
        ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);

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

    public function sendBulkReminders(GuestList $guest, array $channels, string $ceremonyNames)
    {
        foreach ($channels as $channel) {
            try {
                if ($channel === 'sms' && $guest->guest_number) {
                    $this->sendReminderSMS($guest, $ceremonyNames);
                }

                if ($channel === 'whatsapp') {
                    $this->sendReminderWhatsApp($guest, $ceremonyNames);
                }

                if ($channel === 'email' && $guest->guest_email) {
                    $this->sendReminderEmail($guest, $ceremonyNames);
                }
            } catch (\Exception $e) {
                Log::error("Failed to send reminder via {$channel} to guest {$guest->id}: " . $e->getMessage());
            }
        }
    }

    protected function sendReminderSMS(GuestList $guest, string $ceremonyNames)
    {
        // TODO: Apply reminder SMS template here when provided.
        // For now, just logging the action.
        \Illuminate\Support\Facades\Log::info("sendReminderSMS triggered for Guest ID {$guest->id}");
    }

    protected function sendReminderWhatsApp(GuestList $guest, string $ceremonyNames)
    {
        // TODO: Apply reminder WhatsApp template here when provided.
        // For now, just logging the action.
        \Illuminate\Support\Facades\Log::info("sendReminderWhatsApp triggered for Guest ID {$guest->id}");
    }

    protected function sendReminderEmail(GuestList $guest, string $ceremonyNames)
    {
        // TODO: Apply reminder Email template here when provided.
        // For now, just logging the action.
        \Illuminate\Support\Facades\Log::info("sendReminderEmail triggered for Guest ID {$guest->id}");
    }
}
