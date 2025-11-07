document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('registrationForm');
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    // Real-time validation
    form.addEventListener('input', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'SELECT' || event.target.tagName === 'TEXTAREA') {
            validateField(event.target);
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            const formData = new FormData(form);
            
            // Show loading state
            const submitBtn = document.querySelector('#registerBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            
            // Submit form via AJAX
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successModal.show();
                    form.reset();
                } else {
                    showError(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while processing your request. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        }
    });

    // Cancel button click handler
    document.getElementById('cancelBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel? All form data will be lost.')) {
            window.location.href = 'index.php';
        }
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (password && confirmPassword) {
        [password, confirmPassword].forEach(field => {
            field.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
        });
    }

    // File input preview
    const fileInput = document.getElementById('profileImage');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showError('Please select a valid image file (JPEG, PNG, or GIF)');
                    this.value = '';
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    showError('File size exceeds 2MB limit');
                    this.value = '';
                    return;
                }
                
                // Show preview (optional)
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can show a preview here if needed
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Helper function to validate a single field
    function validateField(field) {
        if (field.required && !field.value.trim()) {
            field.classList.add('is-invalid');
            return false;
        }
        
        // Email validation
        if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        // URL validation
        if (field.type === 'url' && field.value) {
            try {
                new URL(field.value);
            } catch (_) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        // If we got here, the field is valid
        field.classList.remove('is-invalid');
        return true;
    }

    // Validate the entire form
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // Additional validation for password match
        if (password && confirmPassword && password.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            isValid = false;
        }
        
        return isValid;
    }

    // Show error message in modal
    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        errorModal.show();
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
