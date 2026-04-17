@extends('layouts.host')

@section('content')

<style>
    /* --- Variables --- */
    :root {
        --primary-blue: #2563eb;
        --primary-blue-hover: #1d4ed8;
        --text-dark: #1f2937;
        --text-gray: #6b7280;
        --text-light: #9ca3af;
        --border-color: #d1d5db;
        --bg-white: #ffffff;
        --bg-gray: #f9fafb;
        --error-red: #ef4444;
        --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-blue: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
    }

    /* --- Layout --- */
    .form-wrapper {
        max-width: 672px; /* Approx max-w-2xl */
        margin: 0 auto;
        padding: 2.5rem 1rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .form-card {
        background-color: var(--bg-white);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--shadow);
        border: 1px solid #f3f4f6;
    }

    /* --- Typography --- */
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-top: 0;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 1rem;
    }

    /* --- Form Grid --- */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr; /* Mobile: 1 column */
        gap: 1.5rem;
    }

    /* Tablet/Desktop: 2 columns */
    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .col-full {
        grid-column: 1 / -1; /* Span full width */
    }

    /* --- Inputs & Labels --- */
    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 0.625rem 0.75rem;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        background-color: var(--bg-white);
        transition: border-color 0.2s, box-shadow 0.2s;
        color: var(--text-dark);
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    /* Error State */
    .input-error {
        border-color: var(--error-red) !important;
    }
    
    .error-message {
        color: var(--error-red);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* --- Actions --- */
    .form-actions {
        margin-top: 2rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel {
        color: var(--text-gray);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .btn-cancel:hover {
        color: var(--text-dark);
    }

    .btn-submit {
        background-color: var(--primary-blue);
        color: var(--bg-white);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
        box-shadow: var(--shadow-blue);
    }

    .btn-submit:hover {
        background-color: var(--primary-blue-hover);
    }

    /* Mobile Adjustments */
    @media (max-width: 640px) {
        .form-card {
            padding: 1.5rem;
        }
        .form-actions {
            flex-direction: column-reverse;
            gap: 1rem;
        }
        .btn-submit {
            width: 100%;
        }
    }
</style>

<div class="form-wrapper">
    <div class="form-card">
        <h2 class="form-title">Add New Wedding Guest</h2>

        <form action="{{ route('host.guestlist.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <!-- Ceremony Selection (Full Width) -->
                

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