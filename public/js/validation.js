function validateForm() {
    let isValid = true;
    const errorClass = 'is-invalid';
    const errorMsgClass = 'invalid-feedback';

    // Clear previous errors
    clearErrors();

    // Required fields validation
    const requiredFields = {
        'first_name': 'First Name is required',
        'last_name': 'Last Name is required',
        'ref_code': 'Reference Code is required',
        'usertype': 'User Type is required',
        'transaction_password': 'Your Transaction Password is required'
    };

    for (let fieldName in requiredFields) {
        const field = document.getElementsByName(fieldName)[0];
        if (!field.value.trim()) {
            showError(field, requiredFields[fieldName]);
            isValid = false;
        }
    }

    // Name validation (letters, spaces, and hyphens only)
    const nameFields = ['first_name', 'last_name'];
    nameFields.forEach(fieldName => {
        const field = document.getElementsByName(fieldName)[0];
        if (field.value && !/^[a-zA-Z\s-]+$/.test(field.value)) {
            showError(field, 'Name must contain only letters, spaces, and hyphens');
            isValid = false;
        }
    });

    // Password validation only if provided (optional in edit mode)
    const password = document.getElementsByName('password')[0];
    if (password && password.value && !validatePassword(password.value)) {
        showError(password, 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character');
        isValid = false;
    }

    // Broker Transaction Password validation if provided
    const brokerTransPass = document.getElementsByName('broker_transaction_password')[0];
    if (brokerTransPass && brokerTransPass.value && !validatePassword(brokerTransPass.value)) {
        showError(brokerTransPass, 'Transaction Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character');
        isValid = false;
    }

    // Admin Transaction Password validation
    const adminTransPass = document.getElementsByName('transaction_password')[0];
    if (!adminTransPass.value.trim()) {
        showError(adminTransPass, 'Your Transaction Password is required to update broker details');
        isValid = false;
    }

    // Numeric fields validation with specific ranges
    const percentageFields = {
        'auto_square_off_percentage': { min: 0, max: 100, message: 'Auto Square Off Percentage must be between 0 and 100' },
        'notify_percentage': { min: 0, max: 100, message: 'Notify Percentage must be between 0 and 100' },
        'profit_share': { min: 0, max: 100, message: 'Profit Share must be between 0 and 100' },
        'brokerage_share': { min: 0, max: 100, message: 'Brokerage Share must be between 0 and 100' }
    };

    for (let fieldName in percentageFields) {
        const field = document.getElementsByName(fieldName)[0];
        if (field.value) {
            const value = parseFloat(field.value);
            if (isNaN(value) || value < percentageFields[fieldName].min || value > percentageFields[fieldName].max) {
                showError(field, percentageFields[fieldName].message);
                isValid = false;
            }
        }
    }

    // Positive number validation
    const positiveNumberFields = {
        'clients_limit': 'Clients Limit must be a positive number',
        'sub_brokers_limit': 'Sub Brokers Limit must be a positive number',
        'mcx_brokerage': 'MCX Brokerage must be a positive number',
        'mcx_intraday_margin': 'MCX Intraday Margin must be a positive number',
        'mcx_holding_margin': 'MCX Holding Margin must be a positive number',
        'nse_brokerage': 'NSE Brokerage must be a positive number',
        'nse_intraday_margin': 'NSE Intraday Margin must be a positive number',
        'nse_holding_margin': 'NSE Holding Margin must be a positive number'
    };

    for (let fieldName in positiveNumberFields) {
        const field = document.getElementsByName(fieldName)[0];
        if (field.value) {
            const value = parseFloat(field.value);
            if (isNaN(value) || value < 0) {
                showError(field, positiveNumberFields[fieldName]);
                isValid = false;
            }
        }
    }

    // MCX and NSE specific validations
    const mcxEnabled = document.getElementsByName('mcx_enabled')[0];
    if (mcxEnabled && mcxEnabled.checked) {
        // Validate MCX fields when MCX is enabled
        const mcxRequiredFields = ['mcx_brokerage_type', 'mcx_brokerage', 'mcx_exposure_type', 'mcx_intraday_margin', 'mcx_holding_margin'];
        mcxRequiredFields.forEach(fieldName => {
            const field = document.getElementsByName(fieldName)[0];
            if (!field.value.trim()) {
                showError(field, 'This field is required when MCX Trading is enabled');
                isValid = false;
            }
        });
    }

    const nseEnabled = document.getElementsByName('nse_enabled')[0];
    if (nseEnabled && nseEnabled.checked) {
        // Validate NSE fields when NSE is enabled
        const nseRequiredFields = ['nse_brokerage', 'nse_intraday_margin', 'nse_holding_margin'];
        nseRequiredFields.forEach(fieldName => {
            const field = document.getElementsByName(fieldName)[0];
            if (!field.value.trim()) {
                showError(field, 'This field is required when Equity Trading is enabled');
                isValid = false;
            }
        });
    }

    return isValid;
}

function validatePassword(password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordRegex.test(password);
}

function showError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearErrors() {
    const errorFields = document.getElementsByClassName('is-invalid');
    const errorMessages = document.getElementsByClassName('invalid-feedback');
    
    while (errorFields.length > 0) {
        errorFields[0].classList.remove('is-invalid');
    }
    
    while (errorMessages.length > 0) {
        errorMessages[0].parentNode.removeChild(errorMessages[0]);
    }
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select');

    // Disable username field if it exists (edit mode)
    const usernameField = document.getElementsByName('username')[0];
    if (usernameField) {
        usernameField.disabled = true;
        usernameField.style.backgroundColor = '#e9ecef';
    }

    // Convert password fields to type="password"
    const passwordFields = ['password', 'broker_transaction_password', 'transaction_password'];
    passwordFields.forEach(fieldName => {
        const field = document.getElementsByName(fieldName)[0];
        if (field && field.type !== 'password') {
            field.type = 'password';
        }
    });

    // Handle MCX/NSE section visibility
    const mcxEnabled = document.getElementsByName('mcx_enabled')[0];
    const nseEnabled = document.getElementsByName('nse_enabled')[0];
    
    if (mcxEnabled) {
        mcxEnabled.addEventListener('change', function() {
            const mcxFields = document.querySelectorAll('[name^="mcx_"]');
            mcxFields.forEach(field => {
                if (field !== mcxEnabled) {
                    field.required = this.checked;
                    field.closest('.form-group').style.opacity = this.checked ? '1' : '0.5';
                }
            });
        });
        // Trigger change event to set initial state
        mcxEnabled.dispatchEvent(new Event('change'));
    }

    if (nseEnabled) {
        nseEnabled.addEventListener('change', function() {
            const nseFields = document.querySelectorAll('[name^="nse_"]');
            nseFields.forEach(field => {
                if (field !== nseEnabled) {
                    field.required = this.checked;
                    field.closest('.form-group').style.opacity = this.checked ? '1' : '0.5';
                }
            });
        });
        // Trigger change event to set initial state
        nseEnabled.dispatchEvent(new Event('change'));
    }

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            clearErrors();
            validateForm();
        });
    });

    // Form submit validation
    form.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            alert('Please fix the errors in the form before submitting.');
        }
    });
});
