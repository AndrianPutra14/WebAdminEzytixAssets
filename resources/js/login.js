/**
 * Admin Login - JavaScript
 * Mengelola interaktivitas form login
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============ ELEMENTS ============
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const rememberCheckbox = document.getElementById('remember');
    const forgotPasswordLink = document.querySelector('.forgot-password');
    
    // ============ PASSWORD VISIBILITY TOGGLE ============
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Update icon and aria-label
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                this.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                this.setAttribute('aria-label', 'Tampilkan password');
            }
            
            // Focus back to password field
            passwordInput.focus();
        });
        
        // Accessibility: Toggle with Enter/Space
        togglePasswordBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    }
    
    // ============ FORM VALIDATION ============
    if (loginForm) {
        // Real-time validation
        usernameInput?.addEventListener('input', function() {
            validateUsername(this.value.trim());
        });
        
        passwordInput?.addEventListener('input', function() {
            validatePassword(this.value.trim());
        });
        
        // Form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const isUsernameValid = validateUsername(usernameInput.value.trim());
            const isPasswordValid = validatePassword(passwordInput.value.trim());
            
            if (!isUsernameValid || !isPasswordValid) {
                showError('Mohon perbaiki kesalahan pada form');
                return;
            }
            
            // Submit form (simulated)
            submitLoginForm();
        });
        
        // Enter key to submit
        loginForm.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                // Let the form submission handle it
            }
        });
    }
    
    // ============ VALIDATION FUNCTIONS ============
    function validateUsername(username) {
        const errorElement = document.getElementById('username-error');
        
        if (!username) {
            showFieldError('username', 'Username harus diisi');
            return false;
        }
        
        if (username.length < 3) {
            showFieldError('username', 'Username minimal 3 karakter');
            return false;
        }
        
        if (!/^[a-zA-Z0-9_.-]+$/.test(username)) {
            showFieldError('username', 'Hanya boleh berisi huruf, angka, ., -, _');
            return false;
        }
        
        clearFieldError('username');
        return true;
    }
    
    function validatePassword(password) {
        const errorElement = document.getElementById('password-error');
        
        if (!password) {
            showFieldError('password', 'Password harus diisi');
            return false;
        }
        
        if (password.length < 6) {
            showFieldError('password', 'Password minimal 6 karakter');
            return false;
        }
        
        clearFieldError('password');
        return true;
    }
    
    // ============ ERROR HANDLING ============
    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        
        if (!field || !errorElement) return;
        
        // Add error class to input
        field.classList.add('error');
        
        // Show error message
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorElement.classList.add('show');
    }
    
    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        
        if (field) {
            field.classList.remove('error');
        }
        
        if (errorElement) {
            errorElement.innerHTML = '';
            errorElement.classList.remove('show');
        }
    }
    
    // ============ FORM SUBMISSION ============
    function submitLoginForm() {
        const loginBtn = loginForm.querySelector('.login-btn');
        const originalText = loginBtn.innerHTML;
        
        // Disable form
        disableForm(true);
        
        // Show loading state
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        loginBtn.disabled = true;
        
        // Simulate API call (2 seconds delay)
        setTimeout(() => {
            // Mock authentication
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            
            // Demo credentials (remove in production)
            const demoCredentials = [
                { username: 'admin', password: 'admin123' },
                { username: 'superadmin', password: 'super123' },
                { username: 'administrator', password: 'admin2026' }
            ];
            
            const isValid = demoCredentials.some(cred => 
                cred.username === username && cred.password === password
            );
            
            if (isValid) {
                // Success
                showSuccess('Login berhasil! Mengarahkan ke dashboard...');
                loginBtn.innerHTML = '<i class="fas fa-check"></i> Berhasil!';
                loginBtn.classList.add('success');
                
                // Simulate redirect
                setTimeout(() => {
                    // In real app, uncomment this:
                    // window.location.href = '/dashboard';
                    
                    // For demo: reset form
                    resetForm();
                    loginBtn.innerHTML = originalText;
                    loginBtn.classList.remove('success');
                    disableForm(false);
                    
                    // Show demo message
                    showSuccess('Login simulasi berhasil. Di aplikasi nyata, Anda akan diarahkan ke dashboard.');
                }, 2000);
            } else {
                // Error
                showError('Username atau password salah');
                loginBtn.innerHTML = '<i class="fas fa-times"></i> Gagal';
                loginBtn.classList.add('error');
                
                // Shake form
                loginForm.classList.add('shake');
                setTimeout(() => loginForm.classList.remove('shake'), 500);
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    loginBtn.innerHTML = originalText;
                    loginBtn.classList.remove('error');
                    disableForm(false);
                    
                    // Focus on username field
                    usernameInput.focus();
                    usernameInput.select();
                }, 2000);
            }
        }, 1500);
    }
    
    function disableForm(disabled) {
        const inputs = loginForm.querySelectorAll('input, button');
        inputs.forEach(input => {
            if (input.type !== 'submit') {
                input.disabled = disabled;
            }
        });
        
        if (disabled) {
            loginForm.classList.add('disabled');
        } else {
            loginForm.classList.remove('disabled');
        }
    }
    
    function resetForm() {
        loginForm.reset();
        clearFieldError('username');
        clearFieldError('password');
    }
    
    // ============ NOTIFICATION SYSTEM ============
    function showError(message) {
        showNotification(message, 'error');
    }
    
    function showSuccess(message) {
        showNotification(message, 'success');
    }
    
    function showNotification(message, type) {
        // Remove existing notification
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');
        
        // Icons
        const icons = {
            error: 'exclamation-circle',
            success: 'check-circle',
            info: 'info-circle'
        };
        
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${icons[type] || 'info-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close" aria-label="Tutup notifikasi">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Add styles dynamically
        notification.style.cssText = `
            position: fixed;
            top: 24px;
            right: 24px;
            padding: 16px 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            max-width: 400px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        `;
        
        // Set colors based on type
        if (type === 'error') {
            notification.style.background = 'linear-gradient(135deg, rgba(211, 47, 47, 0.95), rgba(183, 28, 28, 0.95))';
            notification.style.borderLeft = '4px solid #FF5252';
        } else if (type === 'success') {
            notification.style.background = 'linear-gradient(135deg, rgba(76, 175, 80, 0.95), rgba(56, 142, 60, 0.95))';
            notification.style.borderLeft = '4px solid #69F0AE';
        }
        
        // Style content
        const content = notification.querySelector('.notification-content');
        content.style.cssText = `
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        `;
        
        // Close button
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.style.cssText = `
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            cursor: pointer;
            margin-left: 16px;
            font-size: 14px;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        `;
        
        closeBtn.addEventListener('mouseenter', () => {
            closeBtn.style.background = 'rgba(255, 255, 255, 0.2)';
        });
        
        closeBtn.addEventListener('mouseleave', () => {
            closeBtn.style.background = 'rgba(255, 255, 255, 0.1)';
        });
        
        closeBtn.addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });
        
        // Add animations if not exists
        if (!document.getElementById('notification-animations')) {
            const style = document.createElement('style');
            style.id = 'notification-animations';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
                .shake { animation: shake 0.5s ease; }
            `;
            document.head.appendChild(style);
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    // ============ REMEMBER ME ANIMATION ============
    if (rememberCheckbox) {
        rememberCheckbox.addEventListener('change', function() {
            const label = document.querySelector('label[for="remember"]');
            if (this.checked) {
                label.style.color = 'var(--primary-color)';
                label.style.fontWeight = '600';
            } else {
                label.style.color = '';
                label.style.fontWeight = '';
            }
        });
    }
    
    // ============ FORGOT PASSWORD ============
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Simple alert for demo
            const email = prompt('Masukkan email untuk reset password:');
            if (email) {
                if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showSuccess(`Link reset password telah dikirim ke ${email}`);
                } else {
                    showError('Format email tidak valid');
                }
            }
        });
    }
    
    // ============ ENHANCE FORM ACCESSIBILITY ============
    enhanceAccessibility();
    
    function enhanceAccessibility() {
        // Add aria labels
        usernameInput?.setAttribute('aria-label', 'Username');
        passwordInput?.setAttribute('aria-label', 'Password');
        
        // Add aria-describedby for errors
        usernameInput?.setAttribute('aria-describedby', 'username-error');
        passwordInput?.setAttribute('aria-describedby', 'password-error');
        
        // Focus on username on load
        if (usernameInput && !usernameInput.value) {
            setTimeout(() => usernameInput.focus(), 100);
        }
    }
});