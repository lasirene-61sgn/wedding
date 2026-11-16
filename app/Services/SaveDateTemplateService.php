<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\SaveDate;
use App\Models\GuestList;
use App\Models\VenueName;
use App\Models\Ceramonies;
use App\Models\CategoryVenue;
use Carbon\Carbon;

class SaveDateTemplateService
{
    /**
     * Return all available default Save The Date templates.
     */
    public static function getTemplates(): array
    {
        return [
            [
                'id'            => 'savethedate/invitation.html',
                'name'          => 'Crimson Blossom',
                'theme'         => 'Curtain Reveal & Wax Seal',
                'color'         => '#c83e5b',
                'secondary'     => '#fadadd',
                'badge'         => 'Floral Romance',
                'description'   => 'Drape curtain opening with a glossy wax seal stamp, floating flower petals, and a scalloped floral arch card.',
                'file'          => 'savethedate/invitation.html',
            ],
            [
                'id'            => 'savethedate/invitationone.html',
                'name'          => 'Royal Sapphire',
                'theme'         => 'Damask Envelope & Cerulean Seal',
                'color'         => '#1992d4',
                'secondary'     => '#071330',
                'badge'         => 'Royal Luxury',
                'description'   => 'Embossed floral damask envelope with a cerulean blue seal, gold shimmer canvas, and gold foil frame.',
                'file'          => 'savethedate/invitationone.html',
            ],
            [
                'id'            => 'savethedate/invitationtwo.html',
                'name'          => 'Golden Mughal',
                'theme'         => 'Botanical Gates & Rotating Sunflower',
                'color'         => '#d4af37',
                'secondary'     => '#1c1108',
                'badge'         => 'Heritage Grandeur',
                'description'   => 'Palatial gold foliage gates opening with a serrated gold coin and an animated rotating sunflower medallion.',
                'file'          => 'savethedate/invitationtwo.html',
            ],
        ];
    }

