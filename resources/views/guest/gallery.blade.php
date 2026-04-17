<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Gallery | {{ $invite->host->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #c5a059;
            --pink: #d63384;
            --dark: #2d2d2d;
            --light: #fffafa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            margin: 0; padding: 0;
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 20px; text-align: center; }

        .gallery-header h1 {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(2.5rem, 8vw, 4rem);
            color: var(--pink);
            margin-bottom: 5px;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--gold);
            margin: 40px 0 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Responsive Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
        }

        @media (min-width: 768px) {
            .grid { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
        }

        /* Image Styling */
        .img-card {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        @media (min-width: 768px) { .img-card { height: 300px; } }

        .img-card:hover { transform: scale(1.02); }

        /* Album Folder Styling */
        .album-folder {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .album-label {
            position: absolute;
            bottom: 0; width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            font-weight: 600;
            color: var(--dark);
        }

        /* Universal Lightbox (For viewing images) */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 2000;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95);
            justify-content: center;
            align-items: center;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 85vh;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }

        .close-lb {
            position: absolute;
            top: 20px; right: 30px;
            color: white; font-size: 40px; cursor: pointer;
        }

        /* Full Album View Overlay */
        .album-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: white;
            z-index: 1000;
            overflow-y: auto;
            padding: 20px;
        }

        .video-card { width: 100%; border-radius: 15px; background: #000; }
    </style>
</head>
<body>

<div class="container">
    <div class="gallery-header">
        <a href="{{ route('guest.wedding.details', $invite->id) }}" style="text-decoration:none; color:var(--pink);">← Back</a>
        <h1>Wedding Gallery</h1>
        <p>Moments of <strong>{{ $invite->host->bride_name }} & {{ $invite->host->groom_name }}</strong></p>
    </div>

    @if($albums->count() > 0)
        <h2 class="section-title">Photo Albums</h2>
        <div class="grid">
            @foreach($albums as $album)
                <div class="album-folder" onclick="toggleAlbum('{{ $album->id }}', true)">
                    <img src="{{ asset('storage/' . $album->album_images[0]) }}" class="img-card">
                    <div class="album-label">📂 {{ $album->album_name }} ({{ count($album->album_images) }})</div>
                </div>

                <div id="album-container-{{ $album->id }}" class="album-overlay">
                    <h2 style="color: var(--pink);">{{ $album->album_name }}</h2>
                    <button onclick="toggleAlbum('{{ $album->id }}', false)" style="margin-bottom:20px; padding:10px 20px; border:none; background:var(--gold); color:white; border-radius:5px;">Close Album</button>
                    <div class="grid">
                        @foreach($album->album_images as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="img-card" onclick="openLightbox(this.src)">
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($pictures->count() > 0)
        <h2 class="section-title">Single Photos</h2>
        <div class="grid">
            @foreach($pictures as $pic)
                <img src="{{ asset('storage/' . $pic->picture) }}" class="img-card" onclick="openLightbox(this.src)">
            @endforeach
        </div>
    @endif

    @if($videos->count() > 0)
        <h2 class="section-title">Wedding Films</h2>
        <div class="grid">
            @foreach($videos as $vid)
                <video class="video-card" controls>
                    <source src="{{ asset('storage/' . $vid->videos) }}" type="video/mp4">
                </video>
            @endforeach
        </div>
    @endif
</div>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="close-lb">&times;</span>
    <img id="lightbox-img" src="">
</div>

<script>
    // Open Album Overlay
    function toggleAlbum(id, show) {
        const el = document.getElementById('album-container-' + id);
        el.style.display = show ? 'block' : 'none';
        document.body.style.overflow = show ? 'hidden' : 'auto';
    }

    // Lightbox for full-size viewing
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        // Only re-enable scrolling if an album overlay isn't also open
        if (!document.querySelector('.album-overlay[style*="display: block"]')) {
            document.body.style.overflow = 'auto';
        }
    }
</script>

</body>
</html>