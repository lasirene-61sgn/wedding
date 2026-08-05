@extends('layouts.host')

@section('content')
<div class="main-container" style="padding: 20px; font-family: 'Inter', sans-serif; background: #fbfcfe;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0;">Guest Management</h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Total Guests: {{ $guestlists->total() }} | Reminders Sent: <span style="font-weight: 700; color: #f59e0b;">{{ Auth::user()->reminders_sent_count ?? 0 }}</span></p>
        </div>
        <div style="display: flex; gap: 12px;">
            @if(isset($categories) && count($categories) > 0)
                <a href="{{ route('host.guestlist.downloadSample') }}"
                    style="display: flex; align-items: center; gap: 8px; background: #fff; color: #475569; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0;">
                    Download Sample
                </a>
                <a href="{{ route('host.guestlist.create') }}"
                    style="background: #4f46e5; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">+
                    Add Guest</a>
                <button type="button" onclick="openImportModal()"
                    style="background: #10b981; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px;">Import
                    Excel</button>
            @else
                <button type="button" onclick="alert('Please create at least one Guest Category before downloading the sample.')"
                    style="display: flex; align-items: center; gap: 8px; background: #fff; color: #94a3b8; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0; cursor: not-allowed;">
                    Download Sample
                </button>
                <button type="button" onclick="alert('Please create at least one Guest Category before adding guests.')"
                    style="background: #a5b4fc; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: not-allowed; font-size: 14px;">+
                    Add Guest</button>
                <button type="button" onclick="alert('Please create at least one Guest Category before importing guests via Excel.')"
                    style="background: #6ee7b7; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: not-allowed; font-size: 14px;">Import
                    Excel</button>
            @endif
            <button type="button" onclick="openReminderModal()"
                style="background: #f59e0b; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px;">
                Send Reminders
            </button>
        </div>
    </div>

    {{-- ═══════ Permanent Per-Channel Quota Bar ═══════ --}}
    @php
        $_qHost    = Auth::user();
        $_waLimit  = $_qHost->effectiveWhatsappLimit();
        $_smsLimit = $_qHost->effectiveSmsLimit();
        $_emLimit  = $_qHost->effectiveEmailLimit();
        $_waSent   = (int)($_qHost->whatsapp_sent_count ?? 0);
        $_smsSent  = (int)($_qHost->sms_sent_count     ?? 0);
        $_emSent   = (int)($_qHost->email_sent_count   ?? 0);
        $_waPct    = $_waLimit  > 0 ? min(100, round($_waSent  / $_waLimit  * 100)) : 0;
        $_smsPct   = $_smsLimit > 0 ? min(100, round($_smsSent / $_smsLimit * 100)) : 0;
        $_emPct    = $_emLimit  > 0 ? min(100, round($_emSent  / $_emLimit  * 100)) : 0;
    @endphp
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 16px; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
        <div style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">
            📊 Message Quota
            <div style="font-size: 10px; font-weight: 500; color: #94a3b8; text-transform: none; margin-top: 2px;">Save the Date + Invitations</div>
        </div>

        {{-- WhatsApp --}}
        <div style="flex: 1; min-width: 150px; max-width: 220px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 12px; font-weight: 700; color: #15803d;">📲 WhatsApp</span>
                    <a href="{{ route('host.addons.index') }}" style="font-size: 9px; font-weight: 700; color: #16a34a; text-decoration: none; background: #dcfce7; padding: 2px 5px; border-radius: 4px;">+ Add</a>
                </div>
                @if($_waLimit > 0)
                    <span style="font-size: 12px; font-weight: 700; color: {{ $_waSent >= $_waLimit ? '#ef4444' : '#1e293b' }};">{{ $_waSent }} / {{ $_waLimit }}</span>
                @else
                    <span style="font-size: 11px; font-weight: 600; color: #64748b;">Unlimited</span>
                @endif
            </div>
            @if($_waLimit > 0)
                <div style="background: #f1f5f9; border-radius: 99px; height: 7px; overflow: hidden;">
                    <div style="background: {{ $_waPct >= 100 ? '#ef4444' : ($_waPct >= 80 ? '#f59e0b' : '#22c55e') }}; width: {{ $_waPct }}%; height: 100%; border-radius: 99px; transition: width 0.4s;"></div>
                </div>
                @if($_waSent >= $_waLimit)
                    <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">⚠ Limit reached</div>
                @endif
            @endif
        </div>

        <div style="width: 1px; height: 40px; background: #e2e8f0;"></div>

        {{-- SMS --}}
        <div style="flex: 1; min-width: 150px; max-width: 220px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 12px; font-weight: 700; color: #a16207;">💬 SMS</span>
                    <a href="{{ route('host.addons.index') }}" style="font-size: 9px; font-weight: 700; color: #ca8a04; text-decoration: none; background: #fef9c3; padding: 2px 5px; border-radius: 4px;">+ Add</a>
                </div>
                @if($_smsLimit > 0)
                    <span style="font-size: 12px; font-weight: 700; color: {{ $_smsSent >= $_smsLimit ? '#ef4444' : '#1e293b' }};">{{ $_smsSent }} / {{ $_smsLimit }}</span>
                @else
                    <span style="font-size: 11px; font-weight: 600; color: #64748b;">Unlimited</span>
                @endif
            </div>
            @if($_smsLimit > 0)
                <div style="background: #f1f5f9; border-radius: 99px; height: 7px; overflow: hidden;">
                    <div style="background: {{ $_smsPct >= 100 ? '#ef4444' : ($_smsPct >= 80 ? '#f59e0b' : '#eab308') }}; width: {{ $_smsPct }}%; height: 100%; border-radius: 99px; transition: width 0.4s;"></div>
                </div>
                @if($_smsSent >= $_smsLimit)
                    <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">⚠ Limit reached</div>
                @endif
            @endif
        </div>

        <div style="width: 1px; height: 40px; background: #e2e8f0;"></div>

        {{-- Email --}}
        <div style="flex: 1; min-width: 150px; max-width: 220px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 12px; font-weight: 700; color: #1d4ed8;">✉️ Email</span>
                    <a href="{{ route('host.addons.index') }}" style="font-size: 9px; font-weight: 700; color: #2563eb; text-decoration: none; background: #dbeafe; padding: 2px 5px; border-radius: 4px;">+ Add</a>
                </div>
                @if($_emLimit > 0)
                    <span style="font-size: 12px; font-weight: 700; color: {{ $_emSent >= $_emLimit ? '#ef4444' : '#1e293b' }};">{{ $_emSent }} / {{ $_emLimit }}</span>
                @else
                    <span style="font-size: 11px; font-weight: 600; color: #64748b;">Unlimited</span>
                @endif
            </div>
            @if($_emLimit > 0)
                <div style="background: #f1f5f9; border-radius: 99px; height: 7px; overflow: hidden;">
                    <div style="background: {{ $_emPct >= 100 ? '#ef4444' : ($_emPct >= 80 ? '#f59e0b' : '#3b82f6') }}; width: {{ $_emPct }}%; height: 100%; border-radius: 99px; transition: width 0.4s;"></div>
                </div>
                @if($_emSent >= $_emLimit)
                    <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">⚠ Limit reached</div>
                @endif
            @endif
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════ --}}

    @if(session('success'))
    <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
        {{ session('error') }}
    </div>
    @endif

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
            @php
                $host      = Auth::user();
                $waLimit   = $host->effectiveWhatsappLimit();
                $smsLimit  = $host->effectiveSmsLimit();
                $emLimit   = $host->effectiveEmailLimit();
                $waSent    = (int)($host->whatsapp_sent_count ?? 0);
                $smsSent   = (int)($host->sms_sent_count     ?? 0);
                $emSent    = (int)($host->email_sent_count   ?? 0);
                $waPct     = $waLimit  > 0 ? min(100, round($waSent  / $waLimit  * 100)) : 0;
                $smsPct    = $smsLimit > 0 ? min(100, round($smsSent / $smsLimit * 100)) : 0;
                $emPct     = $emLimit  > 0 ? min(100, round($emSent  / $emLimit  * 100)) : 0;
            @endphp
            <div style="display: flex; flex-wrap: wrap; gap: 25px; align-items: flex-start;">
                <div>
                    <span id="count-text" style="display: block; font-weight: 800; color: #4f46e5; font-size: 18px;">0
                        Guests Selected</span>
                    <p style="font-size: 12px; color: #64748b; margin-top: 4px;">Update ceremonies &amp; invites</p>
                </div>

                {{-- Per-channel quota display --}}
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    {{-- WhatsApp --}}
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px 14px; min-width: 140px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <div style="font-size: 11px; font-weight: 700; color: #15803d; text-transform: uppercase;">📲 WhatsApp</div>
                            <a href="{{ route('host.addons.index') }}" style="font-size: 10px; font-weight: 700; color: #16a34a; text-decoration: none; background: #dcfce7; padding: 2px 6px; border-radius: 4px;">+ Add</a>
                        </div>
                        @if($waLimit > 0)
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $waSent }} / {{ $waLimit }}</div>
                            <div style="background: #dcfce7; border-radius: 99px; height: 6px; margin-top: 5px; overflow: hidden;">
                                <div style="background: {{ $waPct >= 100 ? '#ef4444' : '#22c55e' }}; width: {{ $waPct }}%; height: 100%; border-radius: 99px;"></div>
                            </div>
                            @if($waSent >= $waLimit)
                                <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">Limit reached</div>
                            @endif
                        @else
                            <div style="font-size: 12px; font-weight: 600; color: #64748b;">Unlimited</div>
                        @endif
                    </div>

                    {{-- SMS --}}
                    <div style="background: #fefce8; border: 1px solid #fde68a; border-radius: 10px; padding: 10px 14px; min-width: 140px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <div style="font-size: 11px; font-weight: 700; color: #a16207; text-transform: uppercase;">💬 SMS</div>
                            <a href="{{ route('host.addons.index') }}" style="font-size: 10px; font-weight: 700; color: #ca8a04; text-decoration: none; background: #fef9c3; padding: 2px 6px; border-radius: 4px;">+ Add</a>
                        </div>
                        @if($smsLimit > 0)
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $smsSent }} / {{ $smsLimit }}</div>
                            <div style="background: #fef9c3; border-radius: 99px; height: 6px; margin-top: 5px; overflow: hidden;">
                                <div style="background: {{ $smsPct >= 100 ? '#ef4444' : '#eab308' }}; width: {{ $smsPct }}%; height: 100%; border-radius: 99px;"></div>
                            </div>
                            @if($smsSent >= $smsLimit)
                                <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">Limit reached</div>
                            @endif
                        @else
                            <div style="font-size: 12px; font-weight: 600; color: #64748b;">Unlimited</div>
                        @endif
                    </div>

                    {{-- Email --}}
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 10px 14px; min-width: 140px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <div style="font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase;">✉️ Email</div>
                            <a href="{{ route('host.addons.index') }}" style="font-size: 10px; font-weight: 700; color: #2563eb; text-decoration: none; background: #dbeafe; padding: 2px 6px; border-radius: 4px;">+ Add</a>
                        </div>
                        @if($emLimit > 0)
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $emSent }} / {{ $emLimit }}</div>
                            <div style="background: #dbeafe; border-radius: 99px; height: 6px; margin-top: 5px; overflow: hidden;">
                                <div style="background: {{ $emPct >= 100 ? '#ef4444' : '#3b82f6' }}; width: {{ $emPct }}%; height: 100%; border-radius: 99px;"></div>
                            </div>
                            @if($emSent >= $emLimit)
                                <div style="font-size: 10px; color: #ef4444; margin-top: 3px; font-weight: 600;">Limit reached</div>
                            @endif
                        @else
                            <div style="font-size: 12px; font-weight: 600; color: #64748b;">Unlimited</div>
                        @endif
                    </div>
                </div>

                {{-- <div style="flex: 1; min-width: 220px;">
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
                </div> --}}

                <div style="flex: 1; min-width: 220px;">
                    <label
                        style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Send
                        Via:</label>
                    <div
                        style="display: flex; flex-direction: column; gap: 6px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">

                        {{-- WhatsApp --}}
                        @php $waFull = $waLimit > 0 && $waSent >= $waLimit; @endphp
                        <label style="font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: {{ $waFull ? 'not-allowed' : 'pointer' }}; opacity: {{ $waFull ? '0.5' : '1' }};">
                            <input type="checkbox" name="channels[]" value="whatsapp" {{ $waFull ? 'disabled' : '' }}
                                style="width:15px;height:15px; cursor: {{ $waFull ? 'not-allowed' : 'pointer' }};">
                            <span style="color: {{ $waFull ? '#94a3b8' : '#1e293b' }};">📲 WhatsApp</span>
                            @if($waFull)
                                <span style="font-size: 10px; background: #fee2e2; color: #ef4444; border-radius: 4px; padding: 1px 6px; font-weight: 700;">Limit reached</span>
                            @endif
                        </label>

                        {{-- SMS --}}
                        @php $smsFull = $smsLimit > 0 && $smsSent >= $smsLimit; @endphp
                        <label style="font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: {{ $smsFull ? 'not-allowed' : 'pointer' }}; opacity: {{ $smsFull ? '0.5' : '1' }};">
                            <input type="checkbox" name="channels[]" value="sms" {{ $smsFull ? 'disabled' : '' }}
                                style="width:15px;height:15px; cursor: {{ $smsFull ? 'not-allowed' : 'pointer' }};">
                            <span style="color: {{ $smsFull ? '#94a3b8' : '#1e293b' }};">💬 SMS</span>
                            @if($smsFull)
                                <span style="font-size: 10px; background: #fee2e2; color: #ef4444; border-radius: 4px; padding: 1px 6px; font-weight: 700;">Limit reached</span>
                            @endif
                        </label>

                        {{-- Email --}}
                        @php $emFull = $emLimit > 0 && $emSent >= $emLimit; @endphp
                        <label style="font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: {{ $emFull ? 'not-allowed' : 'pointer' }}; opacity: {{ $emFull ? '0.5' : '1' }};">
                            <input type="checkbox" name="channels[]" value="email" {{ $emFull ? 'disabled' : '' }}
                                style="width:15px;height:15px; cursor: {{ $emFull ? 'not-allowed' : 'pointer' }};">
                            <span style="color: {{ $emFull ? '#94a3b8' : '#1e293b' }};">✉️ Email</span>
                            @if($emFull)
                                <span style="font-size: 10px; background: #fee2e2; color: #ef4444; border-radius: 4px; padding: 1px 6px; font-weight: 700;">Limit reached</span>
                            @endif
                        </label>
                    </div>
                </div>

                <div style="align-self: flex-end; display: flex; gap: 10px;">
                    <button type="button" onclick="submitSaveDate()"
                        style="background: #10b981; color: white; border: none; padding: 14px 20px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">
                        Send Save the Date
                    </button>
                    <button type="submit"
                        style="background: #4f46e5; color: white; border: none; padding: 14px 20px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                        Send Invitations
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
                        @if($guest->category)
                        <div style="margin-bottom: 6px;">
                            <span style="background: #1e293b; color: #fff; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; margin-right: 4px;">
                                {{ $guest->category->category_name }}
                            </span>
                        </div>
                            @php
                                $ceremonyIdsList = collect($guest->category->ceremony_ids ?? [])->map(function($item) {
                                    return is_array($item) ? ($item['id'] ?? null) : $item;
                                })->filter()->toArray();
                                
                                $ceremoniesData = \App\Models\Ceramonies::whereIn('id', $ceremonyIdsList)->get();
                                $formattedNames = [];
                                
                                foreach($ceremoniesData as $ceremony) {
                                    $groupType = '';
                                    foreach($guest->category->ceremony_ids ?? [] as $cid) {
                                        if(is_array($cid) && isset($cid['id']) && $cid['id'] == $ceremony->id) {
                                            $groupType = ucfirst($cid['group_type'] ?? 'single');
                                            break;
                                        }
                                    }
                                    $formattedNames[] = $ceremony->ceramony_name . ($groupType ? ' (' . $groupType . ')' : '');
                                }
                                $namesStr = implode(', ', $formattedNames);
                            @endphp
                            <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid #c7d2fe; display: inline-block; max-width: 250px; white-space: normal;">
                                {{ $namesStr ?: 'No ceremonies selected' }}
                            </span>
                        @elseif($guest->assigned_ceremonies)
                        <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1px solid #c7d2fe; display: inline-block; max-width: 250px; white-space: normal;">
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
                        @if($guest->save_date_sent)
                        <div style="font-size: 11px; color: #10b981; margin-bottom: 5px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg> Save the Date: Sent
                        </div>
                        @else
                        <div style="font-size: 11px; color: #64748b; margin-bottom: 5px; font-weight: 600;">
                            Save the Date: Pending
                        </div>
                        @endif

                        @if($guest->invitation_sent)
                        <div
                            style="color: #10b981; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div> Invite Sent
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

                        @if($guest->reminder_sent)
                        <div style="font-size: 11px; color: #10b981; margin-top: 5px; font-weight: 600;">
                            Reminder: Sent
                        </div>
                        @elseif($guest->reminder_scheduled)
                        <div style="font-size: 11px; color: #f59e0b; margin-top: 5px; font-weight: 600;">
                            Reminder: Scheduled
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

