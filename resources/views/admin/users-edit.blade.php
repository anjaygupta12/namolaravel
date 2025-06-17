@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Edit User</h5>
                    </div>
                    <div class="card-body ">
                        <form method="post" action="./edit-user.aspx?userid=2004" id="ctl00">
                            <div class="aspNetHidden">
                                <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
                                <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
                                <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
                                    value="/wEPDwUIMjA4Nzk5OTYPZBYCZg9kFgICAQ9kFgICAQ9kFgIC2wIPEA8WBh4LXyFEYXRhQm91bmRnHg1EYXRhVGV4dEZpZWxkBQlGaXJzdE5hbWUeDkRhdGFWYWx1ZUZpZWxkBQhCcm9rZXJJZGQQFQ0LU2VsZWN0IFVzZXIEYW1pdAZkZWVwYWsFbW9oYW4DcmFtBnJoZ3JmaAVyb2hhbgZydXBlc2gHU0FOREVFUAZzYXRpc2gIU2hlcmxvY2sFc29oYW4Fc3VtaXQVDQEwBDEwMTIEMTAwNgQxMDA3BDEwMDkEMjAwMwQxMDExBDEwMDUBMQQxMDA0BDEwMDMEMTAwOAQxMDEzFCsDDWdnZ2dnZ2dnZ2dnZ2dkZBgBBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WCwUkY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjaGtpc19kZW1vBTljdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGNoa2FsbG93X29yZGVyc19iZXlvbmRfaGlnaF9sb3cFOmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrYWxsb3dfb3JkZXJzX2JldHdlZW5faGlnaF9sb3cFL2N0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkdHJhZGVfZXF1aXR5X2FzX3VuaXRzBSNjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGNoa3N0YXR1cwUsY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjaGthdXRvX3NxdWFyZV9vZmYFKGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrbWN4X2VuYWJsZWQFK2N0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrbWN4dXNlcnNlcXVpdHkFLGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY2hrbWN4dXNlcnNlcXVpdHkxBSxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGNoa21jeHVzZXJzZXF1aXR5MgUsY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjaGttY3h1c2Vyc2VxdWl0eTM7zDEWGXom2CgvWCJi8t79qY0h4SVFQNMHusl4G/sMzg==">
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
                                    value="89C8BEE3">
                                <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION"
                                    value="/wEdAL0BDl0xnKpNGdl6BBPlopLP1sAIClnFTVLEtIYpug4LM/WXd2w03C9og5wz07zmmZ2vGAYGC+2NqjcLsoC6PBGOPWguxzy4xCLGpfBu7vhgqtHaJUeJIb67pet70+2H6iqkFmwA3WLphbOi0VmNaqmUmO4b3hlvBvat5x9wjX6VOgfCgD0LTazRBVHUM2zROZIM7fbtrsMOrhnjxBtvaxQVDEPf+0GanBccVnjgptqSSHBgfACnLSxHKsdzuJU+MvXzPVRpgIj66VAh2z2BS7yeEe8RgzkP/CzAMTHAH8YzSB8vWxsgkhGF8cZDXA0r0t1ZsT9g/ZHs9ZKImnhStrbY2wVGizsqwQw/3Xl4v2BjPTNXwrkHMzo9Qj3Bvawn8h5ZwLACyG6IczOwL2+Ow7+TWfceGQZVmhqxjPPDazdgDbdCEqxv0o9EV7Wi2hnOOdOG1L3/+KmmDEaVrLsMJEhjKnvmpfGgvz+HRgctMFY9NmZTdz/XtMcIyCeQ0B1caZkeJjyhcBgOLuPuI/9E6nd5aGlnLS2Zy/nc4mPgLQvrOcgBjmCo/GTI9/y80wuqIBNwyABcrEDbiF0Cmrieba8Jkvwe7nT2KRHVWRtToAlHMcF449qOIg/KJK/vOhSQ6T+5D4Kohmmx4zWOqCLmWSVWw2SkhWBpeHpfLvo9W4Vf84a2GXk9era4m15PkiCOJe0LoLYFCZpmIY87Pm+M0h9nFK4SiL3A0BIbZ0P2ZehfxBs0356WYJ5FFO27FL5smZuRGo0AGkhsGok8w3mpx2crgTndwH2X41xameBcmBKzR1k3YbcbJmkXsIgqnm9/0fKcBPz4S+8AD2OSeeV+GpaCt61poR95disTzezoUieiaMyvKh2L2YMSkWfVwi5/zlyqTOVqxCiCwW3n/4DXck7fLWlTmRnFzkiZQQO4r04Eu+HRY+KA/T5KW+BC2CbM7vQHD8n2/eT9ln3YxX5OvGyg4aThrw94ZIE2Adq+imZnha9wHCjA21I8PjxlSVPZFRDu9nrYuK9GUX+K65onH5vOcRCP/wTeHP3ZxXp2G0pHdaDficVfqLccrujQYbSnX7Oq1rEp5iBLUjkYlJdlqYnug63ZCZIVFdDc83otbZg2zZhnl5R3qZobW3jcuWYsejhV5FLBX6eaT0762OiijUgBYXzPK0SQftsllVoc13JpayalX8U775m4e8EQdxWr0OTOjP6qXR1Y2wGvs9bZM9hdl2CtvCGM2bGgLqfSY8C7JA5OFNdrDsKzJsYhjnDhip6XTbAgtcM6OftDt5oBf2C0kDiljRzv+10PvUNCndmPj3Fo0IR+Gb1CUHZb6q9gX5fzE/pzSHj5Evv+W/qcgPqWaX8/VPf1CBuD7hvbaQbqzaiIAGNn9TXVglRdZYXkeHNsxjzKcd1gtXQt+lU19fz7gkijJxgItbfFBTaegulYn45v0O6fojptcPan03LwSex9XsBYv7UjGDaoqrLctRuwaH+8L8KP+QloWW3u9WgvhpZkUJuORbxptuVgST2xhVroPFJR5eeT93m5HiEY4dYWYcSEQ5llWlE3CGisWKrcHCbVEvQRP7jESrUnPg+MZdNuQG2jEQqNXq6LMrmc7GQgfcNjrYEIO/DBvxyE2NYfDZ/2aE2BM6T//GsYJubn3Ry/UQbww9ytqwlds3SKSbjIEgScFEuKiGUgkXhws+o13TgETUKVCRHv4L+gs3PsmPu+wan9dDy6fE2scwK1A1rATIIVhXbIxBA04jzdAIC6TvAW2/6Y3cBm4po3iYi6Qmj3uHfFctHxxu4PRjCwZhOw2iAomFF0Wj9cYeeH3/7ywiHV88OO75kLWHnQqs+KwGVbi8tmYg3D+O4WRzc594G95ZNnCz6rEGdDLKuLP0T5iLADqGHqcgWY0wZosnQuZAyB+kWuoQcQqZdnSIJUPLJ7v/mFoONLhjdU0NGaryZsqNG5aPDkOpXxpCnWVfctp5yWQxtlW2Eq22hGZTDG9H4UJ/nGyhIOj3qNpBVKdj2jAOAowbkMJBJYxXev4AMsBdd9CKJFA1qzs15DUVc50HSP4lF6UMBFFRJbhN6Kw+0hhmvX0gJmTHo09IW+RMfqNYqa5+B+xJ1TaUpuEAKjQFBvhMg+9XGbvCcpyzI1kHgT+EyPLsPUJnGP42Zn9oigZQDiEPoY1IPmygcDD9MbA2AxGe7v66Wu8IsOkjXTD2HeINqtH2cvpe2FcrsjwWRrHODADNiJ6gJmDJyG4ZTs4TrkjcVbedX4rGdWPVKiTefijHhHuZw6gEQvMFV4P3PZivPthnUp0rLe9gEC+Ewss4/QKgBQviFSZvj8BW33DaFZZSmo+r2vxVho34pDA/Y278eQDIvcLjjxLvqCXpLX9fwdYN/gWTZ1+x/5J007IVRZV9sJfUgU26R2Adq/6TrUWFvEJEeWjwrZiZZbYTYo6+bsgKhWN7tNCk0Nu3NN7hjNBXw8sgIykzJruFoX+CZtjVcPXgAQa7aW4zgliFqQSMwUP7sPQISMNryKv3yfVdR6vtLFhOk1NNQFu4LVPFQo/vRj4KhJ48uOjqfDs7Fj/APTmslC8cqO2W7lUT6eeZqmOq0VGurDQfiA456xAb+nZewCuMuLNB6eTe8GYGnUQIVJylf3xD/xLuJnBBcuGdP16d3Ej57cnFgfiSSfBLGBwV25qHb+mbkvNuYaMykRoW+vY+o4ga7QXwIwFeh8J0nByXsILNNpTJGOyTXtbJfPdFS5FvD9gGDtdNNBaG1i2hv3maPuTyIPDCA7xcXoQfB1FFBO300QdZRZN+mePucMw9dXBnoKMPykY17GkHovQBgT0oAqgEZdhREii0TZ0z02/mQoxFqDtIt9W8pZy9GPnRy27NZ4sLkEKAdgCcMqLxZF1dFRqXcbjhajNChSWqmNpa6Gk4pJXbA2hXAkYmTgkCCANbbotol9+FuJ9YIsTUcWC1Vs/ZTchZbZtOr6d76isQTSYvqcTkwXYw1i3KDh/Q1g5bPgQWQf7BN9DPbmVR2dMeWY4C+7/YvbTT0OIi0lSSXOtLqEGV4/vYY6cFjoivh/ZuUn7Bgzjqudwac1/6cH6/XNQtbQHiHp4/iS4imuZ1oaD0lKtYnIVkD+xP5PMwxvsxcB5zpF/MTXmXh9woZIPpALM3NCIu+tBV78CO01RgO+U1jzkHlYgjtMHrbrI9VWhVVKc+XbaBXJInTnecjgQ4Uz45WsBpbnE9hE5cdrpFX87zscCDXvEV3M7COMsdLs6EPzt5fF3frYGBOUQt2h96Tl2PJEzgC7HPNepQ7JWclnhPp1LZdWQjWe/shgZXKSsLIMXdjbbUSjL3BW913nn6DMTD8PgMQz4cLynocm+yLRxRfr1Z1gz5EJ3x16btDt3XV6N66r8Aet4cv7Q2FC4cXWpryPtMVjNTTVZrGfJroJSdjsC+34+tkHGZlAkj4JXtZxBmZt+tqSchImgIJk8ZjxBzOufquGAsxMQoHmSfdGt+9I0kqEtL91UDlQ4WQCi1ShqEV6RO+47Bu0bMovbVIPnnWqoubuO6DRya/ovm8mKR3bSk5+SPyC698H/erzl5k84u5xPu8EC7CptGXBxyNMBu7sWmeNCAJ5wKD+f6jbd0RneyhTqQGFJHGXGPUoBUA/M2ioLoMnlQj0V1RJukHyxCvO7KAufKsr+zQamoOODh/ndLIHPKxy5kGxvo01Vx7bPxFrv/Mf4IPFrbCty9e9kqSEgqrwfwUjvqiyk/GjWeoj92A3dMAhU7j09c0bfvxFtNRw4VBHyX7vZm3DLDv1Dvidv3ceH5oTqCd0GEI2za+WYi3CvCe1qmPMYWvExve8Wp3QUDRGWRZxnfQlYvtIUIpG0/zlWS87KoQ9twLOVLExD2gkwBNJrdfRTN5G+m4b8YXlNvZwG6mKb3thAfNuT+ADNGy8Y76tAMh/jH/jlIGS5bGME4tTQjNQVs6rjBPQJYHqitw9H8VhptS7Umlcc5TlC0tC2JpXuG72gbmrVzj5VZrFCJ5g3RSA9OOyg36jxNr9FuNz6dQmVDwyXfjhy2St0KxWk9oC4wxFyrg=">
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Personal Details</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-name">
                                        <label class="control-label" for="mcxusers-name">Name</label>

                                        <input name="ctl00$ContentPlaceHolder1$txtFullName" type="text"
                                            id="ContentPlaceHolder1_txtFullName" class="form-control">
                                        <div class="hint-block">Insert Real name of the trader. Will be visible in trading
                                            App</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mobile">
                                        <label class="control-label" for="mcxusers-mobile">Mobile</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtMobile" type="text"
                                            id="ContentPlaceHolder1_txtMobile" class="form-control">

                                        <div class="hint-block">Optional</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Username</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtUsername" type="text"
                                            id="ContentPlaceHolder1_txtUsername" class="form-control">

                                        <div class="hint-block">username for loggin-in with, is not case sensitive. must be
                                            unique for every trader. should not contain symbols.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Password</label>

                                        <input name="ctl00$ContentPlaceHolder1$txtpassword" type="text"
                                            id="ContentPlaceHolder1_txtpassword" class="form-control">
                                        <div class="hint-block">password for loggin-in with, is case sensitive. Leave Blank
                                            if you want password remain unchanged.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-city">
                                        <label class="control-label" for="mcxusers-city">City</label>

                                        <input name="ctl00$ContentPlaceHolder1$txtCity" type="text"
                                            id="ContentPlaceHolder1_txtCity" class="form-control">
                                        <div class="hint-block">Optional</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Config</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-demo">

                                        <input type="hidden" name="Mcxusers[demo]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkis_demo" type="checkbox"
                                                id="ContentPlaceHolder1_chkis_demo" class="form-check-input">
                                            demo account? <span class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-allow_entry_ahbl">
                                        <input type="hidden" name="Mcxusers[allow_entry_ahbl]"
                                            value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkallow_orders_beyond_high_low"
                                                type="checkbox" id="ContentPlaceHolder1_chkallow_orders_beyond_high_low"
                                                class="form-check-input" checked="checked">
                                            Allow Fresh Entry Order above high &amp; below low? <span
                                                class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-aobhl">
                                        <input type="hidden" name="Mcxusers[aobhl]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkallow_orders_between_high_low"
                                                type="checkbox" id="ContentPlaceHolder1_chkallow_orders_between_high_low"
                                                class="form-check-input" checked="checked">
                                            Allow Orders between High - Low? <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-trade_equity_as_units">

                                        <input type="hidden" name="Mcxusers[trade_equity_as_units]"
                                            value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$trade_equity_as_units" type="checkbox"
                                                id="ContentPlaceHolder1_trade_equity_as_units" class="form-check-input"
                                                value="1" checked="checked" onchange="quantity_lots();">
                                            Trade equity as units instead of lots. <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>

                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-status">
                                        <input type="hidden" name="Mcxusers[status]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkstatus" type="checkbox"
                                                id="ContentPlaceHolder1_chkstatus" class="form-check-input"
                                                checked="checked">
                                            Account Status <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-status">
                                        <label>

                                            <input name="ctl00$ContentPlaceHolder1$chkauto_square_off" type="checkbox"
                                                id="ContentPlaceHolder1_chkauto_square_off" class="form-check-input"
                                                checked="checked">
                                            Auto Close Trades if condition met <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_close_at">
                                        <label class="control-label" for="mcxusers-auto_square_close_at">auto-Close all
                                            active trades when the losses reach % of Ledger-balance</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtauto_square_off_percentage"
                                            type="text" id="ContentPlaceHolder1_txtauto_square_off_percentage"
                                            class="form-control" value="90">
                                        <div class="hint-block">Example: 95, will close when losses reach 95% of ledger
                                            balance</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="mcxusers-auto_square_notify_at">Notify client
                                            when the losses reach % of Ledger-balance</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtnotify_percentage" type="text"
                                            id="ContentPlaceHolder1_txtnotify_percentage" class="form-control"
                                            value="70">
                                        <div class="hint-block">Example: 70, will send notification to customer every
                                            5-minutes until losses cross 70% of ledger balance</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-auto_square_notify_at">
                                        <label class="control-label" for="mcxusers-auto_square_notify_at">Min. Time to
                                            book profit (No. of Seconds)</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtprofit_book_interval" type="text"
                                            id="ContentPlaceHolder1_txtprofit_book_interval" class="form-control"
                                            value="0">
                                        <div class="hint-block">Example: 120, will hold the trade for 2 minutes before
                                            closing a trade in profit</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">MCX Futures</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-commodity">
                                        <input type="hidden" name="Mcxusers[commodity]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkmcx_enabled" type="checkbox"
                                                id="ContentPlaceHolder1_chkmcx_enabled" class="form-check-input"
                                                value="1" checked="checked">
                                            MCX Trading <span class="form-check-sign"><span class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_commodity">
                                        <label class="control-label" for="mcxusersmin_size_trade_commodity">Minimum lot
                                            size required per single trade of MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_min_lot_per_trade" type="text"
                                            id="ContentPlaceHolder1_txtmcx_min_lot_per_trade" class="form-control"
                                            value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_commodity">
                                        <label class="control-label" for="mcxusers-max_size_trade_commodity">Maximum lot
                                            size allowed per single trade of MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_max_lot_per_trade" type="text"
                                            id="ContentPlaceHolder1_txtmcx_max_lot_per_trade" class="form-control"
                                            value="5">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_commodity">
                                        <label class="control-label" for="mcxusers-max_size_script_commodity">Maximum lot
                                            size allowed per script of MCX to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_max_lot_per_scrip" type="text"
                                            id="ContentPlaceHolder1_txtmcx_max_lot_per_scrip" class="form-control"
                                            value="15">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_commodity bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_commodity">Max Size All Commodity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmax_commodity_lots" type="text"
                                            id="ContentPlaceHolder1_txtmax_commodity_lots" class="form-control"
                                            value="30">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="mcx_brokerage_type">Mcx Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddlmcx_brokerage_type"
                                                id="ContentPlaceHolder1_ddlmcx_brokerage_type"
                                                onchange="toggle_brokerage_type();">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option selected="selected" value="per_crore">Per Crore Basis</option>
                                                <option value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div id="per_crore" class="col-md-6" style="">
                                    <div class="form-group field-mcxusers-commodity_brokerage">
                                        <label class="control-label" for="mcxusers-commodity_brokerage">MCX
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_brokerage" type="text"
                                            id="ContentPlaceHolder1_txtmcx_brokerage" class="form-control"
                                            value="1000.0000" required="required">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_mcx_type">
                                        <label class="control-label" for="mcxusers-exposure_mcx_type">Exposure Mcx
                                            Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddlexposure_type"
                                                id="ContentPlaceHolder1_ddlexposure_type" onchange="toggle_exposure();">
                                                <option value="">Select Margin/Exposure Calculation type</option>
                                                <option selected="selected" value="per_turnover">Per Turnover Basis
                                                </option>
                                                <option value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_mcx">
                                        <label class="control-label" for="mcxusers-exposure_mcx">Intraday Exposure/Margin
                                            MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_intraday_margin" type="text"
                                            id="ContentPlaceHolder1_txtmcx_intraday_margin" class="form-control"
                                            value="500">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade devided by Exposure is
                                            required margin. eg. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_mcx">
                                        <label class="control-label" for="mcxusers-holding_exposure_mcx">Holding
                                            Exposure/Margin MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcx_holding_margin" type="text"
                                            id="ContentPlaceHolder1_txtmcx_holding_margin" class="form-control"
                                            value="70">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade devided by Exposure is required margin. eg. if gold having
                                            lotsize of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div id="exposure_per_lot" class="row" style="display: none;">
                                    <div class="mt-2 pt-2 col-12">
                                        <label>MCX Exposure Lot wise: </label>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-bulldex bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-bulldex">BULLDEX INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcx_lot_margin" type="text"
                                                id="ContentPlaceHolder1_txtmcx_lot_margin" class="form-control"
                                                value="1000000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-bulldex bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-bulldex">BULLDEX HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotbulldex"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotbulldex"
                                                class="form-control" value="1000000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-gold bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-gold">GOLD INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$mcxusersexposure_mcx_lotgold"
                                                type="text" id="ContentPlaceHolder1_mcxusersexposure_mcx_lotgold"
                                                class="form-control" value="10000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-gold bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-gold">GOLD HOLDING</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotgold"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotgold"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-silver bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-silver">SILVER INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$mcxusersexposure_mcx_lotsilver"
                                                type="text" id="ContentPlaceHolder1_mcxusersexposure_mcx_lotsilver"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-silver bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-silver">SILVER HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotsilver"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotsilver"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-crudeoil">CRUDEOIL INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotcrudeoil87"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotcrudeoil87"
                                                class="form-control" value="1000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusersholding_exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-crudeoil">CRUDEOIL HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotcrudeoil"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotcrudeoil"
                                                class="form-control" value="20000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-crudeoil">CRUDEOIL MINI INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotcrudeoil"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotcrudeoil"
                                                class="form-control" value="200" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-crudeoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-crudeoil">CRUDEOIL MINI
                                                HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotcrudeoil1"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotcrudeoil1"
                                                class="form-control" value="2000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-copper bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-copper">COPPER INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotcopper"
                                                type="text" id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotcopper"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-copper bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-copper">COPPER HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotcopper"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotcopper"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-nickel bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-nickel">NICKEL INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotnickel"
                                                type="text" id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotnickel"
                                                class="form-control" value="1000000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-nickel bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-nickel">NICKEL HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotnickel"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotnickel"
                                                class="form-control" value="1000000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-zinc bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-zinc">ZINC INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotzinc"
                                                type="text" id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotzinc"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-zinc bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-zinc">ZINC HOLDING</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotzinc"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotzinc"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-zinc bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-zincmini">ZINCMINI INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotzincmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotzincmini"
                                                class="form-control" value="2500" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-zinc bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-zincmini">ZINCMINI HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotzincmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotzincmini"
                                                class="form-control" value="10000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-lead bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-lead">LEAD INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotlead"
                                                type="text" id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotlead"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-lead bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-lead">LEAD HOLDING</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotlead"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotlead"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-lead bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-lead">LEADMINI INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotleadmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotleadmini"
                                                class="form-control" value="500" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-lead bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-lead">LEADMINI HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotleadmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotleadmini"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-aluminium">ALUMINIUM INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotaluminium"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotaluminium"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-aluminium">ALUMINIUM HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotaluminium"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotaluminium"
                                                class="form-control" value="25000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-alumini">ALUMINI INTRADAY</label>
                                            <input type="text" id="mcxusers-exposure_mcx_lot-alumini"
                                                class="form-control" name="mcx_lot_margin[ALUMINI][INTRADAY]"
                                                value="250" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-aluminium bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-alumini">ALUMINI HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotalumini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotalumini"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-naturalgas">NATURALGAS INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotnaturalgas"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotnaturalgas"
                                                class="form-control" value="2500" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-naturalgas">NATURALGAS
                                                HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotnaturalgas"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotnaturalgas"
                                                class="form-control" value="20000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-naturalgas">NATURALGAS MINI INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$mcxusersexposure_mcx_lotnaturalgas"
                                                type="text" id="ContentPlaceHolder1_mcxusersexposure_mcx_lotnaturalgas"
                                                class="form-control" value="500" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-naturalgas bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-naturalgas">NATURALGAS MINI
                                                HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotnaturalgas1"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotnaturalgas1"
                                                class="form-control" value="2500" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-menthaoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-menthaoil">MENTHAOIL INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotmenthaoil"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotmenthaoil"
                                                class="form-control" value="0" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-menthaoil bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-menthaoil">MENTHAOIL HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotmenthaoil"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotmenthaoil"
                                                class="form-control" value="0" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-cotton bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-cotton">COTTON INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotcotton"
                                                type="text" id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotcotton"
                                                class="form-control" value="0" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-cotton bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-cotton">COTTON HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotcotton"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotcotton"
                                                class="form-control" value="0" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-gold-mini bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-gold-mini">GOLDM INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotgoldmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotgoldmini"
                                                class="form-control" value="1000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-gold-mini bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-gold-mini">GOLDM HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotgoldmini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotgoldmini"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-silver-mini bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-silver-mini">SILVERM INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotsilvermini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotsilvermini"
                                                class="form-control" value="300" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-silver-mini bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-silver-mini">SILVERM HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotsilvermini"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotsilvermini"
                                                class="form-control" value="5000" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-exposure_mcx_lot-silvermic bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-exposure_mcx_lot-silvermic">SILVER MIC INTRADAY</label>
                                            <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_mcx_lotsilvermic"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersexposure_mcx_lotsilvermic"
                                                class="form-control" value="200" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="form-group field-mcxusers-holding_exposure_mcx_lot-silvermic bmd-form-group is-filled">
                                            <label class="control-label bmd-label-static"
                                                for="mcxusers-holding_exposure_mcx_lot-silvermic">SILVER MIC
                                                HOLDING</label>
                                            <input
                                                name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_mcx_lotsilvermic"
                                                type="text"
                                                id="ContentPlaceHolder1_txtmcxusersholding_exposure_mcx_lotsilvermic"
                                                class="form-control" value="200" required="required">

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12" id="mcx_lot_brokerage_div">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>MCX Lot Wise Brokerage: </label>
                                        </div>
                                        <div class="col-6">
                                            <label>GOLDM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$txtmcx_lot_brokerageGOLDM"
                                                    type="text" id="ContentPlaceHolder1_txtmcx_lot_brokerageGOLDM"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVERM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$txtmcx_lot_brokerageSILVERM"
                                                    type="text" id="ContentPlaceHolder1_txtmcx_lot_brokerageSILVERM"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>BULLDEX: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$txtmcx_lot_brokerageMCXBULLDEX"
                                                    type="text" id="ContentPlaceHolder1_txtmcx_lot_brokerageMCXBULLDEX"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>GOLD: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageGOLD"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageGOLD"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVER: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageSILVER"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageSILVER"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>CRUDEOIL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageCRUDEOIL"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageCRUDEOIL"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>COPPER: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageCOPPER"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageCOPPER"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NICKEL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageNICKEL"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageNICKEL"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ZINC: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageZINC"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageZINC"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>LEAD: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageLEAD"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageLEAD"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NATURALGAS: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageNATURALGAS"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageNATURALGAS"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NATURALGAS MINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageNATGASMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageNATGASMINI"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ALUMINIUM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageALUMINIUM"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageALUMINIUM"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>MENTHAOIL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageMENTHAOIL"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageMENTHAOIL"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>COTTON: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageCOTTON"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageCOTTON"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVERMIC: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageSILVERMIC"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageSILVERMIC"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ZINCMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageZINCMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageZINCMINI"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ALUMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageALUMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageALUMINI"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>LEADMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageLEADMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageLEADMINI"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>CRUDEOIL MINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_lot_brokerageCRUDEOILM"
                                                    type="text" id="ContentPlaceHolder1_mcx_lot_brokerageCRUDEOILM"
                                                    class="form-control" value="0.0000"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12" style="display:none">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Orders to be away by points in each scrip MCX: </label>
                                        </div>
                                        <div class="col-6">
                                            <label>GOLDM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapGOLDM" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapGOLDM" class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVERM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapSILVERM" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapSILVERM"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>BULLDEX: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapMCXBULLDEX"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapMCXBULLDEX"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>GOLD: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapGOLD" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapGOLD" class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVER: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapSILVER" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapSILVER"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>CRUDEOIL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapCRUDEOIL" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapCRUDEOIL"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>COPPER: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapCOPPER" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapCOPPER"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NICKEL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapNICKEL" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapNICKEL"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ZINC: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapZINC" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapZINC" class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>LEAD: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapLEAD" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapLEAD" class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NATURALGAS: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapNATURALGAS"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapNATURALGAS"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>NATURALGAS MINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapNATGASMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapNATGASMINI"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ALUMINIUM: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapALUMINIUM"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapALUMINIUM"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>MENTHAOIL: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapMENTHAOIL"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapMENTHAOIL"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>COTTON: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapCOTTON" type="text"
                                                    id="ContentPlaceHolder1_mcx_bid_gapCOTTON"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>SILVERMIC: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapSILVERMIC"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapSILVERMIC"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ZINCMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapZINCMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapZINCMINI"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>ALUMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapALUMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapALUMINI"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>LEADMINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapLEADMINI"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapLEADMINI"
                                                    class="form-control"></span>
                                        </div>
                                        <div class="col-6">
                                            <label>CRUDEOIL MINI: </label>
                                            <span class="bmd-form-group is-filled">
                                                <input name="ctl00$ContentPlaceHolder1$mcx_bid_gapCRUDEOILM"
                                                    type="text" id="ContentPlaceHolder1_mcx_bid_gapCRUDEOILM"
                                                    class="form-control"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Equity Futures</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <input type="hidden" name="Mcxusers[equity]" value="0"><label><input
                                                name="ctl00$ContentPlaceHolder1$chkmcxusersequity" type="checkbox"
                                                id="ContentPlaceHolder1_chkmcxusersequity" class="form-check-input"
                                                value="1" checked="checked">
                                            Equity Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Equity brokerage
                                            Per Crore</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage"
                                            class="form-control" value="1000.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_equity">
                                        <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum <span
                                                class="quantity_lot">lot</span> size required per single trade of
                                            Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_equity"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_equity">
                                        <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum <span
                                                class="quantity_lot">lot</span> size allowed per single trade of
                                            Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_equity1"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_equity1"
                                            class="form-control" value="5">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_index">
                                        <label class="control-label" for="mcxusers-min_size_trade_index">Minimum <span
                                                class="quantity_lot">lot</span> size required per single trade of Equity
                                            INDEX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_index"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_index"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_index">
                                        <label class="control-label" for="mcxusers-max_size_trade_index">Maximum <span
                                                class="quantity_lot">lot</span> size allowed per single trade of Equity
                                            INDEX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_index1"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_index1"
                                            class="form-control" value="20">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_equity">
                                        <label class="control-label" for="mcxusers-max_size_script_equity">Maximum <span
                                                class="quantity_lot">lot</span> size allowed per script of Equity to be
                                            actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_equity"
                                            class="form-control" value="20">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_index">
                                        <label class="control-label" for="mcxusers-max_size_script_index">Maximum <span
                                                class="quantity_lot">lot</span> size allowed per script of Equity INDEX to
                                            be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_index"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_index"
                                            class="form-control" value="100">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_equity">Max Size All Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_equity"
                                            class="form-control" value="50">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_index">Max Size All Index</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_index12"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_index12"
                                            class="form-control" value="100">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersexposure_equity" type="text"
                                            id="ContentPlaceHolder1_mcxusersexposure_equity" class="form-control"
                                            value="500">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade devided by Exposure is
                                            required margin. eg. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$mcxusersholding_exposure_equity"
                                            type="text" id="ContentPlaceHolder1_mcxusersholding_exposure_equity"
                                            class="form-control" value="70">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade devided by Exposure is required margin. eg. if gold having
                                            lotsize of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-eqaway bmd-form-group">
                                        <label class="control-label bmd-label-static" for="mcxusers-eqaway">Orders to be
                                            away by % from current price Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxuserseqaway" type="text"
                                            id="ContentPlaceHolder1_txtmcxuserseqaway" class="form-control"
                                            value="0.00">

                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Options Config</h4>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="ctl00$ContentPlaceHolder1$chkmcxusersequity1" type="checkbox"
                                                id="ContentPlaceHolder1_chkmcxusersequity1" class="form-check-input"
                                                value="1" checked="checked">
                                            Index Options Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="ctl00$ContentPlaceHolder1$chkmcxusersequity2" type="checkbox"
                                                id="ContentPlaceHolder1_chkmcxusersequity2" class="form-check-input"
                                                value="1" checked="checked">
                                            Equity Options Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="px-3 form-check col-md-6">
                                    <div class="form-group field-mcxusers-equity">
                                        <label>
                                            <input name="ctl00$ContentPlaceHolder1$chkmcxusersequity3" type="checkbox"
                                                id="ContentPlaceHolder1_chkmcxusersequity3" class="form-check-input"
                                                value="1" checked="checked">
                                            MCX Options Trading <span class="form-check-sign"><span
                                                    class="check"></span></span>
                                        </label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_brokerage_type">Options Index
                                            Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_brokerage_type"
                                                id="ContentPlaceHolder1_ddloptions_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Index
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage1"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage1"
                                            class="form-control" value="25.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_brokerage_type">Options Equity
                                            Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_equity_brokerage_type"
                                                id="ContentPlaceHolder1_ddloptions_equity_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Equity
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage2"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage2"
                                            class="form-control" value="20.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_brokerage_type">Options MCX Brokerage
                                            Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_mcx_brokerage_type"
                                                id="ContentPlaceHolder1_ddloptions_mcx_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options MCX
                                            brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage3"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage3"
                                            class="form-control" value="20.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Min. Bid
                                            Price</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage4"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage4"
                                            class="form-control" value="1.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Index Short
                                            Selling Allowed (Sell First and Buy later)</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_short_selling_allowed"
                                                id="ContentPlaceHolder1_ddloptions_short_selling_allowed"
                                                onchange="shortselling_config(this.value);">
                                                <option value="0">No</option>
                                                <option selected="selected" value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Equity
                                            Short Selling Allowed (Sell First and Buy later)</label>
                                        <div class="dropdown">
                                            <select
                                                name="ctl00$ContentPlaceHolder1$ddloptions_equity_short_selling_allowed"
                                                id="ContentPlaceHolder1_ddloptions_equity_short_selling_allowed"
                                                onchange="shortselling_config(this.value);">
                                                <option value="0">No</option>
                                                <option selected="selected" value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">MCX Options Short
                                            Selling Allowed (Sell First and Buy later)</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_mcx_short_selling_allowed"
                                                id="ContentPlaceHolder1_ddloptions_mcx_short_selling_allowed"
                                                onchange="shortselling_config(this.value);">
                                                <option value="0">No</option>
                                                <option selected="selected" value="1">Yes</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_equity">
                                        <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot
                                            size required per single trade of Equity Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_equity5"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_equity5"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_equity">
                                        <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot
                                            size allowed per single trade of Equity Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_equity"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_index">
                                        <label class="control-label" for="mcxusers-min_size_trade_index">Minimum lot
                                            size required per single trade of Equity INDEX Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_index6"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_index6"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_index">
                                        <label class="control-label" for="mcxusers-max_size_trade_index">Maximum lot
                                            size allowed per single trade of Equity INDEX Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_index"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_index"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_equity">
                                        <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot
                                            size required per single trade of MCX Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_equity8"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_equity8"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_equity">
                                        <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot
                                            size allowed per single trade of MCX Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_equity9"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_equity9"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_equity">
                                        <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot
                                            size allowed per scrip of Equity Options to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_equity0"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_equity0"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_index">
                                        <label class="control-label" for="mcxusers-max_size_script_index">Maximum lot
                                            size allowed per scrip of Equity INDEX Options to be actively open at a
                                            time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_index10"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_index10"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_equity">
                                        <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot
                                            size allowed per scrip of MCX Options to be actively open at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_equity11"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_equity11"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_equity">Max Size All Equity Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_equity12"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_equity12"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_index">Max Size All Index Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_index"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_index"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_index">Max Size All MCX Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_index1"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_index1"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options Index</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity"
                                            class="form-control" value="5">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options Index</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity"
                                            class="form-control" value="2">
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
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity14"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity14"
                                            class="form-control" value="5">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options Equity</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity16"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity16"
                                            class="form-control" value="2">
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
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity45"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity45"
                                            class="form-control" value="5">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options MCX</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity65"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity65"
                                            class="form-control" value="2">
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
                                            away by % from current price in Options</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxuserseqaway2" type="text"
                                            id="ContentPlaceHolder1_txtmcxuserseqaway2" class="form-control"
                                            value="0.00">

                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Options Shortselling Config</h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_ss_brokerage_type">Options Index
                                            Shortselling Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_ss_brokerage_type"
                                                id="ContentPlaceHolder1_ddloptions_ss_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Index
                                            Shortselling brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage37"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage37"
                                            class="form-control" value="50.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_ss_brokerage_type">Options Equity
                                            Shortselling Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$options_ss_equity_brokerage_type"
                                                id="ContentPlaceHolder1_options_ss_equity_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options Equity
                                            Shortselling brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage54"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage54"
                                            class="form-control" value="50.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-mcx_brokerage_type">
                                        <label class="control-label" for="options_ss_brokerage_type">Options MCX
                                            Shortselling Brokerage Type</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddloptions_ss_mcx_brokerage_type"
                                                id="ContentPlaceHolder1_ddloptions_ss_mcx_brokerage_type">
                                                <option value="">Select Brokerage Calculation type</option>
                                                <option value="per_crore">Per Crore Basis</option>
                                                <option selected="selected" value="per_lot">Per Lot Basis</option>
                                            </select>
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-equity_brokerage">
                                        <label class="control-label" for="mcxusers-equity_brokerage">Options MCX
                                            Shortselling brokerage</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage21"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage21"
                                            class="form-control" value="50.0000">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_equity">
                                        <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot
                                            size required per single trade of Equity Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersequity_brokerage22"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersequity_brokerage22"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_equity">
                                        <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot
                                            size allowed per single trade of Equity Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_equity25"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_equity25"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_equity">
                                        <label class="control-label" for="mcxusers-min_size_trade_equity">Minimum lot
                                            size required per single trade of MCX Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_equity58"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_equity58"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_equity">
                                        <label class="control-label" for="mcxusers-max_size_trade_equity">Maximum lot
                                            size allowed per single trade of MCX Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_equity78"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_equity78"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-min_size_trade_index">
                                        <label class="control-label" for="mcxusers-min_size_trade_index">Minimum lot
                                            size required per single trade of Equity INDEX Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmin_size_trade_index98"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmin_size_trade_index98"
                                            class="form-control" value="0">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_trade_index">
                                        <label class="control-label" for="mcxusers-max_size_trade_index">Maximum lot
                                            size allowed per single trade of Equity INDEX Options Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_trade_index55"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_trade_index55"
                                            class="form-control" value="25">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_equity">
                                        <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot
                                            size allowed per scrip of Equity Options Shortselling to be actively open at a
                                            time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_equity45"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_equity45"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_index">
                                        <label class="control-label" for="mcxusers-max_size_script_index">Maximum lot
                                            size allowed per scrip of Equity INDEX Options Shortselling to be actively open
                                            at a time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_index85"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_index85"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_script_equity">
                                        <label class="control-label" for="mcxusers-max_size_script_equity">Maximum lot
                                            size allowed per scrip of MCX Options Shortselling to be actively open at a
                                            time</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_script_equity41"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_script_equity41"
                                            class="form-control" value="50">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_equity bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_equity">Max Size All Equity Options
                                            Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_equity74"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_equity74"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_index">Max Size All Index Options
                                            Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_index54"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_index54"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-max_size_all_index bmd-form-group">
                                        <label class="control-label bmd-label-static"
                                            for="mcxusers-max_size_all_index">Max Size All MCX Options
                                            Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersmax_size_all_index52"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersmax_size_all_index52"
                                            class="form-control" value="200">

                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options Index Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity74"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity74"
                                            class="form-control" value="1">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options Index Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity75"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity75"
                                            class="form-control" value="1">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. e.g. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options Equity Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity56"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity56"
                                            class="form-control" value="1">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options Equity Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity85"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity85"
                                            class="form-control" value="1">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. e.g. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-exposure_equity">
                                        <label class="control-label" for="mcxusers-exposure_equity">Intraday
                                            Exposure/Margin Options MCX Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersexposure_equity42"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersexposure_equity42"
                                            class="form-control" value="1">
                                        <div class="hint-block">Exposure auto calculates the margin money required for any
                                            new trade entry. Calculation : turnover of a trade divided by Exposure is
                                            required margin. e.g. if gold having lotsize of 100 is trading @ 45000 and
                                            exposure is 200, (45000 X 100) / 200 = 22500 is required to initiate the trade.
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-holding_exposure_equity">
                                        <label class="control-label" for="mcxusers-holding_exposure_equity">Holding
                                            Exposure/Margin Options MCX Shortselling</label>
                                        <input name="ctl00$ContentPlaceHolder1$txtmcxusersholding_exposure_equity00"
                                            type="text" id="ContentPlaceHolder1_txtmcxusersholding_exposure_equity00"
                                            class="form-control" value="30000">
                                        <div class="hint-block">Holding Exposure auto calculates the margin money required
                                            to hold a position overnight for the next market working day. Calculation :
                                            turnover of a trade divided by Exposure is required margin. e.g. if gold having
                                            lot size of 100 is trading @ 45000 and holding exposure is 800, (45000 X 100) /
                                            80 = 56250 is required to hold position overnight. System automatically checks
                                            at a given time around market closure to check and close all trades if
                                            margin(M2M) insufficient.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>


                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="bg-info pl-2">Other</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-notes">
                                        <label class="control-label" for="mcxusers-notes">Notes</label>
                                        <textarea name="ctl00$ContentPlaceHolder1$txtmcxusersnotes" id="ContentPlaceHolder1_txtmcxusersnotes"
                                            class="form-control" rows="6"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-referedby">
                                        <label class="control-label" for="mcxusers-referedby">Broker</label>
                                        <div class="dropdown">
                                            <select name="ctl00$ContentPlaceHolder1$ddlbroker_id"
                                                id="ContentPlaceHolder1_ddlbroker_id">
                                                <option value="0">Select User</option>
                                                <option value="1012">amit</option>
                                                <option value="1006">deepak</option>
                                                <option value="1007">mohan</option>
                                                <option value="1009">ram</option>
                                                <option value="2003">rhgrfh</option>
                                                <option value="1011">rohan</option>
                                                <option value="1005">rupesh</option>
                                                <option value="1">SANDEEP</option>
                                                <option value="1004">satish</option>
                                                <option value="1003">Sherlock</option>
                                                <option value="1008">sohan</option>
                                                <option value="1013">sumit</option>

                                            </select>

                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group field-mcxusers-phone">
                                        <label class="control-label" for="mcxusers-phone">Transaction Password</label>
                                        <input name="ctl00$ContentPlaceHolder1$txttranspass" type="text"
                                            id="ContentPlaceHolder1_txttranspass" class="form-control">

                                        <div class="hint-block"></div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button onclick="__doPostBack('ctl00$ContentPlaceHolder1$btnsubmit','')"
                                        id="ContentPlaceHolder1_btnsubmit" type="submit" name="submit"
                                        class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
