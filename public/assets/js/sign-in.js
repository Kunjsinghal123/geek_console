document.addEventListener('DOMContentLoaded', function () {

    const csrf = document.querySelector('input[name="_token"]').value;
    const emailInput = document.getElementById('signin-email');
    const emailErrorDiv = document.getElementById('email-error');
    const emailSuccessDiv = document.getElementById('email-success');
    const otpErrorDiv = document.getElementById('otp-error');
    const otpSuccessDiv = document.getElementById('otp-success');
    const sendBtn = document.getElementById('send-otp-btn');
    const verifyBtn = document.getElementById('verify-otp-btn');
    const otpSection = document.getElementById('otp-section');
    const otpInputs = document.querySelectorAll('.otp');

    // ======================
    // OTP INPUT UX
    // ======================

    otpInputs.forEach((input, index) => {

        // Move forward
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            
            if (input.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            clearOtpValidation();
        });

        // Backspace support
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
    });

    // ======================
    // PASTE OTP SUPPORT
    // ======================
    
    otpInputs[0].addEventListener('paste', (e) => {
        
        const paste = e.clipboardData.getData('text').trim();

        if (/^\d{6}$/.test(paste)) {
            otpInputs.forEach((input, i) => {
                input.value = paste[i] || '';
            });

            otpInputs[5].focus();
            verifyBtn.click();
        }
    });   

    // ======================
    // SEND OTP
    // ======================

    sendBtn.addEventListener('click', () => {

        if (sendBtn.disabled) return;
        
        const email = emailInput.value.trim();

        if (!email) return showEmailError('Please provide a valid Store Email.');
        if (!validateEmail(email)) return showEmailError('Invalid email format');

        clearEmailValidation();
        clearOtpValidation();
        sendOtp(email);

    });

    function sendOtp(email) {

        setLoading(sendBtn, true, 'Sending...');

        fetch('/send-otp', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email })
        })
        .then(async (res) => {
            const data = await res.json();

            if (!res.ok) {
                return Promise.reject(data);
            }

            return data;
        })
        .then((res) => {

            if (res.success) {

                const message = res.data?.message || 'OTP sent successfully'; 
                showEmailSuccess(message);

                otpSection.style.display = 'block';
                emailInput.readOnly = true;

                resetOtpInputs();
                otpInputs[0]?.focus();

                startResendTimer(sendBtn, 30);

            } else {

                const message = res.error?.message || 'Failed to send OTP';
                showEmailError(message);
                resetButton(sendBtn, 'Send OTP');

            }
        })
        .catch((err) => {
            
            const message =
                err?.error?.message ||
                err?.message ||
                'Something went wrong while sending OTP. Please try again.';

            showEmailError(message);
            resetButton(sendBtn, 'Send OTP');

        });

    }
        
    // ======================
    // VERIFY OTP
    // ======================

    verifyBtn.addEventListener('click', () => {

        if (verifyBtn.disabled) return;

        const otp = getOtp();

        clearOtpValidation();

        if (otp.length !== 6) {
            return showOtpError('Please enter valid 6 digit OTP');
        }

        setLoading(verifyBtn, true, 'Verifying...');

        fetch('/verify-otp', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: emailInput.value,
                otp: otp
            })
        })
        .then(async (res) => {
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                return Promise.reject(data);
            }

            return data;
        })
        .then((res) => {

            if (res.success) {
                
                const message = res.data?.message || 'OTP verified successfully';
                showOtpSuccess(message);

                window.location.href = res.data?.redirect || '/dashboard';

            } else {

                const message = res.error?.message || 'Invalid OTP';
                showOtpError(message);
            }

        })
        .catch((err) => {

            const message =
                err?.error?.message ||
                err?.message ||
                'Something went wrong while verifying OTP. Please try again.';

            showOtpError(message);

        })
        .finally(() => {
            resetButton(verifyBtn, 'Verify OTP');
        });

    });

    // ======================
    // HELPERS
    // ======================

    function getOtp() {
        let otp = '';
        otpInputs.forEach(input => otp += input.value.trim());
        return otp;
    }

    function resetOtpInputs() {
        otpInputs.forEach(input => input.value = '');
    }

    function setLoading(btn, state, text) {
        btn.disabled = state;
        if (state) btn.innerText = text;
    }

    function resetButton(btn, text) {
        btn.disabled = false;
        btn.innerText = text;
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function showEmailError(message) {
        emailInput.classList.add('is-invalid');
        emailInput.classList.remove('is-valid');

        emailErrorDiv.style.display = 'block';
        emailErrorDiv.innerText = message;

        emailSuccessDiv.style.display = 'none';
    }

    function showEmailSuccess(message) {
        emailInput.classList.add('is-valid');
        emailInput.classList.remove('is-invalid');

        emailSuccessDiv.style.display = 'block';
        emailSuccessDiv.innerText = message;

        emailErrorDiv.style.display = 'none';
    }

    function clearEmailValidation() {
        emailInput.classList.remove('is-valid', 'is-invalid');
        
        emailErrorDiv.style.display = 'none';
        emailSuccessDiv.style.display = 'none';
    }

    function showOtpError(message) {
        otpInputs.forEach(input => input.classList.add('is-invalid'));

        otpErrorDiv.style.display = 'block';
        otpErrorDiv.innerText = message;

        otpSuccessDiv.style.display = 'none';

        shakeOtp();
    }

    function showOtpSuccess(message) {
        otpInputs.forEach(input => input.classList.remove('is-invalid'));

        otpSuccessDiv.style.display = 'block';
        otpSuccessDiv.innerText = message;

        otpErrorDiv.style.display = 'none';
    }

    function clearOtpValidation() {
        otpInputs.forEach(input => input.classList.remove('is-invalid'));

        otpErrorDiv.style.display = 'none';
        otpSuccessDiv.style.display = 'none';
    }

    function shakeOtp() {
        otpSection.classList.add('otp-shake');

        resetOtpInputs();
        otpInputs[0]?.focus();

        setTimeout(() => {
            otpSection.classList.remove('otp-shake');
        }, 300);
    }

    // ======================
    // RESEND TIMER
    // ======================

    function startResendTimer(btn, duration = 30) {
        let timeLeft = duration;

        btn.disabled = true;

        const interval = setInterval(() => {
            btn.innerText = `Resend ${timeLeft}s`;
            btn.style.width = '110px';
            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.innerText = 'Resend OTP';
            }
        }, 1000);
    }
});