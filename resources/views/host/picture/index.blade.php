@extends('layouts.host')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Media Gallery</h3>
            <p class="text-secondary small mb-0">Organize and manage your wedding memories</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill" id="galleryTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold" id="pic-tab" data-bs-toggle="tab" data-bs-target="#pictures" type="button">
                        <i class="bi bi-image me-2"></i>Photos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold" id="album-tab" data-bs-toggle="tab" data-bs-target="#albums" type="button">
                        <i class="bi bi-folder me-2"></i>Albums
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold" id="video-tab" data-bs-toggle="tab" data-bs-target="#videos" type="button">
                        <i class="bi bi-play-circle me-2"></i>Videos
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4">
            <div class="tab-content" id="galleryTabContent">
                
                <!-- PHOTOS TAB -->
                <div class="tab-pane fade show active" id="pictures" role="tabpanel">
                    <div class="row g-4 mb-4">
                        <div class="col-lg-4">
                            <div class="p-4 bg-light rounded-4 border border-dashed">
                                <h6 class="fw-bold mb-3"><i class="bi bi-cloud-upload me-2 text-primary"></i>Upload Photos</h6>
                                <form action="{{ route('host.picture.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="picture" class="form-control rounded-3" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                                        Upload Photo
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-3">
                                @forelse($pictures as $pic)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="gallery-item position-relative rounded-3 overflow-hidden shadow-sm">
                                            <img src="{{ asset('storage/' . $pic->picture) }}" class="w-100 h-100" style="object-fit: cover; aspect-ratio: 1/1;" alt="Gallery">
                                            <div class="gallery-overlay d-flex align-items-center justify-content-center">
                                                <form action="{{ route('host.picture.destroy', $pic->id) }}" method="POST" onsubmit="return confirm('Delete this photo?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm rounded-circle shadow">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-image text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No photos uploaded yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALBUMS TAB -->
                <div class="tab-pane fade" id="albums" role="tabpanel">
                    <div class="row g-4 mb-4">
                        <div class="col-lg-4">
                            <div class="p-4 bg-light rounded-4 border border-dashed">
                                <h6 class="fw-bold mb-3"><i class="bi bi-folder-plus me-2 text-primary"></i>Create Album</h6>
                                <form action="{{ route('host.album.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="small fw-bold text-secondary mb-1">Album Title</label>
                                        <input type="text" name="album_name" placeholder="e.g., Engagement Party" class="form-control rounded-3" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small fw-bold text-secondary mb-1">Select Photos</label>
                                        <input type="file" name="album_images[]" multiple class="form-control rounded-3" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                                        Create Album
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-4">
                                @forelse($albums as $album)
                                    <div class="col-sm-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden album-card h-100">
                                            <div class="album-cover position-relative" style="height: 180px;">
                                                @if($album->album_images && count($album->album_images) > 0)
                                                    <img src="{{ asset('storage/' . $album->album_images[0]) }}" class="w-100 h-100" style="object-fit: cover;" alt="Cover">
                                                @else
                                                    <div class="bg-secondary-subtle w-100 h-100 d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-folder text-secondary opacity-25 fs-1"></i>
                                                    </div>
                                                @endif
                                                <div class="album-badge">
                                                    {{ count($album->album_images ?? []) }} Photos
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-3">{{ $album->album_name }}</h6>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-link btn-sm text-decoration-none fw-bold p-0" onclick="toggleAlbum({{ $album->id }})">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </button>
                                                    <form action="{{ route('host.album.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Delete this album?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-link btn-sm text-danger text-decoration-none fw-bold p-0">
                                                            <i class="bi bi-trash me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-folder text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No albums created yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VIDEOS TAB -->
                <div class="tab-pane fade" id="videos" role="tabpanel">
                    <div class="row g-4 mb-4">
                        <div class="col-lg-4">
                            <div class="p-4 bg-light rounded-4 border border-dashed">
                                <h6 class="fw-bold mb-3"><i class="bi bi-play-btn me-2 text-primary"></i>Upload Videos</h6>
                                <form action="{{ route('host.video.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="videos" accept="video/*" class="form-control rounded-3" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                                        Upload Video
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-3">
                                @forelse($videos as $vid)
                                    <div class="col-sm-6">
                                        <div class="video-item rounded-3 overflow-hidden shadow-sm position-relative">
                                            <video class="w-100" style="aspect-ratio: 16/9; object-fit: cover;">
                                                <source src="{{ asset('storage/' . $vid->videos) }}" type="video/mp4">
                                            </video>
                                            <div class="video-overlay d-flex align-items-center justify-content-center">
                                                <form action="{{ route('host.video.destroy', $vid->id) }}" method="POST" onsubmit="return confirm('Delete this video?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm rounded-circle shadow">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-play-circle text-muted opacity-25" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No videos uploaded yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .gallery-item { cursor: pointer; height: 100%; position: relative; }
    .gallery-overlay, .video-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: all 0.3s;
    }
    .gallery-item:hover .gallery-overlay, .video-item:hover .video-overlay { opacity: 1; }
    .album-badge {
        position: absolute;
        bottom: 10px; right: 10px;
        background: rgba(255,255,255,0.9);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    .rounded-4 { border-radius: 1rem !important; }
    .nav-pills .nav-link { color: var(--secondary-color); transition: all 0.2s; }
    .nav-pills .nav-link.active { background: var(--accent-color); color: #fff; }
</style>

<script>
    function toggleAlbum(id) {
        // Implement album viewer logic here if needed
        console.log('Viewing album:', id);
    }
</script>


<script>
    // Toggle Album Images on Title Click
    function toggleAlbum(albumId) {
        const albumCard = document.getElementById(`album-${albumId}`);
        const header = albumCard.querySelector('.album-header');
        albumCard.classList.toggle('active');
        header.classList.toggle('active');
    }

    // Persist active tab
    document.addEventListener("DOMContentLoaded", function() {
        let activeTab = localStorage.getItem('hostActiveTab');
        if (activeTab) {
            let trigger = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (trigger) new bootstrap.Tab(trigger).show();
        }
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                localStorage.setItem('hostActiveTab', e.target.getAttribute('data-bs-target'));
            });
        });
    });
</script>
@endsection