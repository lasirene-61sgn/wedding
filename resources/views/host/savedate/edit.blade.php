@extends('layouts.host')

@section('content')
@php
    $invitation = $savedate->invitation ?? \App\Models\Invitation::where('host_id', $savedate->host_id)->first();

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

    $isGroomFirst = ($savedate->name_order ?? 'bride_groom') === 'groom_bride';
    $activeInitials = $isGroomFirst ? ($gInitial . ' & ' . $bInitial) : ($bInitial . ' & ' . $gInitial);
@endphp

<div class="container py-4">
    <div class="card shadow-sm mx-auto border-0 rounded-4 overflow-hidden" style="max-width: 720px;">
        <div class="card-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold"><i class="bi bi-heart me-2 text-danger"></i>Edit Save The Date Announcement</h5>
            <span class="badge bg-warning text-dark"><i class="bi bi-fonts me-1"></i>Initials: {{ $activeInitials }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('host.savedate.update', $savedate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="invitation_id" value="{{ $savedate->invitation_id }}">

                {{-- Template Selector --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">Save The Date Animated Template</label>
                    <select name="template" class="form-select rounded-3">
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl['id'] }}" {{ ($savedate->template ?? 'savethedate/invitation.html') === $tpl['id'] ? 'selected' : '' }}>
                                {{ $tpl['name'] }} ({{ $tpl['theme'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Couple Name Display Order & First Word --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label class="form-label fw-bold text-dark small text-uppercase">Couple Name Order</label>
                        <select name="name_order" id="editNameOrderSelect" class="form-select rounded-3">
                            <option value="bride_groom" {{ ($savedate->name_order ?? 'bride_groom') === 'bride_groom' ? 'selected' : '' }}>
                                Bride First ({{ $bFirst }} &amp; {{ $gFirst }})
                            </option>
                            <option value="groom_bride" {{ ($savedate->name_order ?? '') === 'groom_bride' ? 'selected' : '' }}>
                                Groom First ({{ $gFirst }} &amp; {{ $bFirst }})
                            </option>
                        </select>
                    </div>
                    <div class="col-md-5 d-flex align-items-end">
                        <div class="form-check pb-2">
                            <input class="form-check-input" type="checkbox" name="use_first_name" value="1" id="editUseFirstName" {{ ($savedate->use_first_name ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="editUseFirstName">
                                Use <strong>First Words only</strong>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 1. Category Selection --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">
                        <i class="bi bi-tag-fill text-primary me-1"></i> 1. Wedding Category
                    </label>
                    <select name="category_id" id="editCategorySelect" class="form-select rounded-3">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($selectedCategoryId == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Select category first to filter ceremonies below.</small>
                </div>

                {{-- 2. Ceremony Selection --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">
                        <i class="bi bi-stars text-warning me-1"></i> 2. Ceremony For Save The Date
                    </label>
                    <select name="ceremony_id" id="editCeremonySelect" class="form-select rounded-3">
                        <option value="">-- Main Wedding Ceremony (Default) --</option>
                        @foreach($ceremonies as $c)
                            <option value="{{ $c->id }}" 
                                    data-category-id="{{ $c->category_id }}" 
                                    data-venue-id="{{ $c->venue_id }}"
                                    {{ ($savedate->ceremony_id == $c->id) ? 'selected' : '' }}>
                                {{ $c->ceramony_name }} @if($c->ceramony_date) ({{ date('d M Y', strtotime($c->ceramony_date)) }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Whichever ceremony you select, that ceremony name, date and time will appear on the card.</small>
                </div>

                {{-- 3. Venue Selection --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> 3. Wedding Venue
                    </label>
                    <select name="venue_id" id="editVenueSelect" class="form-select rounded-3">
                        <option value="">-- Auto (Selected Ceremony Venue) --</option>
                        @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ ($savedate->venue_id == $v->id) ? 'selected' : '' }}>
                                {{ $v->venue_name }} @if(!empty($v->district)) ({{ $v->district }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">The venue name and address will show on the announcement.</small>
                </div>

                {{-- Message Presets & Text --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">Announcement Message</label>
                    <select id="template_selector" class="form-select mb-2 rounded-3 form-select-sm">
                        <option value="">-- Choose a message template to insert --</option>
                        <option value="We can't wait to celebrate our special day with you! Please save the date.">Warm &amp; Classic</option>
                        <option value="Save the date! We're getting married and we want you there to celebrate with us.">Modern &amp; Fun</option>
                        <option value="A beautiful journey is about to begin. Please mark your calendars for our wedding.">Elegant &amp; Formal</option>
                        <option value="custom">-- Clear message --</option>
                    </select>
                    
                    <textarea name="message" id="message_box" class="form-control rounded-3 @error('message') is-invalid @enderror" rows="3" placeholder="Enter message here...">{{ old('message', $savedate->message) }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Image Upload --}}
                <div class="mb-4">
                    @if(!empty($savedate->image))
                        <label class="form-label d-block fw-bold text-dark small text-uppercase">Current Image</label>
                        <img src="{{ asset('storage/' . $savedate->image) }}" class="img-thumbnail mb-3 shadow-sm rounded-3" style="max-height: 140px;">
                    @endif
                    
                    <label class="form-label d-block fw-bold text-dark small text-uppercase">Announcement Photo (Optional)</label>
                    <input type="file" name="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Upload an optional photo to feature on your announcement.</small>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold shadow-xs">
                        <i class="bi bi-check-circle-fill me-1"></i> Update Details
                    </button>
                    <a href="{{ route('host.savedate.index') }}" class="btn btn-light rounded-pill">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('template_selector').addEventListener('change', function() {
        const messageBox = document.getElementById('message_box');
        if (this.value === 'custom') {
            messageBox.value = '';
        } else if (this.value !== '') {
            messageBox.value = this.value;
        }
    });

    // Cascading Category -> Ceremony in Edit Form
    const editCat = document.getElementById('editCategorySelect');
    const editCer = document.getElementById('editCeremonySelect');
    const editVen = document.getElementById('editVenueSelect');

    function filterEditCeremonies() {
        if (!editCat || !editCer) return;
        const selectedCat = editCat.value;
        const options = editCer.querySelectorAll('option');
        let firstMatch = null;

        options.forEach(opt => {
            if (!opt.value) {
                opt.style.display = '';
                return;
            }
            const catId = opt.getAttribute('data-category-id');
            if (!selectedCat || selectedCat === '' || catId === selectedCat) {
                opt.style.display = '';
                opt.disabled = false;
                if (!firstMatch) firstMatch = opt;
            } else {
                opt.style.display = 'none';
                opt.disabled = true;
            }
        });

        const current = editCer.options[editCer.selectedIndex];
        if (current && current.value && current.style.display === 'none') {
            if (firstMatch) {
                firstMatch.selected = true;
            } else {
                editCer.value = '';
            }
        }

        handleEditCeremonyChange();
    }

    function handleEditCeremonyChange() {
        if (!editCer || !editVen) return;
        const selectedOpt = editCer.options[editCer.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const venueId = selectedOpt.getAttribute('data-venue-id');
            if (venueId && editVen.querySelector(`option[value="${venueId}"]`)) {
                editVen.value = venueId;
            }
        }
    }

    if (editCat) editCat.addEventListener('change', filterEditCeremonies);
    if (editCer) editCer.addEventListener('change', handleEditCeremonyChange);

    filterEditCeremonies();
</script>
@endsection
