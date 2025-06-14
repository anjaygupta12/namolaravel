function validateTradeUserForm() {
    // Personal Details Validation
    const fullName = document.getElementById('txtFullName').value.trim();
    const username = document.getElementById('txtUsername').value.trim();
    const password = document.getElementById('txtPassword').value.trim();
    const email = document.getElementById('txtEmail').value.trim();
    const mobile = document.getElementById('txtMobile').value.trim();
    const address = document.getElementById('txtAddress').value.trim();
    const city = document.getElementById('txtCity').value.trim();
    const state = document.getElementById('txtState').value.trim();
    const pinCode = document.getElementById('txtPinCode').value.trim();
    const pan = document.getElementById('txtPAN').value.trim();
    const aadhar = document.getElementById('txtAadhar').value.trim();
    const bankName = document.getElementById('txtBankName').value.trim();
    const accountNumber = document.getElementById('txtAccountNumber').value.trim();
    const ifscCode = document.getElementById('txtIFSCCode').value.trim();
    const accountHolderName = document.getElementById('txtAccountHolderName').value.trim();
    const transactionPassword = document.getElementById('txtTransactionPassword').value.trim();

    // Regular Expressions
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const mobileRegex = /^[6-9]\d{9}$/;
    const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
    const aadharRegex = /^\d{12}$/;
    const pinCodeRegex = /^\d{6}$/;
    const ifscRegex = /^[A-Z]{4}0[A-Z0-9]{6}$/;
    const usernameRegex = /^[a-zA-Z0-9_-]{4,20}$/;
    const jsonRegex = /^{.*}$/;
    const decimalRegex = /^\d+(\.\d{1,4})?$/;

    // Required Fields Validation
    if (!fullName) {
        alert('Please enter Full Name');
        document.getElementById('txtFullName').focus();
        return false;
    }

    if (!username) {
        alert('Please enter Username');
        document.getElementById('txtUsername').focus();
        return false;
    }

    if (!usernameRegex.test(username)) {
        alert('Username must be 4-20 characters long and can only contain letters, numbers, underscore and hyphen');
        document.getElementById('txtUsername').focus();
        return false;
    }

    // Password validation for new user
    const isNewUser = !document.getElementById('txtUsername').readOnly;
    if (isNewUser && !password) {
        alert('Please enter Password');
        document.getElementById('txtPassword').focus();
        return false;
    }

    if (password && password.length < 6) {
        alert('Password must be at least 6 characters long');
        document.getElementById('txtPassword').focus();
        return false;
    }

    if (!email) {
        alert('Please enter Email');
        document.getElementById('txtEmail').focus();
        return false;
    }

    if (!emailRegex.test(email)) {
        alert('Please enter a valid Email address');
        document.getElementById('txtEmail').focus();
        return false;
    }

    if (!mobile) {
        alert('Please enter Mobile number');
        document.getElementById('txtMobile').focus();
        return false;
    }

    if (!mobileRegex.test(mobile)) {
        alert('Please enter a valid 10-digit Mobile number starting with 6-9');
        document.getElementById('txtMobile').focus();
        return false;
    }

    // Optional Fields Format Validation
    if (pan && !panRegex.test(pan)) {
        alert('Please enter a valid PAN number (e.g., ABCDE1234F)');
        document.getElementById('txtPAN').focus();
        return false;
    }

    if (aadhar && !aadharRegex.test(aadhar)) {
        alert('Please enter a valid 12-digit Aadhar number');
        document.getElementById('txtAadhar').focus();
        return false;
    }

    if (pinCode && !pinCodeRegex.test(pinCode)) {
        alert('Please enter a valid 6-digit PIN code');
        document.getElementById('txtPinCode').focus();
        return false;
    }

    // Bank Details Validation
    if (bankName || accountNumber || ifscCode || accountHolderName) {
        if (!bankName) {
            alert('Please enter Bank Name');
            document.getElementById('txtBankName').focus();
            return false;
        }
        if (!accountNumber) {
            alert('Please enter Account Number');
            document.getElementById('txtAccountNumber').focus();
            return false;
        }
        if (!ifscCode) {
            alert('Please enter IFSC Code');
            document.getElementById('txtIFSCCode').focus();
            return false;
        }
        if (!ifscRegex.test(ifscCode)) {
            alert('Please enter a valid IFSC code (e.g., SBIN0123456)');
            document.getElementById('txtIFSCCode').focus();
            return false;
        }
        if (!accountHolderName) {
            alert('Please enter Account Holder Name');
            document.getElementById('txtAccountHolderName').focus();
            return false;
        }
    }

    // Trading Configuration Validation
    const autoSquareOff = document.getElementById('chkAutoSquareOff').checked;
    const autoSquareOffPercentage = document.getElementById('txtAutoSquareOffPercentage').value.trim();
    const notifyPercentage = document.getElementById('txtNotifyPercentage').value.trim();

    if (autoSquareOff) {
        if (!autoSquareOffPercentage) {
            alert('Please enter Auto Square Off Percentage');
            document.getElementById('txtAutoSquareOffPercentage').focus();
            return false;
        }
        if (isNaN(autoSquareOffPercentage) || autoSquareOffPercentage < 0 || autoSquareOffPercentage > 100) {
            alert('Auto Square Off Percentage must be between 0 and 100');
            document.getElementById('txtAutoSquareOffPercentage').focus();
            return false;
        }
        if (!notifyPercentage) {
            alert('Please enter Notify Percentage');
            document.getElementById('txtNotifyPercentage').focus();
            return false;
        }
        if (isNaN(notifyPercentage) || notifyPercentage < 0 || notifyPercentage > 100) {
            alert('Notify Percentage must be between 0 and 100');
            document.getElementById('txtNotifyPercentage').focus();
            return false;
        }
        if (parseFloat(notifyPercentage) >= parseFloat(autoSquareOffPercentage)) {
            alert('Notify Percentage must be less than Auto Square Off Percentage');
            document.getElementById('txtNotifyPercentage').focus();
            return false;
        }
    }

    // MCX Configuration Validation
    const mcxLotMargin = document.getElementById('txtMCXLotMargin').value.trim();
    const mcxLotBrokerage = document.getElementById('txtMCXLotBrokerage').value.trim();
    const mcxBidGap = document.getElementById('txtMCXBidGap').value.trim();

    if (mcxLotMargin && !isValidJSON(mcxLotMargin)) {
        alert('MCX Lot Margin must be in valid JSON format');
        document.getElementById('txtMCXLotMargin').focus();
        return false;
    }

    if (mcxLotBrokerage && !isValidJSON(mcxLotBrokerage)) {
        alert('MCX Lot Brokerage must be in valid JSON format');
        document.getElementById('txtMCXLotBrokerage').focus();
        return false;
    }

    if (mcxBidGap && !isValidJSON(mcxBidGap)) {
        alert('MCX Bid Gap must be in valid JSON format');
        document.getElementById('txtMCXBidGap').focus();
        return false;
    }

    // Brokerage and Margin Validation
    const brokerageFields = [
        { id: 'txtNSEFuturesBrokerage', name: 'NSE Futures Brokerage' },
        { id: 'txtNSEOptionsBrokerage', name: 'NSE Options Brokerage' },
        { id: 'txtMCXOptionsBrokerage', name: 'MCX Options Brokerage' }
    ];

    for (const field of brokerageFields) {
        const value = document.getElementById(field.id).value.trim();
        if (!value) {
            alert(`Please enter ${field.name}`);
            document.getElementById(field.id).focus();
            return false;
        }
        if (!decimalRegex.test(value)) {
            alert(`${field.name} must be a valid decimal number with up to 4 decimal places`);
            document.getElementById(field.id).focus();
            return false;
        }
    }

    const marginFields = [
        { id: 'txtNSEFuturesHoldingMargin', name: 'NSE Futures Holding Margin' },
        { id: 'txtNSEOptionsHoldingMargin', name: 'NSE Options Holding Margin' },
        { id: 'txtMCXOptionsHoldingMargin', name: 'MCX Options Holding Margin' }
    ];

    for (const field of marginFields) {
        const value = document.getElementById(field.id).value.trim();
        if (!value) {
            alert(`Please enter ${field.name}`);
            document.getElementById(field.id).focus();
            return false;
        }
        if (!decimalRegex.test(value)) {
            alert(`${field.name} must be a valid decimal number with up to 4 decimal places`);
            document.getElementById(field.id).focus();
            return false;
        }
    }

    // Lot Size Validation
    const lotSizeFields = [
        { id: 'txtNSEFuturesMaxLotPerScrip', name: 'NSE Futures Max Lot/Scrip' },
        { id: 'txtNSEOptionsMaxLotPerScrip', name: 'NSE Options Max Lot/Scrip' },
        { id: 'txtMCXOptionsMaxLotPerScrip', name: 'MCX Options Max Lot/Scrip' }
    ];

    for (const field of lotSizeFields) {
        const value = document.getElementById(field.id).value.trim();
        if (!value) {
            alert(`Please enter ${field.name}`);
            document.getElementById(field.id).focus();
            return false;
        }
        if (!Number.isInteger(Number(value)) || Number(value) < 0) {
            alert(`${field.name} must be a positive integer`);
            document.getElementById(field.id).focus();
            return false;
        }
    }

    // Transaction Password Validation
    if (!transactionPassword) {
        alert('Please enter Transaction Password');
        document.getElementById('txtTransactionPassword').focus();
        return false;
    }

    return true;
}

function isValidJSON(str) {
    try {
        const obj = JSON.parse(str);
        return typeof obj === 'object' && obj !== null;
    } catch (e) {
        return false;
    }
}

function quantity_lots() {
    const tradeEquityAsUnits = document.getElementById('chkTradeEquityAsUnits').checked;
    const quantityLotElements = document.getElementsByClassName('quantity_lot');
    
    for (let element of quantityLotElements) {
        element.textContent = tradeEquityAsUnits ? 'quantity' : 'lot';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    quantity_lots();
});
