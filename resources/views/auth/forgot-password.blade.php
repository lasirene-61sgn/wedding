<style>
    /* ===== BASE RESET & VARIABLES ===== */
    :root {
        --brand-primary: #ff6b6b;
        --brand-primary-dark: #ee5a5a;
        --brand-primary-light: #fff0f0;
        --text-primary: #1a1a1a;
        --text-secondary: #555555;
        --text-muted: #777777;
        --bg-page: #fff9fa;
        --bg-card: #ffffff;
        --border-color: #eee5e7;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --transition-fast: 150ms ease;
        --transition-normal: 250ms ease;
        --focus-ring: 0 0 0 3px rgba(255, 107, 107, 0.25);
    }

    /* ===== PAGE WRAPPER ===== */
    .auth-page-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fff5f7 0%, #fef9fb 50%, #fff9fa 100%);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        padding: clamp(16px, 4vw, 32px);
        position: relative;
        overflow: hidden;
    }

    /* Subtle decorative elements for wedding theme */
    .auth-page-wrapper::before,
    .auth-page-wrapper::after {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,107,107,0.08) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
    }
    .auth-page-wrapper::before { top: -150px; right: -100px; }
    .auth-page-wrapper::after { bottom: -100px; left: -150px; }

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
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
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
        width: 56px;
        height: 56px;
        background: var(--brand-primary-light);
        border-radius: 50%;
        margin-bottom: 16px;
        font-size: 24px;
        color: var(--brand-primary);
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
        line-height: 1.5;
        max-width: 320px;
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
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
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
    }

    .form-label {
        display: block;
        font-size: clamp(13px, 3.5vw, 14px);
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: clamp(6px, 2vw, 8px);
        letter-spacing: 0.01em;
    }

    .form-input {
        width: 100%;
        box-sizing: border-box;
        padding: clamp(12px, 3.5vw, 14px) clamp(14px, 4vw, 16px);
        font-size: clamp(14px, 4vw, 15px);
        border: 1.5px solid #e5e0e2;
        border-radius: var(--radius-sm);
        outline: none;
        background: #fff;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast), transform var(--transition-fast);
        color: var(--text-primary);
    }

    .form-input::placeholder {
        color: var(--text-muted);
        opacity: 1;
    }

    .form-input:focus {
        border-color: var(--brand-primary);
        box-shadow: var(--focus-ring);
        transform: translateY(-1px);
    }

    .form-input:hover:not(:focus) {
        border-color: #d1c9cb;
    }

    /* ===== CHANNEL SELECTION GRID ===== */
    .channel-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: clamp(8px, 2.5vw, 12px);
    }

    .option-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: clamp(12px, 3.5vw, 16px) clamp(8px, 2vw, 12px);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        cursor: pointer;
        background-color: #fff;
        transition: all var(--transition-normal);
        font-size: clamp(12px, 3.5vw, 14px);
        font-weight: 600;
        color: var(--text-secondary);
        text-align: center;
        user-select: none;
        min-height: clamp(56px, 14vw, 72px);
        position: relative;
        overflow: hidden;
    }

    .option-label::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--brand-primary-light), transparent 60%);
        opacity: 0;
        transition: opacity var(--transition-fast);
        z-index: 0;
        border-radius: inherit;
    }

    .option-label:hover {
        border-color: var(--brand-primary);
        color: var(--brand-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .option-label:hover::before {
        opacity: 1;
    }

    .option-label span {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Optional: Add icons for channels */
    .option-label span::before {
        content: "";
        display: inline-block;
        width: 18px;
        height: 18px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.7;
        transition: opacity var(--transition-fast);
    }
    .option-label input[value="email"] ~ span::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ff6b6b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'/%3E%3C/svg%3E");
    }
    .option-label input[value="whatsapp"] ~ span::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2325D366'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.298-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.573-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z'/%3E%3C/svg%3E");
    }
    .option-label input[value="sms"] ~ span::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%233b82f6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'/%3E%3C/svg%3E");
    }

    /* Active state for selected channel */
    .option-label.active-channel,
    .option-label:has(input[type="radio"]:checked) {
        border-color: var(--brand-primary) !important;
        background-color: var(--brand-primary-light) !important;
        color: var(--brand-primary) !important;
        font-weight: 700;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .option-label.active-channel::before,
    .option-label:has(input[type="radio"]:checked)::before {
        opacity: 1;
    }

    .option-label.active-channel span::before,
    .option-label:has(input[type="radio"]:checked) span::before {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Screen Reader Only Utility */
    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
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
        background: linear-gradient(135deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.45);
        background: linear-gradient(135deg, var(--brand-primary-dark), #e04545);
    }

    .submit-button:hover::after {
        transform: translateX(100%);
    }

    .submit-button:active {
        transform: translateY(0);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ===== LOADING STATE ===== */
    .submit-button.loading {
        pointer-events: none;
        position: relative;
        color: transparent !important;
    }

    .submit-button.loading::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin: -10px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== FOOTER LINKS ===== */
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
        .channel-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(10px, 3vw, 14px);
        }
        
        .option-label {
            min-height: 64px;
            padding: 14px 10px;
        }
    }

    /* Mobile: 480px and below */
    @media (max-width: 480px) {
        .auth-card {
            padding: clamp(20px, 5vw, 32px);
            border-radius: var(--radius-md);
        }
        
        .channel-grid {
            grid-template-columns: 1fr;
            gap: clamp(10px, 3vw, 12px);
        }
        
        .option-label {
            flex-direction: row;
            justify-content: flex-start;
            padding: clamp(12px, 4vw, 14px) clamp(16px, 5vw, 20px);
            min-height: auto;
            text-align: left;
            border-radius: var(--radius-sm);
        }
        
        .option-label span {
            gap: 10px;
            font-size: clamp(14px, 4vw, 15px);
        }
        
        .option-label span::before {
            width: 20px;
            height: 20px;
        }
        
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
    }

    /* ===== HIGH CONTRAST / ACCESSIBILITY ===== */
    @media (prefers-contrast: high) {
        :root {
            --border-color: #999;
            --text-secondary: #333;
        }
        .form-input:focus {
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.5);
        }
    }

    /* ===== REDUCED MOTION ===== */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">💒</div>
            <h2 class="auth-title">Reset Your Password</h2>
            <p class="auth-subtitle">Enter your email or phone to receive a secure 6‑digit verification code.</p>
        </div>

        {{-- Alert Notification Banner container --}}
        <div id="alert-container" class="alert-banner" role="alert" aria-live="polite"></div>

        <form id="otp-request-form" action="{{ route('host.password.otp.send') }}" method="POST" novalidate>
            @csrf
            
            {{-- Identifier Target Field --}}
            <div class="form-group">
                <label for="identifier" class="form-label">Email Address or Mobile Number</label>
                <input 
                    id="identifier" 
                    name="identifier" 
                    type="text" 
                    required 
                    class="form-input" 
                    placeholder="e.g., alex@example.com or +919876543210"
                    autocomplete="username"
                    inputmode="email"
                    aria-describedby="identifier-help"
                >
                <small id="identifier-help" class="sr-only">Enter the email or phone number associated with your account</small>
            </div>

            {{-- Communication Channel Selection Field --}}
            <div class="form-group">
                <label class="form-label" id="channel-label">Receive OTP via:</label>
                <div class="channel-grid" role="radiogroup" aria-labelledby="channel-label">
                    <label class="option-label" tabindex="0">
                        <input type="radio" name="channel" value="email" checked class="sr-only">
                        <span>Email</span>
                    </label>

                    <label class="option-label" tabindex="0">
                        <input type="radio" name="channel" value="whatsapp" class="sr-only">
                        <span>WhatsApp</span>
                    </label>

                    <!-- <label class="option-label" tabindex="0">
                        <input type="radio" name="channel" value="sms" class="sr-only">
                        <span>SMS</span>
                    </label> -->
                </div>
            </div>

            <button type="submit" id="submit-btn" class="submit-button">
                <span id="btn-text">Generate OTP</span>
            </button>
        </form>

        
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = document.querySelectorAll('.option-label');

        function updateSelection(chosenLabel) {
            labels.forEach(l => l.classList.remove('active-channel'));
            chosenLabel.classList.add('active-channel');
            
            // Find the native hidden input radio inside this label container and check it
            const radioInput = chosenLabel.querySelector('input[type="radio"]');
            if(radioInput) radioInput.checked = true;
        }

        labels.forEach(label => {
            label.addEventListener('click', function(e) {
                updateSelection(this);
            });
        });

        // Initialize display context by ensuring the default checked element looks active on load
        const defaultChecked = document.querySelector('.option-label input[checked]');
        if (defaultChecked) {
            updateSelection(defaultChecked.closest('.option-label'));
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = document.querySelectorAll('.option-label');
        const form = document.getElementById('otp-request-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const alertContainer = document.getElementById('alert-container');

        // Handle dynamic CSS selection visual toggle for channels
        function updateSelection(chosenLabel) {
            labels.forEach(l => l.classList.remove('active-channel'));
            chosenLabel.classList.add('active-channel');
            const radioInput = chosenLabel.querySelector('input[type="radio"]');
            if(radioInput) radioInput.checked = true;
        }

        labels.forEach(label => {
            label.addEventListener('click', function() {
                updateSelection(this);
            });
        });

        // Handle Form Submission via AJAX Fetch
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop normal page reload

            // UI Feedback: Disable button and show loading state
            submitBtn.disabled = true;
            btnText.innerText = "Sending OTP...";
            alertContainer.className = "alert-banner"; // Reset classes
            alertContainer.style.display = "none";

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Tells Laravel this is an AJAX call
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alertContainer.innerText = data.message;
                    alertContainer.classList.add('alert-success');
                    
                    // Senior UI Redirect Flow: 
                    // Move user to the OTP entry validation view after 2 seconds
                    setTimeout(() => {
                        window.location.href = "{{ route('host.password.verify.view') }}?identifier=" + encodeURIComponent(document.getElementById('identifier').value);
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Something went wrong.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                btnText.innerText = "Generate OTP";
                alertContainer.innerText = error.message;
                alertContainer.classList.add('alert-error');
            });
        });
    });
</script>
