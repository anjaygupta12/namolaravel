@extends('layouts.admin')

@section('title', 'Negative Balance Transactions')

@section('content')
    <div class="card ">
        <div class="card-body ">
            <form method="post" action="./Create-Funds-Wd.aspx?userid=6349" id="ctl00">
                <div class="aspNetHidden">
                    <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                        value="/wEPDwUKLTQ1NTkzMjA1OA9kFgJmD2QWAgIBD2QWAgIBD2QWAgIBDw8WAh4EVGV4dGVkZGTN744zwtYjpuU8MlPNorp2/obCDMUqQjH4+XcXLbfcxw==">
                </div>

                <div class="aspNetHidden">

                    <input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="AED6367B">
                    <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
                        value="/wEdAAXk+8Y7PfewGwRgRsSaiytfINHP6Lkr5Ts7eLa9vLG6c21wp+tq0SUq0I0ZrH/IyuGhQhztmMj3h/MqIDWD2JNVVxO7ONN2L+tqN9f8f+J/zaOZ+rFoYrAEaZaL7D1K9mbFnH9PH4nqDJ2z7XDb29RX">
                </div>
                <div style="color: red"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group field-userfunds-user_id required">
                            <label class="control-label" for="userfunds-user_id">User ID</label>
                            <div class="dropdown">
                                <span id="ContentPlaceHolder1_lblusername"></span> <input type="hidden" name="userid"
                                    id="userid" value="6349">
                            </div>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group field-userfunds-notes">
                            <label class="control-label" for="userfunds-notes">Notes</label>
                            <input name="ctl00$ContentPlaceHolder1$txtnotes" type="text"
                                id="ContentPlaceHolder1_txtnotes" class="form-control">

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group field-userfunds-wd required">
                            <label class="control-label" for="userfunds-wd">Funds</label>
                            <input name="ctl00$ContentPlaceHolder1$txtamount" type="text"
                                id="ContentPlaceHolder1_txtamount" class="form-control">

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group field-userfunds-transaction_password required">
                            <label class="control-label" for="userfunds-transaction_password">Transaction Password</label>
                            <input name="ctl00$ContentPlaceHolder1$txtnewpassword" type="text"
                                id="ContentPlaceHolder1_txtnewpassword" class="form-control">

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="submit" name="ctl00$ContentPlaceHolder1$btnsave" value="Save Funds Withdrawal"
                                id="ContentPlaceHolder1_btnsave" class="btn btn-success">


                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
