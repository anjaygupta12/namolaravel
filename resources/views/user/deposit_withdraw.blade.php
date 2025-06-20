@extends('layouts.user')

@section('content')
<div id="appCapsule">
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Pending" role="tabpanel">
                    <h3 class="text-center">Deposit/Withdraw</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <h4 class="comodity mt-1">Withdraw Requests</h4>
                                    </td>
                                    <td></td>
                                    <td class="text-end text-primary">
                                        <a href="{{ url('withdrawal_requests') }}" class="btn btn-primary mb-1" style="border-radius: 0">Withdraw Requests</a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h4 class="comodity mt-1">Withdraw Funds</h4>
                                    </td>
                                    <td></td>
                                    <td class="text-end text-primary">
                                        <a href="{{ route('withdrawal.requests.form') }}" class="btn btn-primary mb-1" style="border-radius: 0">Withdraw Funds</a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <h4 class="comodity mt-1">Deposit Request</h4>
                                    </td>
                                    <td></td>
                                    <td class="text-end text-primary">
                                        <a href="{{ route('deposit.request.form') }}" class="btn btn-primary mb-1" style="border-radius: 0">Deposit Request</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

