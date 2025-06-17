@extends('layouts.admin')

@section('title', 'Create New User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New User Registration</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="./new-user.aspx" id="ctl00"
                            onsubmit="return validateTradeUserForm();">
                            <div class="aspNetHidden">
                                <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
                                <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
                                <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                                    value="/wEPDwUKMTM0MzIyMjI0OA9kFgJmD2QWAgIBD2QWAgIBD2QWCAINDxYCHglpbm5lcmh0bWxlZAIvDxYCHwAFAnt9ZAIxDxYCHwAFAnt9ZAIzDxYCHwAFAnt9ZBgBBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WCwUjY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjaGtJc0RlbW8FNWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrQWxsb3dPcmRlcnNCZXlvbmRIaWdoTG93BTZjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGNoa0FsbG93T3JkZXJzQmV0d2VlbkhpZ2hMb3cFL2N0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrVHJhZGVFcXVpdHlBc1VuaXRzBSpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGNoa0F1dG9TcXVhcmVPZmYFLmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTlNFRnV0dXJlc0VuYWJsZWQFLmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTlNFT3B0aW9uc0VuYWJsZWQFLmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTUNYT3B0aW9uc0VuYWJsZWQFOmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTlNFRnV0dXJlc1Nob3J0U2VsbGluZ0FsbG93ZWQFOmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTlNFT3B0aW9uc1Nob3J0U2VsbGluZ0FsbG93ZWQFOmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrTUNYT3B0aW9uc1Nob3J0U2VsbGluZ0FsbG93ZWTHVxj/IQNVGI0Afh30+EX5A3ih+HUn1ckM5L0veOWMiw==">
                            </div>

                            <script type="text/javascript">
                                //<![CDATA[
                                var theForm = document.forms['ctl00'];
                                if (!theForm) {
                                    theForm = document.ctl00;
                                }

                                function __doPostBack(eventTarget, eventArgument) {
                                    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
                                        theForm.__EVENTTARGET.value = eventTarget;
                                        theForm.__EVENTARGUMENT.value = eventArgument;
                                        theForm.submit();
                                    }
                                }
                                //]]>
                            </script>


                            <script
                                src="/WebResource.axd?d=zbJJXGmSsHJ-j38D3Ics4Bgl1RpEcA6WXXgCylGQyCGmjNfTfv4sRX8Cgqu97p9GJchmuXeAwjTxvR23vgmJuki5tf5llcQs_qnkYaUrTHA1&amp;t=638720350017811471"
                                type="text/javascript"></script>


                            <script
                                src="/ScriptResource.axd?d=xXKOvN74woX2MJ1eGOiO06emaKVuVcEJ9ejxMwRd30Z8BcLXhod1iRk_2aUu_vjHnjx9ktNnlhhAsIkmCj0TbYg39ONcmqoXUlUT-1qvWGa2jyvP_xwZRRxrBuWv7AdUMsZ1zqZMfE6Svkb6RAlFuA3x59kqP6FjX3P7UM4Ek83nR-33WaUvLis2LmaeKxB80&amp;t=2a9d95e3"
                                type="text/javascript"></script>
                            <script type="text/javascript">
                                //<![CDATA[
                                if (typeof(Sys) === 'undefined') throw new Error('ASP.NET Ajax client-side framework failed to load.');
                                //]]>
                            </script>

                            <script
                                src="/ScriptResource.axd?d=CA7oikjFQBsWcrvE6lVnvO8aBFtOxCVMUYtMhzDLV2_xeRdbyrFPLMFK1GzgZoDSLEP9edlCtA_d1UZ_tWTngn3Fn0DOwyKMTSOLHuU1jAG6PRMsGfwL35lpOtoRVSVv6gWBOqBry4KXaQxH3ybioqaRl7QeHtfA7ftXkRup1kbwm7FHPzuttoW4IWkkBMZw0&amp;t=2a9d95e3"
                                type="text/javascript"></script>
                            <div class="aspNetHidden">

                                <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR"
                                    value="37031C8A">
                                <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
                                    value="/wEdAC3jpkDNub/rtpwn4Yq/rGXdwAgKWcVNUsS0him6Dgsz9RgGBgvtjao3C7KAujwRjj1447MIsoZvoOiqHcKGwv3zV/W0gDAOkD+neJAATSx0O5d3bDTcL2iDnDPTvOaZna+lK034AWbbuStUtPTRcKGJ2iVHiSG+u6Xre9Pth+oqpOvnU16Om6xziISJV/hXZahAa3VY15lPPIHMDoH3Il/HWNCixAVKz0Wl+HDXrD/GjbOWTecKUIvR2y6SxxU4fJpbfXUh8w4GU6SsyT8Ks47Mp9uzhI8JT7jHqwGcSBF9ga+Ov7OgFptlnA9LKQ0CvfBOh18bwO9B3xCMRlCy9LNsrMuqKDbXvRzQdOCZfE1Fi3NzDMzSdfHAG9UlWEa8MPqhjg0LqYtp7gtmDgOylOO1BizyO1ZOoTBqmXHvm4I8bekrF4T5fDYZa4iSjKXN63ogOim0cKSZgogwqzH3QsQLsLdcrtzHFqXcpjD4EFQB42prYaicmQDC4y3JBIaX3HVAWQC0/mP3sN5IOG/Ouye9bBtuW1MKl6ycHRqWESV9G06U/1UbwUUnMG8JNRZ/j01/PTypEVwMJ2Zidg8YFOD8uzGm8lXaBhlXNVbl72V9Tz8fxxdiA/xLNcSvbo/gfbVCaI5bikf8GIhWphguiDJXIanpR38NKraXx91ltcyqjQrj76YiZLF6RtWGYVEVTFjWOFAW1ppAKdPDQXDhqMiQtNr/CuZk8vfPYjYCU5CHt77YGv4eqwXP9Sn7Lk1wm0N1TxSsRvCPhSH64r5Gt5NGDw/nilqP0+H5NKZKevWwmQSOSILX1cLgHVUSbMJGqpXwGIQC+hKUXbx2aJSRwwe81E+tyExl5bNJetF76g4dUHFEyM47rwW5zbfHvMtmUJ1WjJwHzUc91faM+gvz8B0My6jVEW7zHCyzg6I8c2Nu2VFGs1zvEoPu1+SsaQT1Xzzl0OfDNflXlt47SJ4ZIQFcuHo9eVQ7Bmz5bvUQMdFLjQ==">
                            </div>
                            <script type="text/javascript">
                                //<![CDATA[
                                Sys.WebForms.PageRequestManager._initialize('ctl00$ContentPlaceHolder1$ScriptManager1', 'ctl00', [], [], [], 90,
                                    'ctl00');
                                //]]>
                            </script>


                            <!-- Personal Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Personal Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtFullName" type="text"
                                            id="ContentPlaceHolder1_txtFullName" class="form-control" maxlength="100"
                                            placeholder="Enter full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtUsername" type="text"
                                            id="ContentPlaceHolder1_txtUsername" class="form-control" maxlength="50"
                                            placeholder="Enter username">
                                        <div class="hint-block">Must be unique, 4-20 characters,
                                            letters/numbers/underscore/hyphen only</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtPassword" type="password"
                                            id="ContentPlaceHolder1_txtPassword" class="form-control" maxlength="100"
                                            placeholder="Enter password">
                                        <div class="hint-block">Minimum 6 characters</div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display:none">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtEmail" type="text"
                                            id="ContentPlaceHolder1_txtEmail" class="form-control" maxlength="100"
                                            placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-md-6" style="display:none">
                                    <div class="form-group">
                                        <label>Mobile *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtMobile" type="text"
                                            id="ContentPlaceHolder1_txtMobile" class="form-control" maxlength="15"
                                            placeholder="Enter mobile number">
                                        <div class="hint-block">10-digit number starting with 6-9</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Address Details</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="ctl00$ContentPlaceHolder1$txtAddress" id="ContentPlaceHolder1_txtAddress" class="form-control"
                                            rows="3" placeholder="Enter full address"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtCity" type="text"
                                            id="ContentPlaceHolder1_txtCity" class="form-control" maxlength="100"
                                            placeholder="Enter city">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtState" type="text"
                                            id="ContentPlaceHolder1_txtState" class="form-control" maxlength="100"
                                            placeholder="Enter state">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PIN Code</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtPinCode" type="text"
                                            id="ContentPlaceHolder1_txtPinCode" class="form-control" maxlength="6"
                                            placeholder="Enter PIN code">
                                        <div class="hint-block">6-digit PIN code</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Details -->
                            <div class="row mb-4" style="display:none">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Identity Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtPAN" type="text"
                                            id="ContentPlaceHolder1_txtPAN" class="form-control" maxlength="10"
                                            placeholder="Enter PAN number">
                                        <div class="hint-block">Format: ABCDE1234F</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Aadhar Number</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtAadhar" type="text"
                                            id="ContentPlaceHolder1_txtAadhar" class="form-control" maxlength="12"
                                            placeholder="Enter Aadhar number">
                                        <div class="hint-block">12-digit number</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Bank Details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtBankName" type="text"
                                            id="ContentPlaceHolder1_txtBankName" class="form-control" maxlength="100"
                                            placeholder="Enter bank name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtAccountNumber" type="text"
                                            id="ContentPlaceHolder1_txtAccountNumber" class="form-control" maxlength="50"
                                            placeholder="Enter account number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IFSC Code</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtIFSCCode" type="text"
                                            id="ContentPlaceHolder1_txtIFSCCode" class="form-control" maxlength="11"
                                            placeholder="Enter IFSC code">
                                        <div class="hint-block">Format: SBIN0123456</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Holder Name</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtAccountHolderName" type="text"
                                            id="ContentPlaceHolder1_txtAccountHolderName" class="form-control"
                                            maxlength="100" placeholder="Enter account holder name">
                                    </div>
                                </div>
                            </div>

                            <!-- Trading Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Trading Configuration</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkIsDemo" type="checkbox"
                                            id="ContentPlaceHolder1_chkIsDemo" class="form-check-input">
                                        <label class="form-check-label" for="chkIsDemo">Demo Account</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkAllowOrdersBeyondHighLow"
                                            type="checkbox" id="ContentPlaceHolder1_chkAllowOrdersBeyondHighLow"
                                            class="form-check-input" checked="checked">
                                        <label class="form-check-label" for="chkAllowOrdersBeyondHighLow">Allow Orders
                                            Beyond High/Low</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkAllowOrdersBetweenHighLow"
                                            type="checkbox" id="ContentPlaceHolder1_chkAllowOrdersBetweenHighLow"
                                            class="form-check-input" checked="checked">
                                        <label class="form-check-label" for="chkAllowOrdersBetweenHighLow">Allow Orders
                                            Between High-Low</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkTradeEquityAsUnits" type="checkbox"
                                            id="ContentPlaceHolder1_chkTradeEquityAsUnits" class="form-check-input"
                                            checked="checked" onchange="quantity_lots();">
                                        <label class="form-check-label" for="chkTradeEquityAsUnits">Trade Equity as
                                            Units</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkAutoSquareOff" type="checkbox"
                                            id="ContentPlaceHolder1_chkAutoSquareOff" class="form-check-input"
                                            checked="checked">
                                        <label class="form-check-label" for="chkAutoSquareOff">Auto Square Off</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Auto Square Off Percentage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtAutoSquareOffPercentage" type="text"
                                            id="ContentPlaceHolder1_txtAutoSquareOffPercentage" class="form-control"
                                            value="90">
                                        <div class="hint-block">Percentage of ledger balance</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Notify Percentage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNotifyPercentage" type="text"
                                            id="ContentPlaceHolder1_txtNotifyPercentage" class="form-control"
                                            value="80">
                                        <div class="hint-block">Notify when losses reach this percentage</div>
                                    </div>
                                </div>
                            </div>

                            <!-- MCX Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">MCX Configuration</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Lot Margin</label>
                                        <textarea name="ctl00$ContentPlaceHolder1$txtMCXLotMargin" id="ContentPlaceHolder1_txtMCXLotMargin"
                                            class="form-control" rows="3"
                                            placeholder="{&quot;GOLD&quot;:{&quot;INTRADAY&quot;:1000,&quot;HOLDING&quot;:2000}}">{}</textarea>
                                        <div class="hint-block">JSON format for lot-wise margin</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Lot Brokerage</label>
                                        <textarea name="ctl00$ContentPlaceHolder1$txtMCXLotBrokerage" id="ContentPlaceHolder1_txtMCXLotBrokerage"
                                            class="form-control" rows="3" placeholder="{&quot;GOLD&quot;:20,&quot;SILVER&quot;:15}">{}</textarea>
                                        <div class="hint-block">JSON format for lot-wise brokerage</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>MCX Bid Gap</label>
                                        <textarea name="ctl00$ContentPlaceHolder1$txtMCXBidGap" id="ContentPlaceHolder1_txtMCXBidGap" class="form-control"
                                            rows="3" placeholder="{&quot;GOLD&quot;:10,&quot;SILVER&quot;:20}">{}</textarea>
                                        <div class="hint-block">JSON format for bid gap configuration</div>
                                    </div>
                                </div>
                            </div>

                            <!-- NSE Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">NSE Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkNSEFuturesEnabled" type="checkbox"
                                            id="ContentPlaceHolder1_chkNSEFuturesEnabled" class="form-check-input"
                                            checked="checked">
                                        <label class="form-check-label" for="chkNSEFuturesEnabled">Enable NSE
                                            Futures</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkNSEOptionsEnabled" type="checkbox"
                                            id="ContentPlaceHolder1_chkNSEOptionsEnabled" class="form-check-input"
                                            checked="checked">
                                        <label class="form-check-label" for="chkNSEOptionsEnabled">Enable NSE
                                            Options</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkMCXOptionsEnabled" type="checkbox"
                                            id="ContentPlaceHolder1_chkMCXOptionsEnabled" class="form-check-input"
                                            checked="checked">
                                        <label class="form-check-label" for="chkMCXOptionsEnabled">Enable MCX
                                            Options</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Max Lot/Scrip</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEFuturesMaxLotPerScrip" type="text"
                                            id="ContentPlaceHolder1_txtNSEFuturesMaxLotPerScrip" class="form-control"
                                            value="100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Max Lot/Scrip</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEOptionsMaxLotPerScrip" type="text"
                                            id="ContentPlaceHolder1_txtNSEOptionsMaxLotPerScrip" class="form-control"
                                            value="50">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Max Lot/Scrip</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtMCXOptionsMaxLotPerScrip" type="text"
                                            id="ContentPlaceHolder1_txtMCXOptionsMaxLotPerScrip" class="form-control"
                                            value="50">
                                    </div>
                                </div>
                            </div>

                            <!-- Brokerage Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Brokerage Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEFuturesBrokerage" type="text"
                                            id="ContentPlaceHolder1_txtNSEFuturesBrokerage" class="form-control"
                                            value="20.0000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEOptionsBrokerage" type="text"
                                            id="ContentPlaceHolder1_txtNSEOptionsBrokerage" class="form-control"
                                            value="20.0000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtMCXOptionsBrokerage" type="text"
                                            id="ContentPlaceHolder1_txtMCXOptionsBrokerage" class="form-control"
                                            value="20.0000">
                                    </div>
                                </div>
                            </div>

                            <!-- Margin Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Margin Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Futures Holding Margin</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEFuturesHoldingMargin" type="text"
                                            id="ContentPlaceHolder1_txtNSEFuturesHoldingMargin" class="form-control"
                                            value="2.0000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NSE Options Holding Margin</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtNSEOptionsHoldingMargin" type="text"
                                            id="ContentPlaceHolder1_txtNSEOptionsHoldingMargin" class="form-control"
                                            value="2.0000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>MCX Options Holding Margin</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtMCXOptionsHoldingMargin" type="text"
                                            id="ContentPlaceHolder1_txtMCXOptionsHoldingMargin" class="form-control"
                                            value="2.0000">
                                    </div>
                                </div>
                            </div>

                            <!-- Short Selling Configuration -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Short Selling Configuration</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkNSEFuturesShortSellingAllowed"
                                            type="checkbox" id="ContentPlaceHolder1_chkNSEFuturesShortSellingAllowed"
                                            class="form-check-input" checked="checked">
                                        <label class="form-check-label" for="chkNSEFuturesShortSellingAllowed">Allow NSE
                                            Futures Short Selling</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkNSEOptionsShortSellingAllowed"
                                            type="checkbox" id="ContentPlaceHolder1_chkNSEOptionsShortSellingAllowed"
                                            class="form-check-input" checked="checked">
                                        <label class="form-check-label" for="chkNSEOptionsShortSellingAllowed">Allow NSE
                                            Options Short Selling</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input name="ctl00$ContentPlaceHolder1$chkMCXOptionsShortSellingAllowed"
                                            type="checkbox" id="ContentPlaceHolder1_chkMCXOptionsShortSellingAllowed"
                                            class="form-check-input" checked="checked">
                                        <label class="form-check-label" for="chkMCXOptionsShortSellingAllowed">Allow MCX
                                            Options Short Selling</label>
                                    </div>
                                </div>
                            </div>



                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">User Security</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User Transaction Password *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtUserSecurity" type="text"
                                            id="ContentPlaceHolder1_txtUserSecurity" class="form-control"
                                            placeholder="Enter User transaction password">


                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Password -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="bg-info p-2 text-white">Security</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Transaction Password *</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtTransactionPassword" type="password"
                                            id="ContentPlaceHolder1_txtTransactionPassword" class="form-control"
                                            placeholder="Enter transaction password">
                                        <div class="hint-block">Required to save changes</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <input type="submit" name="ctl00$ContentPlaceHolder1$btnSave" value="Save"
                                        id="ContentPlaceHolder1_btnSave" class="btn btn-primary">
                                    <input type="submit" name="ctl00$ContentPlaceHolder1$btnCancel" value="Cancel"
                                        id="ContentPlaceHolder1_btnCancel" class="btn btn-secondary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
