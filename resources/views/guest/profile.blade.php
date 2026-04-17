
<style>
    /* Premium Invitation Theme Colors */
    :root {
        --primary-pink: #d63384;
        --royal-gold: #c5a059;
        --bg-warm: #fffafa;
        --soft-border: #ffdceb;
    }

    body { background-color: var(--bg-warm); font-family: 'Poppins', sans-serif; }

    .profile-container { max-width: 600px; margin: 30px auto; padding: 0 15px; }
    
    .profile-card { 
        background: white; 
        border-radius: 30px; 
        padding: 35px; 
        box-shadow: 0 15px 40px rgba(214, 51, 132, 0.08); 
        border: 1px solid #fff5f8; 
    }
    
    .section-head h2 { font-family: 'Great Vibes', cursive; color: var(--royal-gold); font-size: 3rem; text-align: center; margin-bottom: 5px; }
    .section-head p { text-align: center; color: #777; margin-bottom: 25px; font-size: 0.9rem; }

    /* Form Styling */
    .group-title { 
        font-size: 0.8rem; font-weight: 800; color: var(--royal-gold); 
        margin-bottom: 20px; text-transform: uppercase; 
        border-left: 3px solid var(--royal-gold); padding-left: 10px; 
        letter-spacing: 1px;
    }

    .form-label { font-weight: 700; color: var(--primary-pink); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; }
    
    .form-control { 
        border-radius: 12px; border: 1px solid #f1f1f1; padding: 12px 15px; 
        margin-bottom: 18px; width: 100%; transition: 0.3s; 
        background: #fafafa; font-size: 0.95rem; 
    }
    
    .form-control:focus { border-color: var(--primary-pink); background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.1); }
    
    .auto-fetch-bg { background: #fffafa !important; border: 1px solid var(--soft-border) !important; font-weight: bold; color: var(--primary-pink); }
    
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* Smart Fill Button */
    .btn-smart-fill {
        background: #fff5f8; color: var(--primary-pink); 
        border: 1px dashed var(--primary-pink); width: 100%; 
        padding: 12px; border-radius: 12px; font-size: 0.85rem; 
        font-weight: 700; cursor: pointer; transition: 0.3s;
    }
    .btn-smart-fill:hover { background: var(--primary-pink); color: white; }

    /* Action Button */
    .btn-update { 
        background: var(--primary-pink); color: white; border: none; 
        width: 100%; padding: 16px; border-radius: 50px; 
        font-weight: 700; cursor: pointer; font-size: 1.1rem; 
        margin-top: 15px; transition: 0.3s; 
    }
    .btn-update:hover { background: #b0246b; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(214, 51, 132, 0.3); }

    hr { border: 0; border-top: 1px dashed var(--soft-border); margin: 30px 0; }

    /* Mobile responsiveness */
    @media (max-width: 480px) {
        .grid-2 { grid-template-columns: 1fr; }
        .profile-card { padding: 25px 20px; }
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<div class="profile-container">
    <div class="section-head">
        <h2>Your Profile</h2>
        <p>Ensure your details are accurate for wedding arrangements</p>
    </div>

    <div id="smart-fill-section" class="mb-4" style="display: none;">
        <button type="button" onclick="smartFill()" class="btn-smart-fill shadow-sm">
            ✨ Sync details from my previous entries
        </button>
    </div>

    <div class="profile-card">
        <form action="{{ route('guest.profile.update', $invite->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="group-title">1. Personal Details</div>
            
            <label class="form-label">Full Name</label>
            <input type="text" name="guest_name" class="form-control" value="{{ $invite->guest_name }}" required>

            <div class="grid-2">
                <div>
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select</option>
                        <option value="male" {{ $invite->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $invite->gender == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $invite->gender == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Age</label>
                    <input type="text" name="age" class="form-control" value="{{ $invite->age }}" placeholder="Years">
                </div>
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label">Relation</label>
                    <select name="relation" class="form-control">
                        <option value="bride" {{ $invite->relation == 'bride' ? 'selected' : '' }}>Bride Side</option>
                        <option value="groom" {{ $invite->relation == 'groom' ? 'selected' : '' }}>Groom Side</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ $invite->whatsapp_number }}">
                </div>
            </div>

            <label class="form-label">Email Address</label>
            <input type="email" name="guest_email" class="form-control" value="{{ $invite->guest_email }}">

            <hr>
            <div class="group-title">2. Address & Communication</div>

            <div class="grid-2">
                <div>
                    <label class="form-label" style="color: #b91c1c;">Pincode (Auto-Fetch)</label>
                    <input type="text" name="pincode" id="pincode" class="form-control auto-fetch-bg" value="{{ $invite->pincode }}" maxlength="6" onkeyup="fetchAddress()" placeholder="6 Digits">
                </div>
                <div>
                    <label class="form-label">Area / Location</label>
                    <input type="text" name="area_name" id="area_name" class="form-control" value="{{ $invite->area_name }}">
                </div>
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label">District</label>
                    <input type="text" name="district" id="district" class="form-control" value="{{ $invite->district }}">
                </div>
                <div>
                    <label class="form-label">State</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ $invite->state }}">
                </div>
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label">Circle</label>
                    <input type="text" name="circle" id="circle" class="form-control" value="{{ $invite->circle }}">
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="{{ $invite->country ?? 'India' }}">
                </div>
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label">Complex / Building</label>
                    <input type="text" name="complex" class="form-control" value="{{ $invite->complex }}" placeholder="Apt name">
                </div>
                <div>
                    <label class="form-label">Door No / Floor</label>
                    <input type="text" name="door_no" class="form-control" value="{{ $invite->door_no }}" placeholder="e.g. 402, 4th Flr">
                </div>
            </div>

            <label class="form-label">Street / Colony Name</label>
            <input type="text" name="street_name" class="form-control" value="{{ $invite->street_name }}">

            <label class="form-label">Google Maps (Home Location)</label>
            <input type="text" name="location_map" class="form-control" value="{{ $invite->location_map }}" placeholder="Paste Google Maps URL">

            <button type="submit" class="btn-update shadow">Save & Update Profile</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('guest.wedding.details', $invite->id) }}" style="color: #777; text-decoration: none; font-size: 0.85rem; font-weight: 600;">← Back to My Invitation</a>
        </div>
    </div>
