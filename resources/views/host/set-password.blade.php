<style>
    /* ===== CSS VARIABLES & WEDDING THEME ===== */
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
        
        /* Password strength colors */
        --strength-weak: #ef4444;
        --strength-fair: #f97316;
        --strength-good: #22c55e;
        --strength-strong: #16a34a;
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
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,107,107,0.05) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
        animation: float 20s ease-in-out infinite;
    }
    .auth-page-wrapper::before { top: -160px; right: -100px; animation-delay: 0s; }
    .auth-page-wrapper::after { bottom: -140px; left: -120px; animation-delay: -10s; }

    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-25px) scale(1.08); }
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
        width: 64px;
        height: 64px;
        background: var(--brand-primary-light);
        border-radius: 50%;
        margin-bottom: 16px;
        font-size: 30px;
        color: var(--brand-primary);
        animation: pulse 2.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.35); }
        50% { box-shadow: 0 0 0 14px rgba(255, 107, 107, 0); }
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

    /* ===== FORM GROUPS ===== */
    .form-group {
        margin-bottom: clamp(16px, 4vw, 24px);
        position: relative;
    }

    .form-label {
        display: block;
        font-size: clamp(13px, 3.5vw, 14px);
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: clamp(6px, 2vw, 8px);
        letter-spacing: 0.01em;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .form-label .hint {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.9em;
    }

    /* ===== PASSWORD INPUT WITH TOGGLE ===== */
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input {
        width: 100%;
        box-sizing: border-box;
        padding: clamp(12px, 3.5vw, 14px) clamp(14px, 4vw, 44px) clamp(12px, 3.5vw, 14px) clamp(14px, 4vw, 16px);
        font-size: clamp(14px, 4vw, 15px);
        border: 1.5px solid #e5e0e2;
        border-radius: var(--radius-sm);
        outline: none;
        background: #fff;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast), transform var(--transition-fast);
        color: var(--text-primary);
        padding-right: 44px;
    }

    .form-input::placeholder {
        color: var(--text-muted);
        opacity: 1;
    }

    .form-input:focus {
        border-color: var(--border-focus);
        box-shadow: var(--focus-ring);
        transform: translateY(-1px);
    }

    .form-input:hover:not(:focus) {
        border-color: #d1c9cb;
    }

    .form-input.error {
        border-color: #ef4444;
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    /* Password visibility toggle button */
    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        transition: all var(--transition-fast);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        z-index: 2;
    }

    .toggle-password:hover {
        color: var(--brand-primary);
        background: var(--brand-primary-light);
    }

    .toggle-password:focus {
        outline: 2px solid var(--brand-primary);
        outline-offset: 2px;
    }

    .toggle-password svg {
        width: 18px;
        height: 18px;
        transition: transform var(--transition-fast);
    }

    .toggle-password.active svg {
        transform: scale(1.1);
    }

    /* ===== PASSWORD STRENGTH INDICATOR ===== */
    .strength-meter {
        margin-top: clamp(8px, 2vw, 10px);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .strength-bar {
        height: 4px;
        background: #eee;
        border-radius: 2px;
        overflow: hidden;
        position: relative;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        background: var(--strength-weak);
        border-radius: 2px;
        transition: width var(--transition-normal), background-color var(--transition-normal);
    }

    .strength-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .strength-label.weak { color: var(--strength-weak); }
    .strength-label.fair { color: var(--strength-fair); }
    .strength-label.good { color: var(--strength-good); }
    .strength-label.strong { color: var(--strength-strong); }

    .strength-requirements {
        margin-top: clamp(8px, 2vw, 12px);
        padding: clamp(10px, 3vw, 12px);
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        font-size: clamp(11px, 3vw, 12px);
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .strength-requirements ul {
        margin: 6px 0 0 0;
        padding-left: 18px;
        list-style: none;
    }

    .strength-requirements li {
        margin: 4px 0;
        position: relative;
        padding-left: 16px;
    }

    .strength-requirements li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: var(--brand-primary);
        font-weight: bold;
    }

    .strength-requirements li.met {
        color: var(--strength-strong);
    }
    .strength-requirements li.met::before {
        content: "✓";
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
        .auth-card {
            padding: clamp(24px, 5vw, 36px);
        }
    }

    /* Mobile: 480px and below */
    @media (max-width: 480px) {
        .auth-card {
            padding: clamp(20px, 5vw, 32px);
            border-radius: var(--radius-md);
        }
        
        .form-input {
            padding: clamp(12px, 4vw, 14px) clamp(14px, 5vw, 44px) clamp(12px, 4vw, 14px) clamp(14px, 5vw, 16px);
            font-size: clamp(14px, 4vw, 15px);
        }
        
        .auth-subtitle {
            font-size: clamp(13px, 4vw, 14px);
        }
        
        .strength-requirements {
            font-size: clamp(11px, 3.5vw, 12px);
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
    }

    /* ===== ACCESSIBILITY & PREFERENCES ===== */
    @media (prefers-contrast: high) {
        :root {
            --border-color: #888;
            --text-secondary: #222;
        }
        .form-input:focus {
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
    .form-input:focus-visible,
    .toggle-password:focus-visible,
    .submit-button:focus-visible {
        outline: 2px solid var(--brand-primary);
        outline-offset: 2px;
    }

    /* Match password indicator */
    .match-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 12px;
        font-weight: 500;
    }
    .match-indicator.matching { color: var(--strength-strong); }
    .match-indicator.not-matching { color: var(--strength-weak); }
    .match-indicator svg {
        width: 14px;
        height: 14px;
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo" aria-hidden="true">🔐</div>
            <h2 class="auth-title">Secure Your Account</h2>
            <p class="auth-subtitle">Create a strong password to protect your wedding account. You'll use this to log in next time.</p>
        </div>

        {{-- Alert Notification Banner --}}
        <div id="alert-container" class="alert-banner" role="alert" aria-live="polite"></div>

        <form id="set-password-form" action="{{ route('host.set-password.submit') }}" method="POST" novalidate>
            @csrf

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <span>New Password</span>
                    <span class="hint">Required</span>
                </label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="password"
                        name="password"
                        placeholder="Create a strong password"
                        class="form-input"
                        required
                        minlength="8"
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}"
                        autocomplete="new-password"
                        aria-describedby="password-hint password-strength"
                    >
                    <button type="button" class="toggle-password" id="toggle-password" aria-label="Toggle password visibility">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Password Strength Meter -->
                <div class="strength-meter" id="password-strength" aria-live="polite">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strength-fill"></div>
                    </div>
                    <div class="strength-label" id="strength-label">
                        <span>Strength: </span><strong id="strength-text">Start typing...</strong>
                    </div>
                </div>
                
                <!-- Password Requirements -->
                <div class="strength-requirements" id="password-hint">
                    <strong>Password must include:</strong>
                    <ul>
                        <li id="req-length">At least 8 characters</li>
                        <li id="req-lower">One lowercase letter</li>
                        <li id="req-upper">One uppercase letter</li>
                        <li id="req-number">One number</li>
                        <li id="req-special">One special character (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <span>Confirm Password</span>
                    <span class="hint">Required</span>
                </label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        aria-describedby="match-hint"
                    >
                    <button type="button" class="toggle-password" id="toggle-confirm" aria-label="Toggle password visibility">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Match Indicator -->
                <div class="match-indicator" id="match-hint" aria-live="polite">
                    <svg id="match-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span id="match-text"></span>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submit-btn" class="submit-button">
                <span id="btn-text">Set Password & Continue</span>
            </button>
        </form>

        
    </div>
</div>
