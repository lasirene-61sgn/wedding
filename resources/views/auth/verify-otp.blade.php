<style>
    /* ===== CSS VARIABLES & THEME ===== */
    :root {
        --brand-primary: #ff6b6b;
        --brand-primary-dark: #ee5a5a;
        --brand-primary-light: #fff0f0;
        --brand-accent: #ffd3b6;
        --text-primary: #1a1a1a;
        --text-secondary: #555555;
        --text-muted: #777777;
        --bg-page: linear-gradient(135deg, #fff5f7 0%, #fef9fb 50%, #fff9fa 100%);
        --bg-card: #ffffff;
        --border-color: #eee5e7;
        --border-focus: #ff6b6b;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --transition-fast: 150ms ease;
        --transition-normal: 250ms ease;
        --focus-ring: 0 0 0 3px rgba(255, 107, 107, 0.25);
        --otp-gap: 8px;
        --otp-size: clamp(48px, 12vw, 56px);
    }

    /* ===== PAGE WRAPPER ===== */
    .auth-page-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-page);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        padding: clamp(16px, 4vw, 32px);
        position: relative;
        overflow: hidden;
    }

    /* Subtle romantic decorative elements */
    .auth-page-wrapper::before,
    .auth-page-wrapper::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,107,107,0.06) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
        animation: float 18s ease-in-out infinite;
    }
    .auth-page-wrapper::before { top: -140px; right: -80px; animation-delay: 0s; }
    .auth-page-wrapper::after { bottom: -120px; left: -100px; animation-delay: -9s; }

    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.05); }
    }

    /* ===== AUTH CARD ===== */
    .auth-card {
        width: 100%;
        max-width: 440px;
        background: var(--bg-card);
        padding: clamp(24px, 6vw, 40px);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        position: relative;
        z-index: 1;
        animation: cardFadeIn 0.4s ease-out;
    }

    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ===== HEADER SECTION ===== */
    .auth-header {
        text-align: center;
        margin-bottom: clamp(20px, 5vw, 32px);
        padding-bottom: clamp(16px, 4vw, 24px);
        border-bottom: 1px dashed var(--border-color);
    }

    .auth-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: var(--brand-primary-light);
        border-radius: 50%;
        margin-bottom: 16px;
        font-size: 28px;
        color: var(--brand-primary);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.3); }
        50% { box-shadow: 0 0 0 12px rgba(255, 107, 107, 0); }
    }

    .auth-title {
        font-size: clamp(20px, 5vw, 26px);
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }

    .auth-subtitle {
        font-size: clamp(13px, 3.5vw, 15px);
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.6;
        max-width: 340px;
        margin-left: auto;
        margin-right: auto;
    }

    .auth-subtitle strong {
        color: var(--brand-primary);
        font-weight: 600;
        word-break: break-all;
    }

    /* ===== ALERT BANNER ===== */
    .alert-banner {
        padding: clamp(10px, 3vw, 14px) clamp(14px, 4vw, 16px);
        border-radius: var(--radius-sm);
        font-size: clamp(13px, 3.5vw, 14px);
        font-weight: 500;
        margin-bottom: clamp(16px, 4vw, 24px);
        display: none;
        animation: slideDown 0.3s ease;
        text-align: center;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        display: block;
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .alert-error {
        display: block;
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* ===== OTP INPUT CONTAINER ===== */
    .otp-container {
        display: flex;
        justify-content: center;
        gap: var(--otp-gap);
        margin: clamp(16px, 4vw, 24px) 0;
        flex-wrap: wrap;
    }

    .otp-input {
        width: var(--otp-size);
        height: var(--otp-size);
        font-size: clamp(20px, 5vw, 28px);
        font-weight: 700;
        text-align: center;
        border: 2px solid #e5e0e2;
        border-radius: var(--radius-md);
        background: #fff;
        color: var(--text-primary);
        outline: none;
        transition: all var(--transition-fast);
        caret-color: var(--brand-primary);
        -moz-appearance: textfield;
    }

    .otp-input::-webkit-outer-spin-button,
    .otp-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .otp-input:focus {
        border-color: var(--border-focus);
        box-shadow: var(--focus-ring);
        transform: translateY(-2px) scale(1.03);
        z-index: 2;
    }

    .otp-input.filled {
        border-color: var(--brand-primary);
        background: var(--brand-primary-light);
        color: var(--brand-primary-dark);
    }

    .otp-input.error {
        border-color: #ef4444;
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }

    /* ===== FORM GROUP & LABEL ===== */
    .form-group {
        margin-bottom: clamp(16px, 4vw, 24px);
    }

    .form-label {
        display: block;
        font-size: clamp(13px, 3.5vw, 14px);
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: clamp(6px, 2vw, 8px);
        text-align: center;
        letter-spacing: 0.01em;
    }

    .form-hint {
        text-align: center;
        font-size: clamp(12px, 3vw, 13px);
        color: var(--text-muted);
        margin-top: 8px;
        line-height: 1.4;
    }

    /* ===== SUBMIT BUTTON ===== */
    .submit-button {
        width: 100%;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-dark));
        color: #ffffff;
        border: none;
        padding: clamp(14px, 4vw, 16px);
        font-size: clamp(14px, 4vw, 16px);
        font-weight: 700;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-normal);
        margin-top: clamp(8px, 2vw, 12px);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(255, 107, 107, 0.35);
        letter-spacing: 0.01em;
    }

    .submit-button::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(255,255,255,0.25), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .submit-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.45);
        background: linear-gradient(135deg, var(--brand-primary-dark), #e04545);
    }

    .submit-button:hover:not(:disabled)::after {
        transform: translateX(100%);
    }

    .submit-button:active:not(:disabled) {
        transform: translateY(0);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Loading state */
    .submit-button.loading {
        pointer-events: none;
        color: transparent !important;
    }

    .submit-button.loading::after {
        content: "";
        position: absolute;
        width: 22px;
        height: 22px;
        top: 50%;
        left: 50%;
        margin: -11px;
        border: 2.5px solid rgba(255,255,255,0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== RESEND SECTION ===== */
    .resend-section {
        text-align: center;
        margin-top: clamp(16px, 4vw, 24px);
        padding-top: clamp(16px, 4vw, 24px);
        border-top: 1px dashed var(--border-color);
    }

    .resend-text {
        font-size: clamp(13px, 3.5vw, 14px);
        color: var(--text-secondary);
        margin-bottom: 10px;
    }

    .resend-button {
        background: none;
        border: none;
        color: var(--brand-primary);
        font-weight: 600;
        font-size: clamp(13px, 3.5vw, 14px);
        cursor: pointer;
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        transition: all var(--transition-fast);
        position: relative;
    }

    .resend-button:hover:not(:disabled) {
        color: var(--brand-primary-dark);
        background: var(--brand-primary-light);
    }

    .resend-button:disabled {
        color: var(--text-muted);
        cursor: not-allowed;
    }

    .resend-button .timer {
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* ===== FOOTER LINK ===== */
    .auth-footer {
        text-align: center;
        margin-top: clamp(20px, 5vw, 32px);
        padding-top: clamp(16px, 4vw, 24px);
        border-top: 1px dashed var(--border-color);
    }

    .auth-footer a {
        color: var(--brand-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: clamp(13px, 3.5vw, 14px);
        transition: color var(--transition-fast);
        position: relative;
        padding-bottom: 2px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .auth-footer a::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 1.5px;
        background: var(--brand-primary);
        transition: width var(--transition-fast);
    }

    .auth-footer a:hover {
        color: var(--brand-primary-dark);
    }

    .auth-footer a:hover::after {
        width: 100%;
    }

    /* ===== RESPONSIVE BREAKPOINTS ===== */
    
    /* Tablet: 768px and below */
    @media (max-width: 768px) {
        :root {
            --otp-gap: 6px;
            --otp-size: clamp(44px, 11vw, 52px);
        }
        
        .otp-container {
            gap: var(--otp-gap);
        }
    }

    /* Mobile: 480px and below */
    @media (max-width: 480px) {
        .auth-card {
            padding: clamp(20px, 5vw, 32px);
            border-radius: var(--radius-md);
        }
        
        :root {
            --otp-gap: 4px;
            --otp-size: clamp(40px, 10vw, 48px);
        }
        
        .otp-container {
            gap: var(--otp-gap);
            flex-wrap: wrap;
        }
        
        .otp-input {
            width: calc((100% - (var(--otp-gap) * 5)) / 6);
            min-width: 36px;
        }
        
        .auth-subtitle {
            font-size: clamp(13px, 4vw, 14px);
        }
        
        .resend-section,
        .auth-footer {
            font-size: clamp(13px, 4vw, 14px);
        }
    }

    /* Large Desktop: 1440px and above */
    @media (min-width: 1440px) {
        .auth-card {
            max-width: 480px;
            padding: 48px;
        }
        
        .auth-title {
            font-size: 28px;
        }
        
        :root {
            --otp-size: 60px;
        }
    }

    /* ===== ACCESSIBILITY & PREFERENCES ===== */
    @media (prefers-contrast: high) {
        :root {
            --border-color: #888;
            --text-secondary: #222;
        }
        .otp-input:focus {
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.5);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        .auth-page-wrapper::before,
        .auth-page-wrapper::after {
            display: none;
        }
    }

    /* Focus visible for keyboard users */
    .otp-input:focus-visible,
    .resend-button:focus-visible,
    .submit-button:focus-visible {
        outline: 2px solid var(--brand-primary);
        outline-offset: 2px;
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo" aria-hidden="true">✨</div>
            <h2 class="auth-title">Verify Your OTP</h2>
            <p class="auth-subtitle">
                A secure 6‑digit code has been sent to:<br>
                <strong>{{ $identifier ?? 'your contact' }}</strong>
            </p>
        </div>

        {{-- Alert Notification Banner --}}
        <div id="alert-container" class="alert-banner" role="alert" aria-live="polite"></div>

        <form id="otp-verify-form" action="{{ route('host.password.verify.submit') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="identifier" value="{{ $identifier ?? '' }}">
            <input type="hidden" name="channel" value="{{ $channel ?? 'email' }}">

            <div class="form-group">
                <label for="otp-input-1" class="form-label">Enter 6‑Digit Secure Code</label>
                
                <div class="otp-container" role="group" aria-label="One-time password input">
                    @for($i = 1; $i <= 6; $i++)
                        <input 
                            type="text" 
                            id="otp-input-{{ $i }}"
                            class="otp-input"
                            maxlength="1"
                            pattern="[0-9]"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            aria-label="Digit {{ $i }} of 6"
                            data-index="{{ $i }}"
                        >
                    @endfor
                </div>
                
                <p class="form-hint">
                    <span aria-hidden="true">🔐</span> 
                    Code expires in <strong>5 minutes</strong>. Don't share it with anyone.
                </p>
            </div>

            <button type="submit" id="submit-btn" class="submit-button">
                <span id="btn-text">Validate & Verify Code</span>
            </button>
        </form>

        {{-- Resend OTP Section --}}
        <div class="resend-section">
            <p class="resend-text">Didn't receive the code?</p>
            <button 
                type="button" 
                id="resend-btn" 
                class="resend-button"
                aria-live="polite"
            >
                <span id="resend-text">Resend Code</span>
                <span id="resend-timer" class="timer" hidden>(<span id="timer-count">60</span>s)</span>
            </button>
        </div>

        
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('otp-verify-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const alertContainer = document.getElementById('alert-container');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            submitBtn.disabled = true;
            btnText.innerText = "Verifying Code...";
            alertContainer.style.display = "none";
            alertContainer.className = "alert-banner";

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alertContainer.innerText = data.message;
                    alertContainer.classList.add('alert-success');
                    
                    // Redirect to the Reset Password interface, passing the validated session token
                    setTimeout(() => {
                        window.location.href = "{{ url('host/forgot-password/reset') }}?token=" + data.token;
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Verification failed.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                btnText.innerText = "Validate & Verify Code";
                alertContainer.innerText = error.message;
                alertContainer.classList.add('alert-error');
            });
        });
    });
</script>
