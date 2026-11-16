@extends('layouts.host')

@section('content')
<div class="container-fluid py-4">
    {{-- Top Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #1e293b;">
                <i class="bi bi-heart-fill text-danger me-2"></i>Save The Date Announcements
            </h3>
            <p class="text-secondary small mb-0">
                Configure your ceremony, venue, couple name order, and default animated Save The Date template sent to your guests.
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-semibold shadow-xs" onclick="openPreviewModal('{{ $saveDate->template ?? 'savethedate/invitation.html' }}', 'Active Save The Date')">
                <i class="bi bi-eye me-1"></i> Preview Active Card
            </button>
            <a href="{{ route('host.guestlist.index') }}" class="btn btn-success rounded-pill px-4 fw-semibold shadow-xs">
                <i class="bi bi-send me-1"></i> Send Save The Dates
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('Success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('Success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" style="background: #fef2f2; color: #991b1b;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Active Announcement Overview Card --}}
    @php
        $activeTemplate = $saveDate->template ?? 'savethedate/invitation.html';
        $activeTplMeta = collect($templates)->firstWhere('id', $activeTemplate) ?? $templates[0];

        $cleanHonorific = function(?string $name): string {
            $trimmed = trim($name ?? '');
            $cleaned = preg_replace('/^(Mrs|Miss|Mr|Ms|Dr|Er|Shri|Smt)\.?\s*/i', '', $trimmed);
            return !empty($cleaned) ? $cleaned : $trimmed;
        };

        $cleanBride = $cleanHonorific($invitation->bride_name ?? 'Bride');
        $cleanGroom = $cleanHonorific($invitation->groom_name ?? 'Groom');

        $brideWords = array_values(array_filter(preg_split('/\s+/', $cleanBride)));
        $groomWords = array_values(array_filter(preg_split('/\s+/', $cleanGroom)));
        $bFirst = $brideWords[0] ?? 'Bride';
        $gFirst = $groomWords[0] ?? 'Groom';

        $bInitial = mb_strtoupper(mb_substr($bFirst, 0, 1));
        $gInitial = mb_strtoupper(mb_substr($gFirst, 0, 1));
        if (empty($bInitial)) $bInitial = 'B';
        if (empty($gInitial)) $gInitial = 'G';

        $isGroomFirst = ($saveDate->name_order ?? 'bride_groom') === 'groom_bride';
        $useFirstWord = $saveDate ? (bool)($saveDate->use_first_name ?? true) : true;

        $displayCouple = $isGroomFirst 
            ? (($useFirstWord ? $gFirst : $cleanGroom) . ' & ' . ($useFirstWord ? $bFirst : $cleanBride))
            : (($useFirstWord ? $bFirst : $cleanBride) . ' & ' . ($useFirstWord ? $gFirst : $cleanGroom));

        $activeInitials = $isGroomFirst ? ($gInitial . ' & ' . $bInitial) : ($bInitial . ' & ' . $gInitial);

        $selectedCeremony = $ceremonies->firstWhere('id', $saveDate->ceremony_id) 
            ?? $ceremonies->firstWhere('is_main', true) 
            ?? $ceremonies->first();

        $selectedVenue = $venues->firstWhere('id', $saveDate->venue_id) 
            ?? ($selectedCeremony ? $venues->firstWhere('id', $selectedCeremony->venue_id) : null)
            ?? ($invitation ? $venues->firstWhere('id', $invitation->venue_id) : null);
    @endphp

    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden active-hero-card" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white;">
        <div class="card-body p-4 p-md-5 position-relative">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge rounded-pill px-3 py-2 text-uppercase mb-3" style="background: rgba(255,255,255,0.15); letter-spacing: 1.5px; font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.25);">
                        <i class="bi bi-check-circle-fill text-warning me-1"></i> Active Save The Date
                    </span>
                    <h2 class="fw-bold mb-2" style="font-family: 'Playfair Display', serif;">
                        {{ $activeTplMeta['name'] }}
                    </h2>
                    <p class="text-white-50 mb-3" style="font-size: 0.95rem;">
                        {{ $activeTplMeta['description'] }}
                    </p>
                    
                    <div class="row g-3 py-3 mb-4 text-white-50 small" style="border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <div class="col-sm-6">
                            <span class="text-white fw-bold d-block"><i class="bi bi-people me-1 text-warning"></i> Couple Names:</span>
                            {{ $displayCouple }}
                        </div>
                        <div class="col-sm-6">
                            <span class="text-white fw-bold d-block"><i class="bi bi-stars me-1 text-warning"></i> Selected Ceremony:</span>
                            {{ $selectedCeremony ? $selectedCeremony->ceramony_name : 'Wedding Ceremony' }}
                        </div>
                        <div class="col-sm-6">
                            <span class="text-white fw-bold d-block"><i class="bi bi-calendar-event me-1 text-warning"></i> Date & Time:</span>
                            @if($selectedCeremony && !empty($selectedCeremony->ceramony_date))
                                {{ date('F j, Y', strtotime($selectedCeremony->ceramony_date)) }}
                                @if(!empty($selectedCeremony->ceramony_time)) &bull; {{ date('h:i A', strtotime($selectedCeremony->ceramony_time)) }} @endif
                            @elseif(!empty($invitation->wedding_date))
                                {{ date('F j, Y', strtotime($invitation->wedding_date)) }}
                            @else
                                Date to be announced
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <span class="text-white fw-bold d-block"><i class="bi bi-geo-alt me-1 text-warning"></i> Wedding Venue:</span>
                            {{ $selectedVenue ? $selectedVenue->venue_name : ($invitation->wedding_location ?? 'Venue to be announced') }}
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" onclick="openPreviewModal('{{ $activeTemplate }}', '{{ $activeTplMeta['name'] }}')">
                            <i class="bi bi-play-circle-fill me-1"></i> Test & Play Animation
                        </button>
                        <a href="{{ route('host.guestlist.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                            <i class="bi bi-send-fill me-1"></i> Send to Guest List
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 text-center">
                    <div class="phone-mockup-wrap mx-auto" onclick="openPreviewModal('{{ $activeTemplate }}', '{{ $activeTplMeta['name'] }}')" role="button" title="Click to preview card">
                        <div class="phone-mockup">
                            <iframe src="{{ route('host.savedate.preview', ['template' => $activeTemplate]) }}" class="mini-preview-iframe" tabindex="-1"></iframe>
                            <div class="mockup-overlay d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-arrows-fullscreen fs-3 mb-1"></i>
                                <span class="small fw-bold">Click to Expand & Test</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Save The Date Configuration (Couple First Words, Ceremony & Venue Selection) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white py-3 px-4 border-0">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-sliders text-primary me-2"></i>Configure Ceremony, Venue &amp; Couple Display
            </h5>
            <p class="text-muted small mb-0">Select which ceremony name will appear and which wedding venue will show on your Save The Date announcement.</p>
        </div>
        <div class="card-body p-4 pt-2">
            <form action="{{ route('host.savedate.update', $saveDate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="invitation_id" value="{{ $invitation->id ?? '' }}">
                <input type="hidden" name="template" value="{{ $saveDate->template ?? 'savethedate/invitation.html' }}">

                <div class="row g-4">
                    {{-- 1. Category Selection --}}
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">
                            <i class="bi bi-tag-fill text-primary me-1"></i> 1. Wedding Category
                        </label>
                        <select name="category_id" id="categorySelect" class="form-select rounded-3">
                            <option value="">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($selectedCategoryId == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Select category first to filter ceremonies below.</small>
                    </div>

                    {{-- 2. Ceremony Selection (Filtered by Category) --}}
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">
                            <i class="bi bi-stars text-warning me-1"></i> 2. Ceremony For Card
                        </label>
                        <select name="ceremony_id" id="ceremonySelect" class="form-select rounded-3">
                            <option value="">-- Main Wedding Ceremony --</option>
                            @foreach($ceremonies as $c)
                                <option value="{{ $c->id }}" 
                                        data-category-id="{{ $c->category_id }}" 
                                        data-venue-id="{{ $c->venue_id }}"
                                        data-date="{{ $c->ceramony_date ? date('F j, Y', strtotime($c->ceramony_date)) : '' }}"
                                        data-time="{{ $c->ceramony_time ? date('h:i A', strtotime($c->ceramony_time)) : '' }}"
                                        {{ ($saveDate->ceremony_id == $c->id) ? 'selected' : '' }}>
                                    {{ $c->ceramony_name }} 
                                    @if($c->ceramony_date) ({{ date('d M Y', strtotime($c->ceramony_date)) }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">This ceremony name, date &amp; time will show on the card.</small>
                    </div>

                    {{-- 3. Wedding Venue Selection --}}
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> 3. Wedding Venue
                        </label>
                        <select name="venue_id" id="venueSelect" class="form-select rounded-3">
                            <option value="">-- Auto (Ceremony Venue) --</option>
                            @foreach($venues as $v)
                                <option value="{{ $v->id }}" {{ ($saveDate->venue_id == $v->id) ? 'selected' : '' }}>
                                    {{ $v->venue_name }} @if(!empty($v->district)) ({{ $v->district }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Venue will display on the announcement.</small>
                    </div>

                    {{-- 4. Couple First Words & Starting Letters (Monogram) --}}
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">
                            <i class="bi bi-people-fill text-success me-1"></i> 4. Couple Name Order
                        </label>
                        <select name="name_order" id="nameOrderSelect" class="form-select rounded-3">
                            <option value="bride_groom" {{ ($saveDate->name_order ?? 'bride_groom') === 'bride_groom' ? 'selected' : '' }}>
                                Bride First ({{ $bFirst }} &amp; {{ $gFirst }})
                            </option>
                            <option value="groom_bride" {{ ($saveDate->name_order ?? '') === 'groom_bride' ? 'selected' : '' }}>
                                Groom First ({{ $gFirst }} &amp; {{ $bFirst }})
                            </option>
                        </select>
                        <div class="mt-2 d-flex flex-column gap-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="use_first_name" value="1" id="useFirstNameCheck" {{ $useFirstWord ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="useFirstNameCheck">
                                    Use <strong>First Words only</strong>
                                </label>
                            </div>
                            <div class="small d-flex align-items-center mt-1">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 me-1">
                                    <i class="bi bi-fonts me-1"></i>Seal Monogram:
                                </span>
                                <strong id="initialsDisplayBadge" class="text-dark">{{ $activeInitials }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Custom Message Presets & Text --}}
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Announcement Message Preset</label>
                                    <select id="msg_presets" class="form-select rounded-3 form-select-sm">
                                        <option value="">-- Choose a message template --</option>
                                        <option value="We can't wait to celebrate our special day with you! Please save the date.">Warm &amp; Classic</option>
                                        <option value="Save the date! We're getting married and we want you there to celebrate with us.">Modern &amp; Fun</option>
                                        <option value="A beautiful journey is about to begin. Please mark your calendars for our wedding.">Elegant &amp; Formal</option>
                                        <option value="custom">-- Clear custom message --</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Custom Announcement Note</label>
                                    <textarea name="message" id="message_text" rows="2" class="form-control rounded-3" placeholder="Enter message here...">{{ old('message', $saveDate->message) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-xs">
                            <i class="bi bi-check2-circle me-1"></i> Save Configuration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Section: Available Default Templates --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">Default Save The Date Templates</h4>
                <p class="text-muted small mb-0">Select from the ready-to-use animated templates below. Click to preview or activate.</p>
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ count($templates) }} Templates Available</span>
        </div>

        <div class="row g-4">
            @foreach($templates as $tpl)
                @php
                    $isSelected = ($activeTemplate === $tpl['id']);
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 template-card {{ $isSelected ? 'border-active-template' : '' }}" style="transition: all 0.3s ease;">
                        {{-- Card Header Preview Header --}}
                        <div class="card-img-top position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                             style="height: 240px; background: {{ $tpl['color'] }}; color: white; border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">
                            
                            {{-- Visual Theme Graphic --}}
                            <div class="text-center p-3">
                                @if(str_contains($tpl['id'], 'invitationtwo'))
                                    <div class="template-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.15); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #fcd34d;">
                                        <i class="bi bi-sun-fill" style="font-size: 2.5rem; color: #fde047;"></i>
                                    </div>
                                    <div class="small text-uppercase fw-bold tracking-wider text-warning">Gold Gates &amp; Sunflower</div>
                                @elseif(str_contains($tpl['id'], 'invitationone'))
                                    <div class="template-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.15); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #93c5fd;">
                                        <i class="bi bi-envelope-paper-heart-fill" style="font-size: 2.5rem; color: #93c5fd;"></i>
                                    </div>
                                    <div class="small text-uppercase fw-bold tracking-wider text-info">Embossed Damask Envelope</div>
                                @else
                                    <div class="template-icon-circle mx-auto mb-2" style="background: rgba(255,255,255,0.15); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #f472b6;">
                                        <i class="bi bi-flower1" style="font-size: 2.5rem; color: #fbcfe8;"></i>
                                    </div>
                                    <div class="small text-uppercase fw-bold tracking-wider" style="color: #fbcfe8;">Curtain Reveal &amp; Seal</div>
                                @endif
                            </div>

                            @if($isSelected)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-success shadow-sm rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i> ACTIVE TEMPLATE
                                    </span>
                                </div>
                            @endif

                            <div class="position-absolute bottom-0 end-0 m-3">
                                <span class="badge rounded-pill px-3 py-1 small" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
                                    {{ $tpl['badge'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="fw-bold mb-1 text-dark" style="font-family: 'Playfair Display', serif;">{{ $tpl['name'] }}</h5>
                                <div class="text-secondary small mb-2"><i class="bi bi-stars text-warning me-1"></i>{{ $tpl['theme'] }}</div>
                                <p class="text-muted small mb-3" style="min-height: 48px;">{{ $tpl['description'] }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-auto pt-3 border-top d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill flex-grow-1 fw-semibold btn-sm py-2" onclick="openPreviewModal('{{ $tpl['id'] }}', '{{ $tpl['name'] }}')">
                                    <i class="bi bi-eye me-1"></i> Preview
                                </button>
                                
                                @if($isSelected)
                                    <button type="button" class="btn btn-success rounded-pill flex-grow-1 fw-bold btn-sm py-2" disabled>
                                        <i class="bi bi-check-lg me-1"></i> Selected
                                    </button>
                                @else
                                    <form action="{{ route('host.savedate.select-template') }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="template" value="{{ $tpl['id'] }}">
                                        <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold btn-sm py-2">
                                            Select Template
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Interactive Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-dark text-white px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-play-circle text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold mb-0" id="previewModalTitle">Save The Date Preview</h5>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="reloadPreviewFrame()">
                        <i class="bi bi-arrow-clockwise"></i> Replay
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-0 bg-secondary d-flex justify-content-center align-items-center position-relative" style="min-height: 600px; max-height: 85vh; overflow: auto; background-color: #0f172a !important;">
                {{-- Loader --}}
                <div id="previewLoader" class="position-absolute inset-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-white" style="z-index: 10;">
                    <div class="spinner-border text-warning mb-2" role="status"></div>
                    <div class="small text-white-50">Loading animated template...</div>
                </div>

                {{-- Phone / Container Frame --}}
                <div class="preview-frame-container py-3">
                    <iframe id="previewIframe" src="" style="width: 420px; height: 750px; max-height: 80vh; border: none; border-radius: 28px; box-shadow: 0 25px 60px rgba(0,0,0,0.8);"></iframe>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 px-4 py-3 d-flex justify-content-between">
                <div class="small text-muted">
                    <i class="bi bi-info-circle me-1"></i> Tap the seal/envelope inside the preview to test opening animation.
                </div>
                <div class="d-flex gap-2">
                    <form id="selectFromModalForm" action="{{ route('host.savedate.select-template') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="template" id="modalTemplateInput" value="">
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-xs">
                            <i class="bi bi-check-circle-fill me-1"></i> Select This Template
                        </button>
                    </form>
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.06); }
    .border-active-template {
        border: 2.5px solid #10b981 !important;
        box-shadow: 0 0.5rem 1.5rem rgba(16, 185, 129, 0.15) !important;
    }
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.12) !important;
    }
    .phone-mockup-wrap {
        width: 220px;
        height: 380px;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .phone-mockup-wrap:hover {
        transform: scale(1.03);
    }
    .phone-mockup {
        width: 100%;
        height: 100%;
        border-radius: 28px;
        border: 8px solid rgba(255,255,255,0.25);
        overflow: hidden;
        position: relative;
        background: #000;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    .mini-preview-iframe {
        width: 430px;
        height: 800px;
        border: none;
        transform: scale(0.48);
        transform-origin: top left;
        pointer-events: none;
    }
    .mockup-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.2s ease;
        color: #fff;
    }
    .phone-mockup-wrap:hover .mockup-overlay {
        opacity: 1;
    }
</style>

<script>
    document.getElementById('msg_presets').addEventListener('change', function() {
        const box = document.getElementById('message_text');
        if (this.value === 'custom') {
            box.value = '';
        } else if (this.value !== '') {
            box.value = this.value;
        }
    });

    // Cascading Category -> Ceremony Filtering
    const categorySelect = document.getElementById('categorySelect');
    const ceremonySelect = document.getElementById('ceremonySelect');
    const venueSelect = document.getElementById('venueSelect');
    const nameOrderSelect = document.getElementById('nameOrderSelect');
    const initialsBadge = document.getElementById('initialsDisplayBadge');

    const brideInitial = "{{ $bInitial }}";
    const groomInitial = "{{ $gInitial }}";

    function filterCeremoniesByCategory() {
        if (!categorySelect || !ceremonySelect) return;
        const selectedCat = categorySelect.value;
        const options = ceremonySelect.querySelectorAll('option');

        let firstMatchOption = null;

        options.forEach(opt => {
            if (!opt.value) {
                // Default option always stays visible
                opt.style.display = '';
                return;
            }
            const catId = opt.getAttribute('data-category-id');
            if (!selectedCat || selectedCat === '' || catId === selectedCat) {
                opt.style.display = '';
                opt.disabled = false;
                if (!firstMatchOption) {
                    firstMatchOption = opt;
                }
            } else {
                opt.style.display = 'none';
                opt.disabled = true;
            }
        });

        // If currently selected option is hidden, switch to first visible option
        const currentOpt = ceremonySelect.options[ceremonySelect.selectedIndex];
        if (currentOpt && currentOpt.value && currentOpt.style.display === 'none') {
            if (firstMatchOption) {
                firstMatchOption.selected = true;
            } else {
                ceremonySelect.value = '';
            }
        }

        handleCeremonyChange();
    }

    function handleCeremonyChange() {
        if (!ceremonySelect || !venueSelect) return;
        const selectedOpt = ceremonySelect.options[ceremonySelect.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const venueId = selectedOpt.getAttribute('data-venue-id');
            if (venueId && venueSelect.querySelector(`option[value="${venueId}"]`)) {
                venueSelect.value = venueId;
            }
        }
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', filterCeremoniesByCategory);
    }
    if (ceremonySelect) {
        ceremonySelect.addEventListener('change', handleCeremonyChange);
    }
    if (nameOrderSelect && initialsBadge) {
        nameOrderSelect.addEventListener('change', function() {
            if (this.value === 'groom_bride') {
                initialsBadge.innerText = groomInitial + ' & ' + brideInitial;
            } else {
                initialsBadge.innerText = brideInitial + ' & ' + groomInitial;
            }
        });
    }

    // Run initial filter on load
    filterCeremoniesByCategory();

    const previewModalEl = document.getElementById('previewModal');
    const previewModal = new bootstrap.Modal(previewModalEl);
    const previewIframe = document.getElementById('previewIframe');
    const previewLoader = document.getElementById('previewLoader');
    let currentPreviewUrl = '';

    function openPreviewModal(templateId, templateName) {
        document.getElementById('previewModalTitle').innerText = templateName + ' - Live Interactive Preview';
        document.getElementById('modalTemplateInput').value = templateId;

        // Collect current form selections to pass as preview overrides
        const catVal = categorySelect ? categorySelect.value : '';
        const cerVal = ceremonySelect ? ceremonySelect.value : '';
        const venVal = venueSelect ? venueSelect.value : '';
        const orderVal = nameOrderSelect ? nameOrderSelect.value : '';

        previewLoader.style.display = 'flex';
        let url = "{{ route('host.savedate.preview') }}?template=" + encodeURIComponent(templateId);
        if (cerVal) url += '&ceremony_id=' + encodeURIComponent(cerVal);
        if (catVal) url += '&category_id=' + encodeURIComponent(catVal);
        if (venVal) url += '&venue_id=' + encodeURIComponent(venVal);
        if (orderVal) url += '&name_order=' + encodeURIComponent(orderVal);

        currentPreviewUrl = url;
        previewIframe.src = currentPreviewUrl;

        previewIframe.onload = function() {
            previewLoader.style.display = 'none';
        };

        previewModal.show();
    }

    function reloadPreviewFrame() {
        if (currentPreviewUrl) {
            previewLoader.style.display = 'flex';
            previewIframe.src = currentPreviewUrl;
        }
    }
</script>
@endsection
