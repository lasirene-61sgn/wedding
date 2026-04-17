@extends('layouts.host')

@section('content')
<div style="max-width: 1000px; margin: 30px auto; font-family: 'Inter', sans-serif; padding: 0 20px;">
    
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
        <div style="background: #1e293b; padding: 30px; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 22px;">Edit Guest: {{ $guestlist->guest_name }}</h2>
                <p style="margin: 5px 0 0; opacity: 0.7; font-size: 14px;">Complete profile and automated address lookup.</p>
            </div>
            <a href="{{ route('host.guestlist.index') }}" style="color: white; text-decoration: none; font-size: 14px; opacity: 0.8;">&larr; Back to List</a>
        </div>

        <form action="{{ route('host.guestlist.update', $guestlist->id) }}" method="POST" style="padding: 30px;">
            @csrf @method('PUT')

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 700; color: #4f46e5; margin-bottom: 15px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Event Assignment</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; background: #f8fafc; padding: 20px; border-radius: 15px; border: 1px solid #f1f5f9;">
                    @foreach($ceramonies as $ceremony)
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; color: #334155; background: white; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <input type="checkbox" name="ceremony_ids[]" value="{{ $ceremony->id }}" 
                            @if(str_contains($guestlist->assigned_ceremonies ?? '', $ceremony->ceramony_name)) checked @endif>
                        {{ $ceremony->ceramony_name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 700; color: #4f46e5; margin-bottom: 15px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Guest Details</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Full Name</label>
                        <input type="text" name="guest_name" value="{{ $guestlist->guest_name }}" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Primary Number</label>
                        <input type="text" name="guest_number" value="{{ $guestlist->guest_number }}" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ $guestlist->whatsapp_number }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Gender</label>
                        <select name="gender" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                            <option value="">Select</option>
                            <option value="male" {{ $guestlist->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $guestlist->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $guestlist->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Relation</label>
                        <select name="relation" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                            <option value="bride" {{ $guestlist->relation == 'bride' ? 'selected' : '' }}>Bride Side</option>
                            <option value="groom" {{ $guestlist->relation == 'groom' ? 'selected' : '' }}>Groom Side</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Age</label>
                        <input type="text" name="age" value="{{ $guestlist->age }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 30px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                <label style="display: block; font-weight: 700; color: #4f46e5; margin-bottom: 15px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Address Information</label>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #b91c1c; margin-bottom: 8px;">Pincode</label>
                        <input type="text" name="pincode" id="pincode" value="{{ $guestlist->pincode }}" maxlength="6" onkeyup="fetchAddress()" placeholder="Type 6 digits..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; background: #fffaf0;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Area Name</label>
                        <input type="text" name="area_name" id="area_name" value="{{ $guestlist->area_name }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">District</label>
                        <input type="text" name="district" id="district" value="{{ $guestlist->district }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">State</label>
                        <input type="text" name="state" id="state" value="{{ $guestlist->state }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Circle</label>
                        <input type="text" name="circle" id="circle" value="{{ $guestlist->circle }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Door No.</label>
                        <input type="text" name="door_no" value="{{ $guestlist->door_no }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Complex</label>
                        <input type="text" name="complex" value="{{ $guestlist->complex }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Street</label>
                        <input type="text" name="street_name" value="{{ $guestlist->street_name }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px; margin-top: 15px;">
                     <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Country</label>
                        <input type="text" name="country" id="country" value="{{ $guestlist->country ?? 'India' }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Google Maps URL</label>
                        <input type="text" name="location_map" value="{{ $guestlist->location_map }}" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; color: #4f46e5;">
                    </div>
                </div>
            </div>

            <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 14px 60px; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 16px;">Update Records</button>
            </div>
        </form>
    </div>
</div>

<script>
function fetchAddress() {
    let pincode = document.getElementById('pincode').value;
    if (pincode.length === 6) {
        fetch(`https://api.postalpincode.in/pincode/${pincode}`)
            .then(response => response.json())
            .then(data => {
                if (data[0].Status === "Success") {
                    let postOffice = data[0].PostOffice[0]; // Picking the first available post office
                    
                    document.getElementById('area_name').value = postOffice.Name;
                    document.getElementById('district').value = postOffice.District;
                    document.getElementById('state').value = postOffice.State;
                    document.getElementById('circle').value = postOffice.Circle;
                    document.getElementById('country').value = postOffice.Country;
                }
            })
            .catch(error => console.log('Error fetching pincode data:', error));
    }
}
</script>
@endsection