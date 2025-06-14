function validateEditForm() {
    let isValid = true;
    const errors = [];

    // Helper functions
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.innerText = message;
            field.parentNode.appendChild(errorDiv);
        }
        errors.push(message);
        isValid = false;
    }

    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(errorDiv => {
            errorDiv.remove();
        });
    }

    function validateRequired(fieldId, fieldName) {
        const value = document.getElementById(fieldId)?.value?.trim();
        if (!value) {
            showError(fieldId, `${fieldName} is required`);
            return false;
        }
        return true;
    }

    function validateNumeric(fieldId, fieldName, min = null, max = null) {
        const value = document.getElementById(fieldId)?.value?.trim();
        if (value) {
            const numValue = parseFloat(value);
            if (isNaN(numValue)) {
                showError(fieldId, `${fieldName} must be a valid number`);
                return false;
            }
            if (min !== null && numValue < min) {
                showError(fieldId, `${fieldName} must be at least ${min}`);
                return false;
            }
            if (max !== null && numValue > max) {
                showError(fieldId, `${fieldName} must not exceed ${max}`);
                return false;
            }
        }
        return true;
    }

    // Clear previous errors
    clearErrors();

    // Validate Personal Details
    validateRequired('first_name', 'First Name');
    validateRequired('last_name', 'Last Name');
    validateRequired('ref_code', 'Reference Code');
    validateRequired('usertype', 'User Type');

    // Validate optional password fields
    const password = document.getElementById('password').value;
    const transactionPassword = document.getElementById('broker_transaction_password').value;

    if (password) {
        if (password.length < 6) {
            showError('password', 'Password must be at least 6 characters long');
        }
    }

    if (transactionPassword) {
        if (transactionPassword.length < 6) {
            showError('broker_transaction_password', 'Transaction Password must be at least 6 characters long');
        }
    }

    // Validate Configuration
    validateNumeric('auto_square_off_percentage', 'Auto Square Off Percentage', 0, 100);
    validateNumeric('notify_percentage', 'Notify Percentage', 0, 100);
    validateNumeric('profit_share', 'Profit Share', 0, 100);
    validateNumeric('brokerage_share', 'Brokerage Share', 0, 100);
    validateNumeric('clients_limit', 'Clients Limit', 0);
    validateNumeric('sub_brokers_limit', 'Sub-Brokers Limit', 0);

    // Validate MCX Settings if enabled
    if (document.getElementById('mcx_enabled').checked) {
        validateRequired('mcx_brokerage_type', 'MCX Brokerage Type');
        validateNumeric('mcx_brokerage', 'MCX Brokerage', 0);
        validateRequired('mcx_exposure_type', 'MCX Exposure Type');
        validateNumeric('mcx_intraday_margin', 'MCX Intraday Margin', 0);
        validateNumeric('mcx_holding_margin', 'MCX Holding Margin', 0);
    }

    // Validate NSE Settings if enabled
    if (document.getElementById('nse_enabled').checked) {
        validateNumeric('nse_brokerage', 'NSE Brokerage', 0);
        validateNumeric('nse_intraday_margin', 'NSE Intraday Margin', 0);
        validateNumeric('nse_holding_margin', 'NSE Holding Margin', 0);
    }

    // Validate Admin Transaction Password
    validateRequired('transaction_password', 'Admin Transaction Password');

    // If there are errors, show them in an alert
    if (!isValid) {
        alert('Please fix the following errors:\n' + errors.join('\n'));
    }

    return isValid;
}

// Add event listeners for MCX and NSE sections
document.addEventListener('DOMContentLoaded', function() {
    const mcxEnabled = document.getElementById('mcx_enabled');
    const nseEnabled = document.getElementById('nse_enabled');

    function toggleMCXFields() {
        const mcxFields = document.querySelectorAll('[id^="mcx_"]:not(#mcx_enabled)');
        mcxFields.forEach(field => {
            field.closest('.form-group').style.display = mcxEnabled.checked ? 'block' : 'none';
            if (!mcxEnabled.checked) {
                field.value = '';
            }
        });
    }

    function toggleNSEFields() {
        const nseFields = document.querySelectorAll('[id^="nse_"]:not(#nse_enabled)');
        nseFields.forEach(field => {
            field.closest('.form-group').style.display = nseEnabled.checked ? 'block' : 'none';
            if (!nseEnabled.checked) {
                field.value = '';
            }
        });
    }

    if (mcxEnabled) {
        mcxEnabled.addEventListener('change', toggleMCXFields);
        toggleMCXFields();
    }

    if (nseEnabled) {
        nseEnabled.addEventListener('change', toggleNSEFields);
        toggleNSEFields();
    }
});
