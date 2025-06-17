@extends('layouts.admin')

@section('title', 'Copy User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Copy User</h5>
                    </div>
                    <div class="card-body ">
                        <form method="post" action="./copy-user.aspx?userid=2004" id="ctl00">
                            <div class="aspNetHidden">
                                <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
                                <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
                                <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                                    value="/wEPDwULLTE1NTQ0MjA5ODZkZMzgdmlDdWfxKbFfsZBb1uAtkHywz/JSCiML32tY8td2">
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
                                    value="05B61656">
                                <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
                                    value="/wEdAAjnqXAtbfHX0Q1nU3+k2Xil+w6A96UIi12KELRQR05dWapAulf5pX4yC6K7S/vCd5qMD/v6jNNtSY3ycdPsGRIWeOOzCLKGb6Doqh3ChsL9805kTDFHFdFsccP5OMDpUuBpXHOU5QtLQtiaV7hu9oG5q1c4+VWaxQieYN0UgPTjsl+IoCSlR4fv69qWnYJRYelgWnTgYgkOdHgt1xNOkjuL">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Personal Details: </h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-name">
                                        <label class="control-label" for="mcxusers-name">Name</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtfullname" type="text" value="Anshul"
                                            id="ContentPlaceHolder1_txtfullname" class="form-control">

                                        <div class="hint-block">Insert Real name of the trader. Will be visible in trading
                                            App</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mobile">
                                        <label class="control-label" for="mcxusers-mobile">Mobile</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmobile" type="text"
                                            id="ContentPlaceHolder1_txtmobile" class="form-control">

                                        <div class="hint-block">Optional</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Username</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtusername" type="text" value="Ab01"
                                            id="ContentPlaceHolder1_txtusername" class="form-control">

                                        <div class="hint-block">username for loggin-in with, is not case sensitive. must be
                                            unique for every trader. should not contain symbols.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Password</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtPassword" type="text"
                                            id="ContentPlaceHolder1_txtPassword" class="form-control">

                                        <div class="hint-block">password for loggin-in with, is case sensitive. Leave Blank
                                            if you want password remain unchanged.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-city">
                                        <label class="control-label" for="mcxusers-city">City</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtcity" type="text"
                                            id="ContentPlaceHolder1_txtcity" class="form-control">

                                        <div class="hint-block">Optional</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-demo">
                                        <input type="hidden" name="Mcxusers[demo]" value="0"><label><input
                                                type="checkbox" id="mcxusers-demo" class="form-check-input" name="is_demo">
                                            demo account? <span class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Transaction Password</label>
                                        <input name="ctl00$ContentPlaceHolder1$txttranspass" type="text"
                                            value="1" id="ContentPlaceHolder1_txttranspass" class="form-control">

                                        <div class="hint-block"></div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button onclick="__doPostBack('ctl00$ContentPlaceHolder1$btnsubmit','')"
                                            id="ContentPlaceHolder1_btnsubmit" type="submit" name="submit"
                                            class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
