<style>
    :root {
        --primary-pink: #d63384;
        --soft-gold: #c5a059;
        --bg-warm: #fffafa;
        --text-dark: #2d3436;
    }

    body {
        background-color: var(--bg-warm);
        font-family: 'Poppins', sans-serif;
    }

    .invitation-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    .save-the-date-card {
        background: white;
        max-width: 500px;
        width: 100%;
        padding: 40px;
        border-radius: 30px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(214, 51, 132, 0.1);
        border: 1px solid rgba(214, 51, 132, 0.05);
        position: relative;
    }

    /* Decorative corner elements */
    .save-the-date-card::before {
        content: '✿';
        position: absolute;
        top: 20px;
        left: 20px;
        color: var(--soft-gold);
        font-size: 20px;
        opacity: 0.5;
    }

    h1 {
        font-family: 'Great Vibes', cursive;
        color: var(--soft-gold);
        font-size: 3.5rem;
        margin-bottom: 0;
        letter-spacing: 2px;
    }

    .sub-text {
        color: var(--text-dark);
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.8rem;
        margin-bottom: 30px;
    }

    h2 {
        color: var(--primary-pink);
        font-size: 1.8rem;
        margin-top: 10px;
    }

    hr {
        border: 0;
        height: 1px;
        background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(197, 160, 89, 0.75), rgba(0, 0, 0, 0));
        margin: 30px 0;
    }

    .couple-names h3 {
        font-size: 2.2rem;
        font-family: 'Great Vibes', cursive;
        color: var(--primary-pink);
        margin: 10px 0;
    }

    .ceremonies-preview {
        background: var(--bg-warm);
        padding: 20px;
        border-radius: 15px;
        margin: 20px 0;
    }

    .ceremonies-preview h4 {
        font-size: 0.9rem;
        color: var(--soft-gold);
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .ceremonies-preview p {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Buttons */
    .btn-accept {
        background: var(--primary-pink);
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
    }

    .btn-reject {
        background: transparent;
        color: #b2bec3;
        padding: 12px 30px;
        border-radius: 50px;
        border: 1px solid #dfe6e9;
        font-weight: 600;
        cursor: pointer;
        margin-left: 10px;
    }

    .btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(214, 51, 132, 0.4);
    }

    /* Pending Invite Styling */
    .not-invited h3 {
        color: var(--soft-gold);
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

<div class="invitation-wrapper">
    <div class="save-the-date-card">
        @if($invite->invitation_sent)
            
            <h1>Save the Date</h1>
            <p class="sub-text">We invite you to the wedding of</p>
            
            <div class="couple-names">
                <h3>{{ $invite->host->bride_name }} & {{ $invite->host->groom_name }}</h3>
            </div>
            
            <p style="color: #636e72; font-size: 0.9rem;">Hosted by {{ $invite->host->name }}</p>

            <hr>

            <div class="ceremonies-preview">
                <h4>Ceremonies you are invited to:</h4>
                <p>{{ $invite->assigned_ceremonies }}</p>
            </div>

            <div class="actions" style="margin-top: 30px;">
                @if($invite->status !== 'accepted')
                    <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn-accept">
                            ✔ I'm Coming!
                        </button>
                    </form>

                    <form action="{{ route('guest.update_status', $invite->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn-reject">
                            ✘ Sorry, I can't
                        </button>
                    </form>
                @else
                    <div class="alert" style="background: #e1f5fe; padding: 20px; border-radius: 15px; color: #0288d1;">
                        <p style="margin-bottom: 15px; font-weight: 600;">You have accepted this invitation! 🎉</p>
                        <a href="{{ route('guest.wedding.details', $invite->id) }}" style="text-decoration: none; background: #0288d1; color: white; padding: 10px 25px; border-radius: 50px; display: inline-block; font-size: 0.9rem;">
                            Enter Wedding Details & Gallery
                        </a>
                    </div>
                @endif
            </div>

        @else
            <div class="not-invited">
                <div style="font-size: 4rem; margin-bottom: 20px;">💌</div>
                <h3>Invitation Pending</h3>
                <p style="color: #636e72;">Your invitation for this wedding is being prepared by the host. Please check back later!</p>
                <div style="margin-top: 30px;">
                    <a href="{{ route('guest.select') }}" style="color: var(--primary-pink); text-decoration: none; font-weight: 600;">← Back to My Invitations</a>
                </div>
            </div>
        @endif
    </div>
</div>