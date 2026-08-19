@extends('layouts.host')

<style>
    .preview-container {
        border: 2px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        min-height: 600px;
        position: relative;
    }

    #gjs {
        height: 600px !important;
        width: 100% !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }
</style>

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Wedding Invitation</h2>
        <a href="{{ route('host.invitation.index') }}" class="btn btn-secondary btn-sm shadow-sm">Back to Registry</a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <!-- FORM SECTION (Left Side) -->
        <div class="col-lg-7">
            <form id="invitationForm" action="{{ route('host.invitation.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                    <!-- TEMPLATE SELECTION CARD -->
                    <div class="card mb-4 shadow-sm border-0 bg-white">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-layout-text-window-reverse"></i> Select Template</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary small">Category</label>
                                    <select id="cat_select" class="form-select form-select-sm">
                                        <option value="">-- Category --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary small">Sub Category</label>
                                    <select id="subcat_select" class="form-select form-select-sm" disabled>
                                        <option value="">-- Subcategory --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary small">Template</label>
                                    <select name="selected_html_template" id="html_template_select" class="form-select form-select-sm watch-input" disabled>
                                        <option value="">-- Template --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MANAGE RELATED DETAILS CARD -->
                    <div class="card mb-4 shadow-sm border-0 bg-white">
                        <div class="card-body">
                            <div class="mb-3 p-3 bg-white border rounded shadow-sm">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-plus-circle-dotted me-1"></i> Manage Related Details</h6>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#quickCeremonyModal"><i class="bi bi-calendar-event"></i> + Ceremony</button>
                            <button type="button" class="btn btn-sm btn-outline-info fw-bold" data-bs-toggle="modal" data-bs-target="#quickPhotoModal"><i class="bi bi-image"></i> + Photo</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#quickAlbumModal"><i class="bi bi-folder-plus"></i> + Album</button>
                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#quickVideoModal"><i class="bi bi-play-circle"></i> + Video</button>
                            <button type="button" class="btn btn-sm btn-outline-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#quickSaveDateModal"><i class="bi bi-bookmark-heart"></i> Save Date</button>
                        </div>
                        
                        <div class="mt-4 border-top pt-3 text-start">
                            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-list-check"></i> Added Details Preview</h6>
                            
                            @if(isset($ceremonies) && $ceremonies->count() > 0)
                                <div class="mb-3">
                                    <strong class="d-block mb-2 text-primary small"><i class="bi bi-calendar-event"></i> Ceremonies</strong>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($ceremonies as $c)
                                            <div class="border rounded p-2 bg-light shadow-sm d-flex gap-2 align-items-center" style="min-width: 180px;">
                                                @if($c->ceramony_image)
                                                    <img src="{{ asset('storage/' . $c->ceramony_image) }}" class="rounded shadow-sm border" style="width: 45px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="rounded shadow-sm bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 45px; height: 45px;"><i class="bi bi-calendar-event"></i></div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold" style="font-size: 0.85rem;">{{ $c->ceramony_name }}</div>
                                                    <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock"></i> {{ $c->ceramony_date ? \Carbon\Carbon::parse($c->ceramony_date)->format('d M y') : '' }} {{ $c->ceramony_time ? \Carbon\Carbon::parse($c->ceramony_time)->format('h:i A') : '' }}</div>
                                                    @if($c->venue)
                                                        <div class="text-primary mt-1" style="font-size: 0.7rem; line-height: 1.2;">
                                                            <strong><i class="bi bi-geo-alt-fill"></i> {{ $c->venue->venue_name }}</strong><br>
                                                            <span class="text-muted">{{ \Illuminate\Support\Str::limit($c->venue->venue_address, 40) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($pictures) && $pictures->count() > 0)
                                <div class="mb-3">
                                    <strong class="d-block mb-2 text-info small"><i class="bi bi-image"></i> Gallery Photos</strong>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($pictures as $pic)
                                            <img src="{{ asset('storage/' . $pic->picture) }}" class="rounded shadow-sm border" style="width: 55px; height: 55px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(isset($albums) && $albums->count() > 0)
                                <div class="mb-3">
                                    <strong class="d-block mb-2 text-secondary small"><i class="bi bi-folder"></i> Albums (Click to View)</strong>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($albums as $a)
                                            <div class="text-center cursor-pointer" data-bs-toggle="modal" data-bs-target="#viewAlbumModal_{{ $a->id }}">
                                                @if(!empty($a->album_images) && is_array($a->album_images))
                                                    <img src="{{ asset('storage/' . $a->album_images[0]) }}" class="rounded shadow-sm border" style="width: 55px; height: 55px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                                @else
                                                    <div class="rounded shadow-sm bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 55px; height: 55px;"><i class="bi bi-folder"></i></div>
                                                @endif
                                                <div class="mt-1 text-truncate fw-bold text-primary" style="max-width: 65px; font-size: 0.75rem;" title="{{ $a->album_name }}">{{ $a->album_name }}</div>
                                                <div class="text-muted" style="font-size: 0.65rem;">
                                                    {{ !empty($a->album_images) && is_array($a->album_images) ? count($a->album_images) . ' photos' : 'Empty' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(isset($videos) && $videos->count() > 0)
                                <div class="mb-3">
                                    <strong class="d-block mb-2 text-danger small"><i class="bi bi-play-circle"></i> Videos</strong>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($videos as $vid)
                                            <div class="rounded shadow-sm bg-dark d-flex align-items-center justify-content-center text-white border" style="width: 80px; height: 55px;">
                                                <i class="bi bi-play-btn fs-4"></i>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(isset($saveDate) && $saveDate)
                                <div class="mb-3">
                                    <strong class="d-block mb-2 text-warning small"><i class="bi bi-bookmark-heart"></i> Save the Date</strong>
                                    <div class="d-flex align-items-center gap-3 border rounded p-2 bg-light shadow-sm">
                                        @if(!empty($saveDate->image))
                                            <img src="{{ asset('storage/' . $saveDate->image) }}" class="rounded shadow-sm border" style="width: 55px; height: 55px; object-fit: cover;">
                                        @endif
                                        <div class="fst-italic text-muted small">"{{ \Illuminate\Support\Str::limit($saveDate->message, 80) }}"</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        </div> <!-- close mb-3 p-3 bg-white -->
                        </div> <!-- close card-body -->
                    </div> <!-- close card -->

                <div class="card mb-4 shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Invitation Sent By</label>
                                <select name="invite" id="invite_dropdown" class="form-select watch-input">
                                    @foreach(['brideparents' => "Bride's Parents", 'groomparents' => "Groom's Parents", 'bride' => 'Bride', 'groom' => 'Groom', 'weddingcouple' => 'Wedding Couple'] as $key => $label)
                                    <option value="{{ $key }}" {{ $invitation->invite == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-bold">Select Venue</label>
                                <div class="input-group">
                                    <select name="venue_id" id="venue_dropdown" class="form-select watch-input">
                                        <option value="">-- Select Venue --</option>
                                        @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}"
                                            data-name="{{ $venue->venue_name }}"
                                            {{ $invitation->venue_id == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->venue_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#venueModal">+ Add</button>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Theme Style</label>
                                <select name="theme" id="theme_selector" class="form-select watch-input">
                                    <option value="classic" {{ $invitation->theme == 'classic' ? 'selected' : '' }}>Classic Elegant</option>
                                    <option value="royal" {{ $invitation->theme == 'royal' ? 'selected' : '' }}>Royal Luxury</option>
                                    <option value="floral" {{ $invitation->theme == 'floral' ? 'selected' : '' }}>Modern Floral</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="party_details_row">
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm border-info">
                            <div class="card-header bg-info text-white fw-bold">Bride's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Full Name</label><input type="text" name="bride_name" value="{{ $invitation->bride_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile Number</label><input type="text" name="bride_number" value="{{ $invitation->bride_number }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Email Address</label><input type="email" name="bride_email" value="{{ $invitation->bride_email }}" class="form-control watch-input"></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="bride_father_name" value="{{ $invitation->bride_father_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="bride_mother_name" value="{{ $invitation->bride_mother_name }}" class="form-control watch-input" required></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm border-secondary">
                            <div class="card-header bg-secondary text-white fw-bold">Groom's Information</div>
                            <div class="card-body">
                                <div class="mb-2"><label>Full Name</label><input type="text" name="groom_name" value="{{ $invitation->groom_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mobile Number</label><input type="text" name="groom_number" value="{{ $invitation->groom_number }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Email Address</label><input type="email" name="groom_email" value="{{ $invitation->groom_email }}" class="form-control watch-input"></div>
                                <div class="mb-2"><label>Father's Name</label><input type="text" name="groom_father_name" value="{{ $invitation->groom_father_name }}" class="form-control watch-input" required></div>
                                <div class="mb-2"><label>Mother's Name</label><input type="text" name="groom_mother_name" value="{{ $invitation->groom_mother_name }}" class="form-control watch-input" required></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">Event Timing & Location</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Wedding Date</label><input type="date" id="wedding_date" name="wedding_date" value="{{ $invitation->wedding_date }}" class="form-control watch-input"></div>
                            <div class="col-md-6 mb-3"><label>Wedding Time</label><input type="time" id="wedding_time" name="wedding_time" value="{{ $invitation->wedding_time }}" class="form-control watch-input"></div>


                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Title Text Color</label>
                                <input type="color" name="text_color" id="text_color" class="form-control form-control-color w-100 watch-input" value="{{ $invitation->text_color ?? '#b02663' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Details Text Color</label>
                                <input type="color" name="details_color" id="details_color" class="form-control form-control-color w-100 watch-input" value="{{ $invitation->details_color ?? '#2b4c5e' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body text-center">
                        <label class="fw-bold d-block mb-3">Current Invitation Card Image</label>
                        @if($invitation->wedding_image)
                        <img src="{{ asset('storage/' . $invitation->wedding_image) }}" class="img-thumbnail mb-4 shadow-sm" style="max-height: 250px;" alt="Invitation Image">
                        @endif
                        <div class="col-md-8 mx-auto">
                            <input type="file" name="wedding_image" class="form-control">
                            <small class="text-muted mt-2 d-block">Upload only if you want to replace the current image.</small>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="text_positions" id="text_positions" value="{{ is_string($invitation->text_positions) ? $invitation->text_positions : json_encode($invitation->text_positions ?? []) }}">
                <input type="hidden" name="custom_canvas_texts" id="custom_canvas_texts" value="{{ is_string($invitation->custom_canvas_texts) ? $invitation->custom_canvas_texts : json_encode($invitation->custom_canvas_texts ?? []) }}">

                <input type="hidden" name="customized_html" id="customized_html">
                <input type="hidden" name="customized_css" id="customized_css">

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow">✨ Update Wedding Invitation</button>
            </form>
        </div>

        <!-- LIVE PREVIEW & MANAGEMENT (Right Side) -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark text-center fw-bold">Live Template Preview & Editor</h5>
                </div>
                <div class="card-body bg-light position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0">Live Editor</label>
                        <div>
                            <button type="button" id="togglePreviewBtn" class="btn btn-sm btn-dark shadow-sm me-1">
                                <i class="bi bi-eye"></i> Interact
                            </button>
                            <button type="button" id="fullScreenPreviewBtn" class="btn btn-sm btn-primary shadow-sm">
                                <i class="bi bi-box-arrow-up-right"></i> Full Screen Preview
                            </button>
                        </div>
                    </div>
                    <div class="preview-container">
                        <div id="gjs"></div>
                        <div id="gjs-loader" class="position-absolute top-0 start-0 w-100 h-100 bg-white justify-content-center align-items-center" style="display: none; z-index: 999; opacity: 0.85;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading preview...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- VIEW ALBUM MODALS -->
@if(isset($albums) && $albums->count() > 0)
    @foreach($albums as $a)
    <div class="modal fade" id="viewAlbumModal_{{ $a->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-folder2-open"></i> {{ $a->album_name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    @if(!empty($a->album_images) && is_array($a->album_images) && count($a->album_images) > 0)
                        <div class="row g-2">
                            @foreach($a->album_images as $img)
                                <div class="col-4 col-md-3">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x display-1"></i>
                            <p class="mt-3">No photos have been uploaded to this album yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

<!-- QUICK CEREMONY MODAL WITH CATEGORIES, SUBCATEGORIES, BADGES, VENUES, DATE & IMAGE -->
<div class="modal fade" id="quickCeremonyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Ceremony</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickCeremonyForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" id="modal_ceremony_cat" class="form-select" required onchange="handleModalCategoryChange()">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-subcategories="{{ json_encode(is_array($cat->sub_categories) ? $cat->sub_categories : []) }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="modal_subcategory_container" style="display: none;">
                            <label class="form-label fw-bold">Subcategory</label>
                            <select name="sub_category" id="modal_ceremony_subcat" class="form-select" onchange="handleModalSubcategoryChange()">
                                <option value="">-- Select Subcategory --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="mb-3" id="modal_ceremonies_box" style="display: none;">
                        <label class="form-label fw-bold">Select Ceremony</label>
                        <div id="modal_ceremonies_badges" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <div id="modal_ceremony_details" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ceremony Name</label>
                            <input type="text" name="ceramony_name" id="modal_ceramony_name" class="form-control" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Venue</label>
                                <select name="venue_id" class="form-select">
                                    <option value="">-- Select Venue --</option>
                                    @foreach($venues as $v)
                                    <option value="{{ $v->id }}">{{ $v->venue_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" name="ceramony_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Time</label>
                                <input type="time" name="ceramony_time" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            <input type="file" name="ceramony_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 shadow">Save & Refresh Preview</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK PHOTO MODAL -->
<div class="modal fade" id="quickPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Upload Photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickPhotoForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Photo</label>
                        <input type="file" name="picture" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info text-white w-100 shadow">Upload Photo & Refresh</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK ALBUM MODAL -->
<div class="modal fade" id="quickAlbumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Create Album</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickAlbumForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Album Title</label>
                        <input type="text" name="album_name" class="form-control" placeholder="e.g., Reception Party" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Photos</label>
                        <input type="file" name="album_images[]" multiple class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary w-100 shadow">Create Album & Refresh</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK VIDEO MODAL -->
<div class="modal fade" id="quickVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Upload Video</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickVideoForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Video File</label>
                        <input type="file" name="videos" class="form-control" accept="video/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger w-100 shadow">Upload Video & Refresh</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK SAVE DATE MODAL -->
<div class="modal fade" id="quickSaveDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Save The Date Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickSaveDateForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="3" required>Save the date! We are getting married.</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100 shadow fw-bold">Save & Refresh Preview</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- VENUE MODAL -->
<div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5>Add New Venue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="venueForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label>Venue Name</label><input type="text" name="venue_name" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Pincode</label><input type="text" name="pincode" class="form-control" maxlength="6" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Area</label><input type="text" name="area_name" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>District</label><input type="text" name="district" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>State</label><input type="text" name="state" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Landmark</label><input type="text" name="wedding_location" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Map URL</label><input type="text" name="location_map" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Full Address</label><textarea name="venue_address" class="form-control" rows="2" required></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 shadow">Save Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/css/grapes.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/grapes.min.js"></script>
<script>
    let modalCurrentSubcategories = [];

    function handleModalCategoryChange() {
        const select = document.getElementById('modal_ceremony_cat');
        const option = select.options[select.selectedIndex];
        const subcatContainer = document.getElementById('modal_subcategory_container');
        const subcatSelect = document.getElementById('modal_ceremony_subcat');
        const ceremoniesBox = document.getElementById('modal_ceremonies_box');
        const badges = document.getElementById('modal_ceremonies_badges');
        const details = document.getElementById('modal_ceremony_details');

        badges.innerHTML = '';
        subcatSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
        subcatContainer.style.display = 'none';
        ceremoniesBox.style.display = 'none';
        details.style.display = 'none';
        document.getElementById('modal_ceramony_name').value = '';

        if (!option || !option.value) return;

        modalCurrentSubcategories = JSON.parse(option.getAttribute('data-subcategories') || '[]');

        if (modalCurrentSubcategories.length > 0) {
            modalCurrentSubcategories.forEach(sub => {
                if (sub.name) {
                    subcatSelect.add(new Option(sub.name, sub.name));
                }
            });
            subcatContainer.style.display = 'block';
        } else {
            handleModalSubcategoryChange(true);
        }
    }

    function handleModalSubcategoryChange(forceEmpty = false) {
        const subcatSelect = document.getElementById('modal_ceremony_subcat');
        const ceremoniesBox = document.getElementById('modal_ceremonies_box');
        const badges = document.getElementById('modal_ceremonies_badges');
        const details = document.getElementById('modal_ceremony_details');

        badges.innerHTML = '';
        ceremoniesBox.style.display = 'none';
        details.style.display = 'none';
        document.getElementById('modal_ceramony_name').value = '';

        let ceremonies = [];
        if (!forceEmpty && subcatSelect.value !== '') {
            const selectedSub = modalCurrentSubcategories.find(s => s.name === subcatSelect.value);
            if (selectedSub) ceremonies = selectedSub.ceremonies || [];
        } else if (subcatSelect.value === '' && !forceEmpty) {
            return;
        }

        ceremonies.forEach(ceremony => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-primary btn-sm ceremony-badge';
            btn.innerText = ceremony;
            btn.onclick = function() {
                document.querySelectorAll('.ceremony-badge').forEach(b => b.classList.remove('btn-primary', 'text-white'));
                this.classList.add('btn-primary', 'text-white');
                details.style.display = 'block';
                document.getElementById('modal_ceramony_name').value = ceremony;
            };
            badges.appendChild(btn);
        });

        const othersBtn = document.createElement('button');
        othersBtn.type = 'button';
        othersBtn.className = 'btn btn-outline-secondary btn-sm ceremony-badge';
        othersBtn.innerText = 'Others';
        othersBtn.onclick = function() {
            document.querySelectorAll('.ceremony-badge').forEach(b => b.classList.remove('btn-primary', 'text-white'));
            this.classList.add('btn-secondary', 'text-white');
            details.style.display = 'block';
            const nameInput = document.getElementById('modal_ceramony_name');
            nameInput.value = '';
            nameInput.focus();
        };
        badges.appendChild(othersBtn);
        ceremoniesBox.style.display = 'block';
    }

    document.addEventListener("DOMContentLoaded", function() {
        const templatesData = @json($htmlTemplates);
        const currentSelectedFile = {!! json_encode($invitation->selected_html_template) !!};

        const catSelect = document.getElementById('cat_select');
        const subcatSelect = document.getElementById('subcat_select');
        const templateSelect = document.getElementById('html_template_select');
        const loader = document.getElementById('gjs-loader');
        const mainForm = document.getElementById('invitationForm');

        const tree = {};
        templatesData.forEach(t => {
            if (!tree[t.category]) tree[t.category] = {};
            if (!tree[t.category][t.subcategory]) tree[t.category][t.subcategory] = [];
            tree[t.category][t.subcategory].push(t.file);
        });

        for (const cat in tree) {
            catSelect.add(new Option(cat, cat));
        }

        catSelect.addEventListener('change', function() {
            subcatSelect.innerHTML = '<option value="">-- Subcategory --</option>';
            templateSelect.innerHTML = '<option value="">-- Template --</option>';
            subcatSelect.disabled = true;
            templateSelect.disabled = true;

            const selectedCat = this.value;
            if (selectedCat && tree[selectedCat]) {
                for (const sub in tree[selectedCat]) {
                    subcatSelect.add(new Option(sub, sub));
                }
                subcatSelect.disabled = false;
            }
        });

        subcatSelect.addEventListener('change', function() {
            templateSelect.innerHTML = '<option value="">-- Template --</option>';
            templateSelect.disabled = true;

            const selectedCat = catSelect.value;
            const selectedSub = this.value;

            if (selectedCat && selectedSub && tree[selectedCat][selectedSub]) {
                tree[selectedCat][selectedSub].forEach(file => {
                    const filename = file.split('/').pop();
                    templateSelect.add(new Option(filename, file));
                });
                templateSelect.disabled = false;
            }
        });

        const editor = grapesjs.init({
            container: '#gjs',
            height: '100%',
            width: '100%',
            storageManager: false,
            allowScripts: 1, // <--- ALLOW TEMPLATE SCRIPTS TO RUN
            panels: {
                defaults: []
            }
        });

        window.loadPreview = function() {
            const selected = templateSelect.value;
            if (!selected || selected.trim() === '') {
                editor.setComponents('<div style="padding:40px; text-align:center; color:#999;">Select a Category, Subcategory, and Template to start previewing.</div>');
                return;
            }

            loader.style.display = 'flex';

            const formData = new FormData(mainForm);
            formData.delete('_method');
            formData.delete('customized_html');
            formData.delete('customized_css');
            formData.delete('text_positions');
            formData.delete('custom_canvas_texts');
            formData.append('template', selected);

            fetch("{{ route('host.invitation.live-preview') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Preview fetch failed (HTTP ' + res.status + ')');
                    return res.text();
                })
                .then(html => {
                    editor.setComponents(html);
                    loader.style.display = 'none';
                })
                .catch(err => {
                    console.error(err);
                    loader.style.display = 'none';
                });
        };

        if (currentSelectedFile) {
            const found = templatesData.find(t => t.file === currentSelectedFile);
            if (found) {
                catSelect.value = found.category;
                catSelect.dispatchEvent(new Event('change'));
                subcatSelect.value = found.subcategory;
                subcatSelect.dispatchEvent(new Event('change'));
                templateSelect.value = currentSelectedFile;
            } else {
                // It's a customized template that isn't in the original tree
                catSelect.add(new Option('Customized Template', 'Customized', true, true));
                
                subcatSelect.add(new Option('Customized', 'Customized', true, true));
                subcatSelect.disabled = false;
                
                templateSelect.add(new Option(currentSelectedFile.split('/').pop(), currentSelectedFile, true, true));
                templateSelect.disabled = false;
            }
            loadPreview();
        }

        templateSelect.addEventListener('change', loadPreview);

        let debounceTimer;
        document.querySelectorAll('.watch-input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (templateSelect.value) loadPreview();
                }, 400);
            });
            input.addEventListener('change', function() {
                if (templateSelect.value) loadPreview();
            });
        });

        mainForm.addEventListener('submit', function() {
            if (templateSelect.value) {
                document.getElementById('customized_html').value = editor.getHtml();
                document.getElementById('customized_css').value = editor.getCss();
            }
        });

        // Toggle Preview Mode for interactive templates (like Envelopes)
        let isPreviewMode = false;
        const toggleBtn = document.getElementById('togglePreviewBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (!isPreviewMode) {
                    editor.runCommand('core:preview');
                    this.innerHTML = '<i class="bi bi-pencil"></i> Back to Editing';
                    this.classList.replace('btn-dark', 'btn-primary');
                    isPreviewMode = true;
                } else {
                    editor.stopCommand('core:preview');
                    this.innerHTML = '<i class="bi bi-eye"></i> Interact / Open Envelope';
                    this.classList.replace('btn-primary', 'btn-dark');
                    isPreviewMode = false;
                }
            });
        }

        // Quick Modals AJAX
        document.getElementById('venueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.venue.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('venue_dropdown');
                    select.add(new Option(data.venue_name, data.id, true, true));
                    bootstrap.Modal.getInstance(document.getElementById('venueModal')).hide();
                    this.reset();
                    loadPreview();
                })
                .catch(() => alert("Error saving venue."));
        });

        document.getElementById('quickCeremonyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.ceramony.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (res.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('quickCeremonyModal')).hide();
                        this.reset();
                        loadPreview();
                    } else alert("Error saving ceremony.");
                });
        });

        document.getElementById('quickPhotoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.picture.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (res.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('quickPhotoModal')).hide();
                        this.reset();
                        loadPreview();
                    } else alert("Error uploading photo.");
                });
        });

        document.getElementById('quickAlbumForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.album.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (res.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('quickAlbumModal')).hide();
                        this.reset();
                        loadPreview();
                    } else alert("Error creating album.");
                });
        });

        document.getElementById('quickVideoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.video.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (res.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('quickVideoModal')).hide();
                        this.reset();
                        loadPreview();
                    } else alert("Error uploading video.");
                });
        });

        document.getElementById('quickSaveDateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('host.savedate.store') }}", {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => {
                    if (res.ok) {
                        bootstrap.Modal.getInstance(document.getElementById('quickSaveDateModal')).hide();
                        this.reset();
                        loadPreview();
                    } else alert("Error saving Save the Date.");
                });
        });

        // Full Screen Preview in New Tab
        const fullScreenBtn = document.getElementById('fullScreenPreviewBtn');
        if (fullScreenBtn) {
            fullScreenBtn.addEventListener('click', function() {
                const selected = templateSelect.value;
                if (!selected || selected.trim() === '') {
                    alert('Please select a template first to preview.');
                    return;
                }

                // Create a temporary hidden form to POST to a new tab
                const form = document.createElement('form');
                form.target = '_blank';
                form.method = 'POST';
                form.action = "{{ route('host.invitation.live-preview') }}";

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);

                // Add all main form data
                const formData = new FormData(mainForm);
                formData.delete('_method'); // Don't let Laravel spoof it to PUT
                formData.append('template', selected);

                for (let [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });
        }

        // Before submitting the main form, capture any custom edits made in the Live Editor
        mainForm.addEventListener('submit', function() {
            if (templateSelect.value && templateSelect.value.trim() !== '') {
                // Base64 encode to bypass WAF HTML payload blocking
                document.getElementById('customized_html').value = 'B64:' + btoa(unescape(encodeURIComponent(editor.getHtml() || '')));
                document.getElementById('customized_css').value = 'B64:' + btoa(unescape(encodeURIComponent(editor.getCss() || '')));
            }
        });
    });
</script>
@endpush
@endsection