<!-- Send Reminders Modal -->
<div id="reminderModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 16px; width: 100%; max-width: 450px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700;">Send Reminders</h3>
            <button onclick="closeReminderModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">
            Upload a header image for your automated WhatsApp reminders. The campaign will run daily to keep your guests updated!
        </p>
        <form action="{{ route('host.guestlist.sendReminders') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 10px;">Reminder Image:</label>
                <input type="file" name="reminder_image" required accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #ddd; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 10px;">Select Channels:</label>
                <div style="display: flex; flex-direction: column; gap: 10px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <label style="font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="channels[]" value="whatsapp" style="width: 16px; height: 16px;"> WhatsApp
                    </label>
                    <label style="font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="channels[]" value="sms" style="width: 16px; height: 16px;"> SMS
                    </label>
                    <label style="font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="channels[]" value="email" style="width: 16px; height: 16px;"> Email
                    </label>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="closeReminderModal()" style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #ddd; background: #fff; cursor: pointer;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 12px; border-radius: 10px; border: none; background: #f59e0b; color: white; font-weight: 600; cursor: pointer;">Activate Reminders</button>
            </div>
        </form>
    </div>
</div>

<script>
    const master = document.getElementById('master-checkbox');
    const items = document.querySelectorAll('.guest-item');
    const bulkBar = document.getElementById('bulk-bar');
    const countText = document.getElementById('count-text');

    function openImportModal() {
        document.getElementById('importModal').style.display = 'flex';
    }

    function closeImportModal() {
        document.getElementById('importModal').style.display = 'none';
    }

    function openReminderModal() {
        document.getElementById('reminderModal').style.display = 'flex';
    }

    function closeReminderModal() {
        document.getElementById('reminderModal').style.display = 'none';
    }

    function toggleBar() {
        const checked = document.querySelectorAll('.guest-item:checked').length;
        bulkBar.style.display = checked > 0 ? 'block' : 'none';
        countText.innerText = checked + (checked === 1 ? " Guest Selected" : " Guests Selected");
    }

    master.addEventListener('change', () => {
        items.forEach(i => {
            if (!i.disabled) i.checked = master.checked;
        });
        toggleBar();
    });

    items.forEach(i => i.addEventListener('change', toggleBar));

    function submitSaveDate() {
        const form = document.getElementById('bulk-send-form');
        form.action = "{{ route('host.guestlist.bulkSaveDate') }}";
        form.submit();
    }
</script>
@endsection