</div>

<script>
    /** * 1. Smart Fill Logic 
     * Checks if the guest has other entries on the platform
     */
    document.addEventListener('DOMContentLoaded', function() {
        fetch("{{ route('guest.profile.get_previous') }}")
            .then(res => res.json())
            .then(result => {
                if(result.success) {
                    document.getElementById('smart-fill-section').style.display = 'block';
                    window.previousData = result.data;
                }
            });
    });

    function smartFill() {
        const data = window.previousData;
        if(!data) return;

        const fields = [
            'guest_name', 'guest_email', 'whatsapp_number', 'gender', 'age', 
            'pincode', 'area_name', 'district', 'state', 'circle', 
            'complex', 'door_no', 'street_name', 'location_map', 'country'
        ];

        fields.forEach(field => {
            let input = document.querySelector(`[name="${field}"]`);
            if(input && data[field]) {
                input.value = data[field];
            }
        });
        alert("Details synced successfully! Review and click Save.");
    }

    /** * 2. Auto-Pincode Logic 
     * Fetches Area, District, State, and Circle automatically
     */
    function fetchAddress() {
        let pincode = document.getElementById('pincode').value;
        if (pincode.length === 6) {
            fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                .then(response => response.json())
                .then(data => {
                    if (data[0].Status === "Success") {
                        let postOffice = data[0].PostOffice[0];
                        document.getElementById('area_name').value = postOffice.Name;
                        document.getElementById('district').value = postOffice.District;
                        document.getElementById('state').value = postOffice.State;
                        document.getElementById('circle').value = postOffice.Circle;
                        document.getElementById('country').value = postOffice.Country;
                    }
                })
                .catch(error => console.log('Error:', error));
        }
    }
</script>
