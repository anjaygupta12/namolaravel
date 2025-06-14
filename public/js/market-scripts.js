function validateMarketScriptForm() {
    var scriptName = document.getElementById('ContentPlaceHolder1_txtScriptName');
    var marketType = document.getElementById('ContentPlaceHolder1_ddlMarketType');
    var lotSize = document.getElementById('ContentPlaceHolder1_txtLotSize');
    var tickSize = document.getElementById('ContentPlaceHolder1_txtTickSize');

    if (!scriptName.value.trim()) {
        alert('Please enter Script Name');
        scriptName.focus();
        return false;
    }

    if (!marketType.value) {
        alert('Please select Market Type');
        marketType.focus();
        return false;
    }

    if (!lotSize.value || parseInt(lotSize.value) <= 0) {
        alert('Please enter a valid Lot Size');
        lotSize.focus();
        return false;
    }

    if (!tickSize.value.match(/^\d*\.?\d+$/)) {
        alert('Please enter a valid Tick Size (decimal number)');
        tickSize.focus();
        return false;
    }

    return true;
}
