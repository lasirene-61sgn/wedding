<style>
    .auth-page-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9fafb;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        padding: 20px;
    }

    .auth-card {
        max-w: 400px;
        width: 100%;
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .auth-title {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .auth-subtitle {
        font-size: 14px;
        color: #4b5563;
        margin: 0;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        font-size: 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-input:focus {
        border-color: #ff4757;
        box-shadow: 0 0 0 3px rgba(255, 71, 87, 0.15);
    }

    .submit-button {
        width: 100%;
        background-color: #ff4757;
        color: #ffffff;
        border: none;
        padding: 12px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        margin-top: 10px;
    }

    .submit-button:hover {
        background-color: #e03d4b;
    }

    .alert-banner {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        display: none;
    }
    
    .alert-success { display: block; background-color: #def7ec; color: #03543f; border: 1px solid #bfecdc; }
    .alert-error { display: block; background-color: #fde8e8; color: #9b1c1c; border: 1px solid #fabdbe; }
</style>
<div class="auth-page-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Set New Password</h2>
            <p class="auth-subtitle">Please choose a strong password to secure your host portal account.</p>
        </div>

        {{-- Added inline block display handling initialization --}}
        <div id="alert-container" class="alert-banner" role="alert" aria-live="polite" style="display: none;"></div>

        <form id="password-reset-form" action="{{ route('host.password.reset.submit') }}" method="POST">
            @csrf
            
            {{-- Pass the secure validation token along silently --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <input id="password" name="password" type="password" required minlength="8"
                    class="form-input" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                    class="form-input" placeholder="••••••••">
            </div>

            <button type="submit" id="submit-btn" class="submit-button">
                <span id="btn-text">Update Password</span>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('password-reset-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const alertContainer = document.getElementById('alert-container');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const pass = document.getElementById('password').value;
            const conf = document.getElementById('password_confirmation').value;
            
            if (pass !== conf) {
                alertContainer.innerText = "Passwords do not match.";
                alertContainer.className = "alert-banner alert-error";
                alertContainer.style.display = "block";
                return;
            }

            submitBtn.disabled = true;
            btnText.innerText = "Updating Password...";
            alertContainer.style.display = "none";
            alertContainer.className = "alert-banner";

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json' // Explicitly tell Laravel to return JSON errors
                }
            })
            .then(async response => {
                const data = await response.json();

                // Intercept 422 errors and structural framework validation anomalies
                if (!response.ok) {
                    let errorMessage = data.message || 'Reset execution failed.';
                    
                    // If Laravel returned specific field validation rules, stitch them together
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(' ');
                    }
                    throw new Error(errorMessage);
                }
                return data;
            })
            .then(data => {
                if (data.status === 'success' || data.success === true) {
                    alertContainer.innerText = data.message || "Password updated successfully!";
                    alertContainer.className = "alert-banner alert-success";
                    alertContainer.style.display = "block";
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('host.login') }}";
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Reset execution failed.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                btnText.innerText = "Update Password";
                alertContainer.innerText = error.message;
                alertContainer.className = "alert-banner alert-error";
                alertContainer.style.display = "block";
            });
        });
    });
</script>
