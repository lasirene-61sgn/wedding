@extends('layouts.host')

@section('content')
    <div class="main-container" style="padding: 20px; font-family: 'Inter', sans-serif; background: #fbfcfe;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0;">Guest Management</h1>
                <p style="color: #64748b; font-size: 14px;">Total Guests: {{ $guestlists->total() }}</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="data:text/csv;charset=utf-8,name,number,email,relation%0ARamesh,9876543210,ramesh@example.com,groom%0ASunita,9123456789,sunita@test.com,bride"
                    download="wedding_guests_template.csv"
                    style="display: flex; align-items: center; gap: 8px; background: #fff; color: #475569; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0;">
                    Download Sample
                </a>
                <a href="{{ route('host.guestlist.create') }}"
                    style="background: #4f46e5; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">+
                    Add Guest</a>
                <button type="button" onclick="openImportModal()"
                    style="background: #10b981; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px;">Import
                    Excel</button>
            </div>
        </div>

        <div style="background: white; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
            <form action="{{ route('host.guestlist.index') }}" method="GET"
                style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or number..."
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; flex: 1; min-width: 200px;">

                <select name="ceramony_id"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; min-width: 150px;">
                    <option value="">All Ceremonies</option>
                    @foreach($ceramonies as $ceremony)
                        <option value="{{ $ceremony->id }}" {{ request('ceramony_id') == $ceremony->id ? 'selected' : '' }}>
                            {{ $ceremony->ceramony_name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; min-width: 150px;">
                    <option value="">All Status</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Invitation Sent</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>

                <button type="submit"
                    style="background: #1e293b; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">Filter
                    Results</button>
            </form>
        </div>

        <div id="bulk-bar"
            style="display: none; background: #ffffff; border: 2px solid #4f46e5; padding: 20px; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); animation: slideDown 0.3s ease-out;">
            <form action="{{ route('host.guestlist.bulkSend') }}" method="POST" id="bulk-send-form">
                @csrf
                <div style="display: flex; flex-wrap: wrap; gap: 25px; align-items: flex-start;">
                    <div>
                        <span id="count-text" style="display: block; font-weight: 800; color: #4f46e5; font-size: 18px;">0
                            Guests Selected</span>
                        <p style="font-size: 12px; color: #64748b; margin-top: 4px;">Update ceremonies & invites</p>
                    </div>

                    <div style="flex: 1; min-width: 220px;">
    <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">
        Assign Category:
    </label>
    <select name="category_id" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
        <option value="">Select a Category</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
        @endforeach
    </select>
    <p style="font-size: 11px; color: #64748b; mt-2">This will auto-assign all ceremonies linked to this category.</p>
</div>

                    <div style="flex: 1; min-width: 220px;">
                        <label
                            style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Send
                            Invitation Via:</label>
                        <div
                            style="display: flex; flex-direction: column; gap: 8px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <label
                                style="font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;"><input
                                    type="checkbox" name="channels[]" value="whatsapp"> WhatsApp</label>
                            <label
                                style="font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;"><input
                                    type="checkbox" name="channels[]" value="sms"> SMS</label>
                            <label
                                style="font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;"><input
                                    type="checkbox" name="channels[]" value="email"> Email</label>
                        </div>
                    </div>

                    <div style="align-self: flex-end;">
                        <button type="submit"
                            style="background: #4f46e5; color: white; border: none; padding: 14px 35px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px;">
                            Execute Bulk Action
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div
            style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 18px; width: 50px;">
                            <input type="checkbox" id="master-checkbox"
                                style="width: 18px; height: 18px; cursor: pointer; accent-color: #4f46e5;">
                        </th>
                        <th
                            style="padding: 18px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                            Guest Info</th>
                        <th
                            style="padding: 18px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                            Assigned Ceremony</th>
                        <th
                            style="padding: 18px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">
                            Invite Status</th>
                        <th
                            style="padding: 18px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: right;">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guestlists as $guest)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 18px;">
                                @if($guest->invitation_sent)
                                    <input type="checkbox" disabled
                                        style="width: 17px; height: 17px; cursor: not-allowed; opacity: 0.3;">
                                @else
                                    <input type="checkbox" class="guest-item" name="ids[]" value="{{ $guest->id }}"
                                        form="bulk-send-form" style="width: 17px; height: 17px; cursor: pointer;">
                                @endif
                            </td>
                            <td style="padding: 18px;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $guest->guest_name }}</div>
                                <div style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ $guest->guest_number }}</div>
                            </td>
                            <td style="padding: 18px;">
                                @if($guest->assigned_ceremonies)
                                    <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid #c7d2fe; display: inline-block;">
                                        {{ $guest->assigned_ceremonies }}
                                    </span>
                                @elseif($guest->ceramony)
                                    <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid #c7d2fe; display: inline-block;">
                                        {{ $guest->ceramony->ceramony_name }}
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 12px; font-style: italic;">Not Assigned</span>
                                @endif
                            </td>
                            <td style="padding: 18px;">
                                @if($guest->invitation_sent)
                                    <div
                                        style="color: #10b981; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div> Sent
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 5px; padding-left: 16px;">
                                        Via: <span
                                            style="text-transform: capitalize; color: #4f46e5; font-weight: 600;">{{ $guest->send_via }}</span>
                                    </div>
                                @else
                                    <div
                                        style="color: #f59e0b; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%;"></div>
                                        Pending
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 18px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 15px; align-items: center;">

                                    <a href="{{ route('host.guestlist.show', $guest->id) }}"
                                        style="color: #64748b; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 4px;"
                                        title="View Profile">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>

                                    <a href="{{ route('host.guestlist.edit', $guest->id) }}"
                                        style="color: #4f46e5; text-decoration: none; font-weight: 600; font-size: 14px;">
                                        Edit
                                    </a>

                                    <form action="{{ route('host.guestlist.destroy', $guest->id) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Delete this guest?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="color: #ef4444; border: none; background: none; cursor: pointer; font-weight: 600; font-size: 14px; padding: 0;">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 50px; text-align: center; color: #94a3b8;">No guests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 25px;">{{ $guestlists->links() }}</div>
    </div>

    <div id="importModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 16px; width: 100%; max-width: 450px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 18px; font-weight: 700;">Import Guest List</h3>
                <button onclick="closeImportModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #94a3b8;">&times;</button>
            </div>
            <form action="{{ route('host.guestlist.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required style="width: 100%; padding: 10px; border: 1px dashed #ddd; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeImportModal()" style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">Cancel</button>
                    <button type="submit" style="flex: 1; padding: 12px; border-radius: 10px; border: none; background: #10b981; color: white; font-weight: 600;">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const master = document.getElementById('master-checkbox');
        const items = document.querySelectorAll('.guest-item');
        const bulkBar = document.getElementById('bulk-bar');
        const countText = document.getElementById('count-text');

        function openImportModal() { document.getElementById('importModal').style.display = 'flex'; }
        function closeImportModal() { document.getElementById('importModal').style.display = 'none'; }

        function toggleBar() {
            const checked = document.querySelectorAll('.guest-item:checked').length;
            bulkBar.style.display = checked > 0 ? 'block' : 'none';
            countText.innerText = checked + (checked === 1 ? " Guest Selected" : " Guests Selected");
        }

        master.addEventListener('change', () => {
            items.forEach(i => { if (!i.disabled) i.checked = master.checked; });
            toggleBar();
        });

        items.forEach(i => i.addEventListener('change', toggleBar));
    </script>
@endsection