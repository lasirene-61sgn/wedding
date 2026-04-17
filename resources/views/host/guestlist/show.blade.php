@extends('layouts.host')

@section('content')
<div style="max-width: 900px; margin: 30px auto; font-family: 'Inter', sans-serif; padding: 0 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">Guest Profile</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('host.guestlist.index') }}" style="text-decoration: none; padding: 10px 20px; background: #f1f5f9; color: #475569; border-radius: 10px; font-weight: 600; font-size: 14px;">Back</a>
            <a href="{{ route('host.guestlist.edit', $guestlist->id) }}" style="text-decoration: none; padding: 10px 20px; background: #4f46e5; color: white; border-radius: 10px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);">Edit Profile</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <div style="background: white; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
                    <div style="width: 60px; height: 60px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; border-radius: 15px; font-size: 24px; font-weight: 800;">
                        {{ substr($guestlist->guest_name, 0, 1) }}
                    </div>
                    <div>
                        <h1 style="margin: 0; font-size: 22px; color: #1e293b;">{{ $guestlist->guest_name }}</h1>
                        <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">{{ ucfirst($guestlist->relation) }}'s Side</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Phone Number</label>
                        <p style="font-weight: 600; color: #334155; margin: 5px 0;">{{ $guestlist->guest_number }}</p>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">WhatsApp</label>
                        <p style="font-weight: 600; color: #334155; margin: 5px 0;">{{ $guestlist->whatsapp_number ?? 'N/A' }}</p>
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</label>
                        <p style="font-weight: 600; color: #334155; margin: 5px 0;">{{ $guestlist->guest_email ?? 'No email saved' }}</p>
                    </div>
                </div>
            </div>

            <div style="background: white; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 15px; font-size: 16px; color: #1e293b;">Location Details</h3>
                <p style="color: #475569; line-height: 1.6; margin: 0; font-size: 15px;">
                    {{ $guestlist->door_no }}, {{ $guestlist->complex }}<br>
                    {{ $guestlist->street_name }}<br>
                    {{ $guestlist->district }}, {{ $guestlist->state }} - {{ $guestlist->pincode }}
                </p>
                @if($guestlist->location_map)
                    <a href="{{ $guestlist->location_map }}" target="_blank" style="display: inline-block; margin-top: 15px; color: #4f46e5; font-weight: 700; text-decoration: none; font-size: 14px;">View on Google Maps ↗</a>
                @endif
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 25px;">
            <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0; text-align: center;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px;">Invite Status</label>
                @if($guestlist->invitation_sent)
                    <span style="background: #ecfdf5; color: #059669; padding: 8px 16px; border-radius: 100px; font-weight: 700; font-size: 12px; display: inline-block;">● Sent</span>
                    <p style="font-size: 11px; color: #64748b; margin-top: 10px;">Via: {{ $guestlist->send_via }}</p>
                @else
                    <span style="background: #fffbeb; color: #d97706; padding: 8px 16px; border-radius: 100px; font-weight: 700; font-size: 12px; display: inline-block;">● Pending</span>
                @endif
            </div>

            <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; text-align: center;">Selected Events</label>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                    @php $events = explode(', ', $guestlist->assigned_ceremonies); @endphp
                    @forelse($events as $event)
                        <span style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #475569;">{{ $event }}</span>
                    @empty
                        <span style="color: #94a3b8; font-size: 12px; font-style: italic;">None assigned</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection