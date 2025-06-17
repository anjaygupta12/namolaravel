@extends('layouts.admin')

@section('title', 'Comex Margins')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Comex Margins</h4>
                        <p class="card-category">Configure Comex settings for {{ $user->name }}</p>
                    </div>
                    <div class="card-body ">
                        <form method="post" action="./comex-margins.aspx?userid=2004" id="ctl00">
                            <div class="aspNetHidden">
                                <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
                                <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
                                <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                                    value="/wEPDwULLTEwNDIwOTk0NjRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYDBTNjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJENvbWV4bWN4dXNlcnNlcXVpdHllbmFibGUFLWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkbWN4dXNlcnNlcXVpdHlGb3JleAUuY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRtY3h1c2Vyc2VxdWl0eUNyeXB0byVvQZDfKWa0bP2Ce6ySew5b8QE0oANm2Gt0vkGWs6PF">
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


                            <div class="aspNetHidden">

                                <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR"
                                    value="4A0FF465">
                                <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
                                    value="/wEdACFeQW9/Jo55H3d5xLSKLDWgs2uPfhaH8YXJwNRWsbwJgNYaby8x4vSzr+XwxEvT5U0MNyRwIfEN9sPf4cmG3D0mMIsOo7MEGJxo796763iGqnmL59FGDsoqdX4ASNdhZ7E0iAZ6HEqVmgSIHN1J0HDCQIfCeZvWbPnPiUnfXch+izpbdq9Nhu5QEaoWCLxuKxYCNhfDWqNKFKil87NneUHi4AEuN7ukragacQg2iQmf/cZ/RXv4Xa+GsG2uxyPlnryLZH0OYr1zNRrA56yVH9Hu5r1MvKlFS3/5fQLO1CpQqLjRsj88maIbaL03Md/VDUq/+cnoH7LcwTSzEHqLHIpTRW5U1loLMQxquB4rPjamFPnrop6VdU8SAAc4UIZUFYUhA/c1wSBDV3VzhOZbHvboIDPIz78Q7+BOSdS+BlQnmeiD4F8vVE3hYDgm1dUyy/XnW03DR1rL55FK/E4somrGpuRMMSSX4Y77StMCphaTNh+ToCUXsNxxOLNWsmPleDHua1HWHgmy7nvZUrFE4ccPVYouOvJBMekDPEf6m+XgmNt4DGDImpced3z0jqCOSrI11YXqZvc7+mjV0kSQqNX4meOu2UFILeSmjdQkd4VsweSxmIXgmgNVfWWscM4PaA7D3sHPWucRk7XVvKsmvnFTqnMloCeJA+8rhoa6xXWxrcuo1RFu8xwss4OiPHNjbtmSlFi/rqtPShF7uLxl4hcceGAd/JQw2tRJ1wOgrZzxsg==">
                            </div>
                            <div style="color: red"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Comex Config </h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$Comexmcxusersequityenable" type="checkbox"
                                                id="ContentPlaceHolder1_Comexmcxusersequityenable" class="form-check-input"
                                                value="1">
                                            Comex Trading <span class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Comex brokerage
                                            type</label>
                                        <select name="ctl00$ContentPlaceHolder1$ddlmodeComex"
                                            id="ContentPlaceHolder1_ddlmodeComex" class="form-control">
                                            <option value="Per Lot">Per Lot</option>
                                            <option selected="selected" value="Per Crore">Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Comex brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersequitybrokerageComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersequitybrokerageComex" class="form-control"
                                            value="1.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="mcxusers-min_size_trade">Minimum lots required per
                                            single trade of Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmin_size_tradeComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmin_size_tradeComex" class="form-control"
                                            value="0.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="mcxusers-max_size_trade">Maximum lots allowed per
                                            single trade of Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_tradeComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_tradeComex" class="form-control"
                                            value="5.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="mcxusers-max_size_script">Maximum lots allowed per
                                            scrip of Comex to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_scriptComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_scriptComex" class="form-control"
                                            value="3.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-max_size_all">Max Size
                                            All Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_allComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_allComex" class="form-control"
                                            value="10.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="mcxusers-exposure">Intraday Exposure/Margin
                                            Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersexposureComex" type="text"
                                            id="ContentPlaceHolder1_mcxusersexposureComex" class="form-control"
                                            value="300.0000">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade devided by Exposure is
                                            required margin. eg. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="mcxusers-holding_exposure">Holding
                                            Exposure/Margin Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersholding_exposureComex"
                                            type="text" id="ContentPlaceHolder1_mcxusersholding_exposureComex"
                                            class="form-control" value="100.0000">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. eg. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be
                                            away by % from current price Comex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxuserseqawayComex" type="text"
                                            id="ContentPlaceHolder1_mcxuserseqawayComex" class="form-control"
                                            value="0.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Forex Config </h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$mcxusersequityForex" type="checkbox"
                                                id="ContentPlaceHolder1_mcxusersequityForex" class="form-check-input"
                                                value="1">
                                            forex Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Forex brokerage
                                            type</label>
                                        <select name="ctl00$ContentPlaceHolder1$ddlmodeForex"
                                            id="ContentPlaceHolder1_ddlmodeForex" class="form-control">
                                            <option value="Per Lot">Per Lot</option>
                                            <option selected="selected" value="Per Crore">Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Forex
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersequity_brokerageForex"
                                            type="text" id="ContentPlaceHolder1_mcxusersequity_brokerageForex"
                                            class="form-control" value="1000.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="mcxusers-min_size_trade">Minimum lots required
                                            per single trade of forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmin_size_tradeForex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmin_size_tradeForex" class="form-control"
                                            value="0.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="mcxusers-max_size_trade">Maximum lots allowed
                                            per single trade of forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_tradeForex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_tradeForex" class="form-control"
                                            value="5.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="mcxusers-max_size_script">Maximum lots allowed
                                            per scrip of forex to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_scriptForex"
                                            type="text" id="ContentPlaceHolder1_mcxusersmax_size_scriptForex"
                                            class="form-control" value="2.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-max_size_all">Max Size
                                            All forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_allForex" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_allForex" class="form-control"
                                            value="10.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="mcxusers-exposure">Intraday Exposure/Margin
                                            forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersexposureForex" type="text"
                                            id="ContentPlaceHolder1_mcxusersexposureForex" class="form-control"
                                            value="100.0000">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade devided by Exposure is
                                            required margin. eg. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="mcxusers-holding_exposure">Holding
                                            Exposure/Margin forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersholding_exposureForex"
                                            type="text" id="ContentPlaceHolder1_mcxusersholding_exposureForex"
                                            class="form-control" value="50.0000">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. eg. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be
                                            away by % from current price forex</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxuserseqawayForex" type="text"
                                            id="ContentPlaceHolder1_mcxuserseqawayForex" class="form-control"
                                            value="0.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Crypto Config </h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$mcxusersequityCrypto" type="checkbox"
                                                id="ContentPlaceHolder1_mcxusersequityCrypto" class="form-check-input"
                                                value="1">
                                            Crypto Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Crypto brokerage
                                            type</label>
                                        <select name="ctl00$ContentPlaceHolder1$ddlmodeCrypto"
                                            id="ContentPlaceHolder1_ddlmodeCrypto" class="form-control">
                                            <option value="Per Lot">Per Lot</option>
                                            <option selected="selected" value="Per Crore">Per Crore</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Crypto
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersequity_brokerageCrypto"
                                            type="text" id="ContentPlaceHolder1_mcxusersequity_brokerageCrypto"
                                            class="form-control" value="1000.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade">
                                        <label class="control-label" for="mcxusers-min_size_trade">Minimum lots required
                                            per single trade of crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmin_size_tradeCrypto"
                                            type="text" id="ContentPlaceHolder1_mcxusersmin_size_tradeCrypto"
                                            class="form-control" value="0.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade">
                                        <label class="control-label" for="mcxusers-max_size_trade">Maximum lots allowed
                                            per single trade of crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_tradeCrypto"
                                            type="text" id="ContentPlaceHolder1_mcxusersmax_size_tradeCrypto"
                                            class="form-control" value="5.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script">
                                        <label class="control-label" for="mcxusers-max_size_script">Maximum lots allowed
                                            per scrip of crypto to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_scriptCrypto"
                                            type="text" id="ContentPlaceHolder1_mcxusersmax_size_scriptCrypto"
                                            class="form-control" value="2.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-max_size_all">Max Size
                                            All crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersmax_size_allCrypto" type="text"
                                            id="ContentPlaceHolder1_mcxusersmax_size_allCrypto" class="form-control"
                                            value="10.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure">
                                        <label class="control-label" for="mcxusers-exposure">Intraday Exposure/Margin
                                            crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersexposureCrypto" type="text"
                                            id="ContentPlaceHolder1_mcxusersexposureCrypto" class="form-control"
                                            value="50.0000">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade devided by Exposure is
                                            required margin. eg. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure">
                                        <label class="control-label" for="mcxusers-holding_exposure">Holding
                                            Exposure/Margin crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersholding_exposureCrypto"
                                            type="text" id="ContentPlaceHolder1_mcxusersholding_exposureCrypto"
                                            class="form-control" value="20.0000">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. eg. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be
                                            away by % from current price crypto</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxuserseqawayCrypto" type="text"
                                            id="ContentPlaceHolder1_mcxuserseqawayCrypto" class="form-control"
                                            value="120.0000">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="row">
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Transaction Password</label>
                                        <input name="ctl00$ContentPlaceHolder1$txttransactionpassword" type="text"
                                            id="ContentPlaceHolder1_txttransactionpassword" class="form-control"
                                            value="1">
                                        <div class="hint-block"></div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <button onclick="__doPostBack('ctl00$ContentPlaceHolder1$btnSave','')"
                                    id="ContentPlaceHolder1_btnSave" type="submit" name="submit"
                                    class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
