@extends('layouts.admin')

@section('content')
<div class="custom-bg-manager">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h2>Ceremony Backgrounds</h2>
            <p>Manage default background templates and assets available for hosts.</p>
        </div>
        <div>
            <span class="total-badge">
                Total Backgrounds: {{ $backgrounds->count() }}
            </span>
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
        <div class="custom-alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Content Container -->
    <div class="content-grid">
        <!-- Upload Section -->
        <div class="card-box">
            <div class="card-box-header">
                <h5><i class="bi bi-cloud-arrow-up"></i> Upload New Background</h5>
            </div>
            <div class="card-box-body">
                <form action="{{ route('admin.ceramony.backgrounds.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-dropzone">
                        <i class="bi bi-image icon-large"></i>
                        <h6>Choose or drag an image here</h6>
                        <p class="subtext">JPG, PNG, or WEBP up to 4MB</p>
                        
                        <input type="file" name="background_image" id="background_image" class="file-input @error('background_image') input-error @enderror" required accept="image/*" onchange="previewFileName(this)">
                        
                        <label for="background_image" class="browse-btn">
                            Browse Image
                        </label>

                        <div id="file-name-preview" class="file-preview-name"></div>
                    </div>

                    @error('background_image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="submit-btn">
                        <i class="bi bi-upload"></i> Upload Image
                    </button>
                </form>
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="card-box">
            <div class="card-box-header">
                <h5><i class="bi bi-grid"></i> Available Choices</h5>
            </div>
            <div class="card-box-body">
                @if($backgrounds->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon-circle">
                            <i class="bi bi-images"></i>
                        </div>
                        <h6>No Backgrounds Uploaded</h6>
                        <p>Upload a background image on the left panel to display options here.</p>
                    </div>
                @else
                    <div class="gallery-grid">
                        @foreach($backgrounds as $bg)
                            <div class="gallery-card">
                                <div class="image-wrapper">
                                    <img src="{{ asset('storage/' . $bg->image_path) }}" alt="Background Template">
                                    <div class="hover-overlay">
                                        <form action="{{ route('admin.ceramony.backgrounds.destroy', $bg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this background?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Reset & Layout Container */
    .custom-bg-manager {
        padding: 24px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #1e293b;
    }

    /* Page Header */
    .custom-bg-manager .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-bg-manager .page-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #0f172a;
    }

    .custom-bg-manager .page-header p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .custom-bg-manager .total-badge {
        background-color: #e0e7ff;
        color: #4338ca;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    /* Alert */
    .custom-bg-manager .custom-alert {
        background-color: #dcfce7;
        color: #15803d;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        font-size: 14px;
    }

    /* Layout Grid */
    .custom-bg-manager .content-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .custom-bg-manager .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .custom-bg-manager .card-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .custom-bg-manager .card-box-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }

    .custom-bg-manager .card-box-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-bg-manager .card-box-header i {
        color: #2563eb;
    }

    .custom-bg-manager .card-box-body {
        padding: 20px;
    }

    /* Upload Area */
    .custom-bg-manager .upload-dropzone {
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 24px 16px;
        text-align: center;
        margin-bottom: 16px;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .custom-bg-manager .upload-dropzone:hover {
        border-color: #2563eb;
        background-color: #f1f5f9;
    }

    .custom-bg-manager .icon-large {
        font-size: 40px;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }

    .custom-bg-manager .upload-dropzone h6 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .custom-bg-manager .upload-dropzone .subtext {
        font-size: 12px;
        color: #64748b;
        margin: 0 0 16px 0;
    }

    .custom-bg-manager .file-input {
        display: none;
    }

    .custom-bg-manager .browse-btn {
        display: inline-block;
        background-color: #ffffff;
        color: #2563eb;
        border: 1px solid #2563eb;
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .custom-bg-manager .browse-btn:hover {
        background-color: #2563eb;
        color: #ffffff;
    }

    .custom-bg-manager .file-preview-name {
        margin-top: 10px;
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        word-break: break-all;
    }

    .custom-bg-manager .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .custom-bg-manager .submit-btn {
        width: 100%;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        padding: 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background-color 0.2s;
    }

    .custom-bg-manager .submit-btn:hover {
        background-color: #1d4ed8;
    }

    /* Empty Gallery State */
    .custom-bg-manager .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .custom-bg-manager .empty-icon-circle {
        width: 64px;
        height: 64px;
        background: #f1f5f9;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #94a3b8;
        margin-bottom: 12px;
    }

    .custom-bg-manager .empty-state h6 {
        margin: 0 0 4px 0;
        font-size: 15px;
        font-weight: 600;
        color: #334155;
    }

    .custom-bg-manager .empty-state p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    /* Gallery Grid */
    .custom-bg-manager .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .custom-bg-manager .gallery-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8fafc;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .custom-bg-manager .gallery-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .custom-bg-manager .image-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }

    .custom-bg-manager .image-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .custom-bg-manager .hover-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .custom-bg-manager .gallery-card:hover .hover-overlay {
        opacity: 1;
    }

    .custom-bg-manager .delete-btn {
        background-color: #ef4444;
        color: #ffffff;
        border: none;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: background-color 0.2s;
    }

    .custom-bg-manager .delete-btn:hover {
        background-color: #dc2626;
    }
</style>

<script>
    function previewFileName(input) {
        const preview = document.getElementById('file-name-preview');
        if (input.files && input.files[0]) {
            preview.textContent = 'Selected: ' + input.files[0].name;
        } else {
            preview.textContent = '';
        }
    }
</script>
@endsection