    /**
     * Resolve the absolute path of a template.
     */
    public static function resolveTemplatePath(string $templateKey): ?string
    {
        $clean = ltrim($templateKey, '/\\');
        $candidates = [
            base_path($clean),
            public_path($clean),
            base_path('savethedate/' . basename($clean)),
            public_path('savethedate/' . basename($clean)),
            storage_path('app/public/' . $clean),
        ];

        foreach ($candidates as $path) {
            if (file_exists($path) && is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Render the template with dynamic host, ceremony, venue, and couple values.
     */
    public static function render(
        string $templateKey,
        ?Invitation $invitation,
        ?SaveDate $saveDate = null,
        ?GuestList $guest = null
    ): ?string {
        $path = self::resolveTemplatePath($templateKey);
        if (!$path) {
            return null;
        }

        $html = file_get_contents($path);

        // 1. Resolve Couple Names, First Words & Starting Letters (Initials)
        $cleanHonorific = function(?string $name): string {
            $trimmed = trim($name ?? '');
            $cleaned = preg_replace('/^(Mrs|Miss|Mr|Ms|Dr|Er|Shri|Smt)\.?\s*/i', '', $trimmed);
            return !empty($cleaned) ? $cleaned : $trimmed;
        };

        $brideRaw = $cleanHonorific($invitation->bride_name ?? 'Bride');
        $groomRaw = $cleanHonorific($invitation->groom_name ?? 'Groom');

        $useFirstName = $saveDate ? (bool)($saveDate->use_first_name ?? true) : true;
        $nameOrder = $saveDate ? ($saveDate->name_order ?? 'bride_groom') : 'bride_groom';

        $brideWords = array_values(array_filter(preg_split('/\s+/', $brideRaw)));
        $groomWords = array_values(array_filter(preg_split('/\s+/', $groomRaw)));

        $brideFirstWord = $brideWords[0] ?? 'Bride';
        $groomFirstWord = $groomWords[0] ?? 'Groom';

        $brideDisplay = $useFirstName ? $brideFirstWord : $brideRaw;
        $groomDisplay = $useFirstName ? $groomFirstWord : $groomRaw;

        // Guaranteed uppercase starting letters (initials)
        $bInitial = mb_strtoupper(mb_substr($brideFirstWord, 0, 1));
        $gInitial = mb_strtoupper(mb_substr($groomFirstWord, 0, 1));
        if (empty($bInitial)) $bInitial = 'B';
        if (empty($gInitial)) $gInitial = 'G';

        if ($nameOrder === 'groom_bride') {
            $coupleNames = $groomDisplay . ' & ' . $brideDisplay;
            $coupleNamesAmp = htmlspecialchars($groomDisplay) . ' &amp; ' . htmlspecialchars($brideDisplay);
            $initial1 = $gInitial;
            $initial2 = $bInitial;
            $first1 = $groomDisplay;
            $first2 = $brideDisplay;
        } else {
            $coupleNames = $brideDisplay . ' & ' . $groomDisplay;
            $coupleNamesAmp = htmlspecialchars($brideDisplay) . ' &amp; ' . htmlspecialchars($groomDisplay);
            $initial1 = $bInitial;
            $initial2 = $gInitial;
            $first1 = $brideDisplay;
            $first2 = $groomDisplay;
        }

        // 2. Resolve Selected Ceremony & Category
        $ceremony = null;
        $hostId = $saveDate->host_id ?? $invitation->host_id ?? null;

        if ($saveDate && $saveDate->ceremony_id) {
            $ceremony = Ceramonies::find($saveDate->ceremony_id);
        }
        if (!$ceremony && $saveDate && $saveDate->category_id && $hostId) {
            $ceremony = Ceramonies::where('host_id', $hostId)->where('category_id', $saveDate->category_id)->first();
        }
        if (!$ceremony && $hostId) {
            $ceremony = Ceramonies::where('host_id', $hostId)->where('is_main', true)->first()
                     ?? Ceramonies::where('host_id', $hostId)->first();
        }

        $ceremonyName = $ceremony->ceramony_name ?? 'Wedding Ceremony';

        // 3. Resolve Wedding Date & Time from Ceremony or Invitation
        $rawDate = $ceremony->ceramony_date ?? $invitation->wedding_date ?? null;
        $dateFormatted = !empty($rawDate) ? Carbon::parse($rawDate)->format('d F Y') : 'Date to be announced';

        $rawTime = $ceremony->ceramony_time ?? $invitation->wedding_time ?? null;
        $timeFormatted = !empty($rawTime) ? Carbon::parse($rawTime)->format('h:i A') : '';

        // 4. Resolve Wedding Venue
        $venue = null;
        if ($saveDate && $saveDate->venue_id) {
            $venue = VenueName::find($saveDate->venue_id);
        } elseif ($ceremony && $ceremony->venue_id) {
            $venue = VenueName::find($ceremony->venue_id);
        } elseif ($invitation && $invitation->venue_id) {
            $venue = VenueName::find($invitation->venue_id);
        }

        $venueName = $venue->venue_name ?? $invitation->wedding_location ?? 'Venue to be announced';
        $venueAddress = '';
        if ($venue) {
            $parts = array_filter([$venue->venue_address, $venue->area_name, $venue->district, $venue->state]);
            $venueAddress = implode(', ', $parts);
        }

        // 5. Save The Date Message
        $message = $saveDate->message ?? ($invitation ? 'We are getting married! Please save the date.' : '');

        // 6. Guest Details URL
        $guestName = $guest->guest_name ?? 'Guest';
        $detailsUrl = '#';
        try {
            if ($guest && !empty($guest->uuid)) {
                $detailsUrl = route('guest.wedding.details', $guest->uuid);
            } elseif ($invitation) {
                $detailsUrl = url('/wedding/' . ($invitation->host_id ?? '1') . '/details');
            }
        } catch (\Throwable $e) {
            $detailsUrl = '#';
        }

        // 7. Dynamic Inner Monograms with Bride & Groom Starting Letters
        // Template 1 (invitation.html)
        $monogram1 = '<div class="monogram-wrapper" style="display:flex; align-items:center; justify-content:center;">'
            . '<svg viewBox="0 0 240 180" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">'
            . '<defs><filter id="crimsonGlow" x="-20%" y="-20%" width="140%" height="140%"><feDropShadow dx="0" dy="4" stdDeviation="4" flood-color="rgba(0,0,0,0.35)"/></filter></defs>'
            . '<text x="70" y="125" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="95" font-weight="700" fill="#fceef2" text-anchor="middle" filter="url(#crimsonGlow)">' . $initial1 . '</text>'
            . '<text x="120" y="112" font-family="\'Great Vibes\', cursive" font-size="58" fill="#f4a6b8" text-anchor="middle">&amp;</text>'
            . '<text x="170" y="132" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="95" font-weight="700" fill="#fceef2" text-anchor="middle" filter="url(#crimsonGlow)">' . $initial2 . '</text>'
            . '</svg></div>';
        $html = preg_replace('/<div class="monogram-wrapper"[^>]*>.*?<\/div>/s', $monogram1, $html);

        // Template 2 (invitationone.html) - Fully self-contained SVG gradients and drop shadow
        $monogram2 = '<div class="rp-monogram-box" style="display:flex; align-items:center; justify-content:center;">'
            . '<svg viewBox="0 0 240 180" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">'
            . '<defs>'
            . '<linearGradient id="monogramBlue" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#2e67c9"/><stop offset="100%" stop-color="#1c499c"/></linearGradient>'
            . '<linearGradient id="goldMonogram" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#fdf497"/><stop offset="35%" stop-color="#f5d061"/><stop offset="70%" stop-color="#d4af37"/><stop offset="100%" stop-color="#aa771c"/></linearGradient>'
            . '<filter id="royalShadow" x="-20%" y="-20%" width="140%" height="140%"><feDropShadow dx="1" dy="3" stdDeviation="3" flood-color="rgba(13,33,79,0.35)"/></filter>'
            . '</defs>'
            . '<text x="70" y="125" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="95" font-weight="700" fill="url(#monogramBlue)" text-anchor="middle" filter="url(#royalShadow)">' . $initial1 . '</text>'
            . '<text x="120" y="112" font-family="\'Great Vibes\', cursive" font-size="58" fill="url(#goldMonogram)" text-anchor="middle">&amp;</text>'
            . '<text x="170" y="132" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="95" font-weight="700" fill="url(#monogramBlue)" text-anchor="middle" filter="url(#royalShadow)">' . $initial2 . '</text>'
            . '</svg></div>';
        $html = preg_replace('/<div class="rp-monogram-box"[^>]*>.*?<\/div>/s', $monogram2, $html);

        // Template 3 (invitationtwo.html) - Metallic gold gradient & deep shadow
        $monogram3 = '<svg class="ks-monogram-svg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">'
            . '<defs>'
            . '<linearGradient id="ksGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#fff099"/><stop offset="35%" stop-color="#f5c42f"/><stop offset="70%" stop-color="#c98a14"/><stop offset="100%" stop-color="#ffdf6b"/></linearGradient>'
            . '<filter id="ksMughalShadow" x="-20%" y="-20%" width="140%" height="140%"><feDropShadow dx="2" dy="4" stdDeviation="4" flood-color="rgba(0,0,0,0.6)"/></filter>'
            . '</defs>'
            . '<path d="M 82,46 C 95,46 108,38 122,42 C 134,45 142,54 134,58 C 128,52 115,48 98,52 C 86,55 75,48 72,38 C 78,44 86,44 94,38 C 90,34 82,34 78,30 C 86,30 96,35 98,44" fill="url(#ksGoldGrad)" />'
            . '<text x="60" y="128" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="80" font-weight="700" fill="url(#ksGoldGrad)" text-anchor="middle" filter="url(#ksMughalShadow)">' . $initial1 . '</text>'
            . '<text x="100" y="115" font-family="\'Great Vibes\', cursive" font-size="48" fill="#ffd700" text-anchor="middle">&amp;</text>'
            . '<text x="140" y="138" font-family="\'Cinzel\', \'Playfair Display\', serif" font-size="80" font-weight="700" fill="url(#ksGoldGrad)" text-anchor="middle" filter="url(#ksMughalShadow)">' . $initial2 . '</text>'
            . '</svg>';
        $html = preg_replace('/<svg class="ks-monogram-svg"[^>]*>.*?<\/svg>/s', $monogram3, $html);

        // 9. Replace Ceremony Name in templates
        $html = preg_replace('/<h1 class="title-script">.*?<\/h1>/s', '<h1 class="title-script">' . htmlspecialchars($ceremonyName) . '</h1>', $html);
        $html = preg_replace('/<h1 class="invite-script-title">.*?<\/h1>/s', '<h1 class="invite-script-title">' . htmlspecialchars($ceremonyName) . '</h1>', $html);
        $html = preg_replace('/<h1 class="ceremony-script">.*?<\/h1>/s', '<h1 class="ceremony-script">' . htmlspecialchars($ceremonyName) . '</h1>', $html);

        // 10. Replace Couple Names in templates
        $html = preg_replace('/<h2 class="couple-names">.*?<\/h2>/s', '<h2 class="couple-names">' . $coupleNamesAmp . '</h2>', $html);
        $html = preg_replace('/<h2 class="couple-names-one">.*?<\/h2>/s', '<h2 class="couple-names-one">' . $coupleNamesAmp . '</h2>', $html);
        $html = preg_replace('/<h2 class="couple-names-footer">.*?<\/h2>/s', '<h2 class="couple-names-footer">' . $coupleNamesAmp . '</h2>', $html);

        // 11. Replace Standard Text Placeholders
        $venueFullDisplay = htmlspecialchars($venueName);
        if (!empty($venueAddress)) {
            $venueFullDisplay .= ' &bull; ' . htmlspecialchars($venueAddress);
        }

        $replacements = [
            '[BRIDE_NAME]'            => htmlspecialchars($brideDisplay),
            '[GROOM_NAME]'            => htmlspecialchars($groomDisplay),
            '[COUPLE_NAMES]'          => $coupleNamesAmp,
            '[BRIDE_INITIAL]'         => htmlspecialchars($initial1),
            '[GROOM_INITIAL]'         => htmlspecialchars($initial2),
            '[INITIAL_1]'             => htmlspecialchars($initial1),
            '[INITIAL_2]'             => htmlspecialchars($initial2),
            '[CEREMONY_NAME]'         => htmlspecialchars($ceremonyName),
            '[WEDDING_DATE]'          => htmlspecialchars($dateFormatted),
            '[WEDDING_TIME]'          => htmlspecialchars($timeFormatted),
            '[VENUE_NAME]'            => $venueFullDisplay,
            '[VENUE_ADDRESS]'         => htmlspecialchars($venueAddress),
            '[SAVE_THE_DATE_MESSAGE]' => htmlspecialchars($message),
            '[MESSAGE]'               => htmlspecialchars($message),
            '[GUEST_NAME]'            => htmlspecialchars($guestName),
            '[DETAILS_URL]'           => $detailsUrl,
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // 12. Legacy or Fallback String Replacements
        $html = str_replace('Aarav &amp; Ananya', $coupleNamesAmp, $html);
        $html = str_replace('Aarav & Ananya', $coupleNames, $html);
        $html = str_replace('Karan <span class="amp">&amp;</span> Simran', $coupleNamesAmp, $html);
        $html = str_replace('Karan &amp; Simran', $coupleNamesAmp, $html);
        $html = str_replace('Karan & Simran', $coupleNames, $html);

        $html = str_replace('Aarav & Ananya - Wedding Invitation', $coupleNames . ' - ' . $ceremonyName . ' Save The Date', $html);
        $html = str_replace('RP - Wedding Invitation', $coupleNames . ' - ' . $ceremonyName . ' Save The Date', $html);
        $html = str_replace('Karan & Simran - Wedding Invitation', $coupleNames . ' - ' . $ceremonyName . ' Save The Date', $html);

        return $html;
    }
}
