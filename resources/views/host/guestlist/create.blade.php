@extends('layouts.host')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hostguestlist.css') }}">

<div class="form-wrapper">
    <div class="form-card">
        <h2 class="form-title">Add New Wedding Guest</h2>

        @if(session('error'))
            <div style="background-color: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('host.guestlist.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <!-- Category Selection -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Assign Category (Auto-assigns ceremonies)</label>
                    <select name="category_id" class="form-select">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }} ({{ ucfirst($cat->group_type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Guest Name -->
                <div class="form-group">
                    <label class="form-label">Guest Full Name *</label>
                    <input type="text" name="guest_name" required value="{{ old('guest_name') }}" class="form-input @error('guest_name') input-error @enderror">
                    @error('guest_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- WhatsApp/Mobile Number -->
                <div class="form-group">
                    <label class="form-label">WhatsApp/Mobile Number *</label>
                    <input type="text" name="guest_number" required value="{{ old('guest_number') }}" class="form-input @error('guest_number') input-error @enderror">
                    @error('guest_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Relation -->
                <div class="form-group">
                    <label class="form-label">Relation</label>
                    <select name="relation" class="form-select">
                        <option value="">Select...</option>
                        <option value="bride" {{ old('relation') == 'bride' ? 'selected' : '' }}>Bride Side</option>
                        <option value="groom" {{ old('relation') == 'groom' ? 'selected' : '' }}>Groom Side</option>
                        <option value="bride_parent" {{ old('relation') == 'bride_parent' ? 'selected' : '' }}>Bride Parent</option>
                        <option value="groom_parent" {{ old('relation') == 'groom_parent' ? 'selected' : '' }}>Groom Parent</option>
                    </select>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('host.guestlist.index') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    Save Guest
                </button>
            </div>
        </form>
    </div>
</div>

@endsection