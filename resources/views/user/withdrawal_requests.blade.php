{{-- resources/views/withdrawal_requests.blade.php --}}
@extends('layouts.user')

@section('head')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="Pending" role="tabpanel">
                        <h3 class="text-center">Withdraw Request</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="tblbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            loadRequests();
        });

        function loadRequests() {
            $.ajax({
                type: 'GET',
                url: '{{ route('withdrawal.requests') }}',
                data: {},
                dataType: 'json',

                success: function(rows) {
                    let html = '';
                    console.log(rows);
                    $('#tblbody').empty();

                    rows.forEach(function(row) {
                        html += `
<tr>
    <td>
        <p class="date">
            <span class="badge badge-success">${row.PK_Id}</span>
        </p>
        <h4 class="comodity mt-1">Amount: ${row.Amount}</h4>
        <p class="date mt-1">A/c Holder: ${row.AccountHolder}</p>
        <p class="detail mt-1">IFSC: ${row.IFSC}</p>
        <p class="detail mt-1">Mobile: ${row.Mobile}</p>
        <p class="detail mt-1 mb-1">${row.Status}</p>
    </td>
    <td></td>
    <td class="text-end text-primary">
        <p class="date mb-1">${row.FormattedTimestamp}</p>
        <p class="text-white fw-bold mb-1">${row.Status}</p>
        <p class="text-white fw-bold mb-1">A/c No.: ${row.AccountNo}</p>
        <p class="text-white fw-bold mb-1">Payment mode: ${row.PaymentMethod}</p>
        <p class="text-white fw-bold mb-1">UPI ID: ${row.Mobile}</p>
    </td>
</tr>`;
                    });

                    $('#tblbody').html(html);
                },
                error: function() {
                    $('#tblbody').html(
                        `<tr><td colspan="3" class="text-center text-danger">Unable to load data.</td></tr>`
                    );
                }
            });
        }
    </script>
@endsection
