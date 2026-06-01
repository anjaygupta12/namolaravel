@extends('layouts.admin')

@section('title', 'Market Calendar')

@section('content')
    <div class="content">
        <div class="container-fluid">

            {{-- Success / Error --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.calendar.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Market Calendar Settings</h4>
                    </div>

                    <div class="card-body">

                        {{-- ================== NSE TIME ================== --}}
                        <h5 class="mb-3">NSE Timing</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="nse_start_time" class="form-control"
                                    value="{{ $nseTime->start_time ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="nse_end_time" class="form-control"
                                    value="{{ $nseTime->end_time ?? '' }}" required>
                            </div>
                        </div>

                        <hr>

                        {{-- ================== MCX TIME ================== --}}
                        <h5 class="mb-3">MCX Timing</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="mcx_start_time" class="form-control"
                                    value="{{ $mcxTime->start_time ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="mcx_end_time" class="form-control"
                                    value="{{ $mcxTime->end_time ?? '' }}" required>
                            </div>
                        </div>

                        <hr>

                        {{-- ================== HOLIDAYS ================== --}}
                        <h5 class="mb-3">Market Holidays</h5>

                        <div id="holiday-wrapper">

                            @if (isset($holidays) && count($holidays))
                                @foreach ($holidays as $row)
                                    <div class="row holiday-row mb-2" data-id="{{ $row->id }}">
                                        <input type="hidden" name="holiday_ids[]" value="{{ $row->id }}">

                                        <div class="col-md-3">
                                            <select name="holiday_market[]" class="form-control" required>
                                                <option value="">Select Market</option>
                                                <option value="NSE" {{ $row->market == 'NSE' ? 'selected' : '' }}>NSE
                                                </option>
                                                <option value="MCX" {{ $row->market == 'MCX' ? 'selected' : '' }}>MCX
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="date" name="holidays[]" class="form-control"
                                                value="{{ $row->holiday_date }}" required>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" name="holiday_titles[]" class="form-control"
                                                value="{{ $row->title }}">
                                        </div>

                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-holiday db-remove"
                                                data-id="{{ $row->id }}">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Empty row --}}
                                <div class="row holiday-row mb-2">
                                    <div class="col-md-3">
                                        <select name="holiday_market[]" class="form-control" required>
                                            <option value="">Select Market</option>
                                            <option value="NSE">NSE</option>
                                            <option value="MCX">MCX</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="holidays[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="holiday_titles[]" class="form-control"
                                            placeholder="Holiday Name">
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            @endif

                        </div>

                        <button type="button" id="addHoliday" class="btn btn-secondary mt-2">
                            + Add More Holiday
                        </button>

                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Save Settings</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.getElementById('addHoliday').addEventListener('click', function() {

            let html = `
        <div class="row holiday-row mb-2">
            <div class="col-md-3">
                <select name="holiday_market[]" class="form-control" required>
                    <option value="">Select Market</option>
                    <option value="NSE">NSE</option>
                    <option value="MCX">MCX</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="holidays[]" class="form-control" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="holiday_titles[]" class="form-control" placeholder="Holiday Name">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-holiday">Remove</button>
            </div>
        </div>
    `;

            document.getElementById('holiday-wrapper').insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-holiday')) {
                e.target.closest('.holiday-row').remove();
            }
        });
    </script>

    <script>
document.addEventListener('click', function(e){

    if(e.target.classList.contains('db-remove')){
        let btn = e.target;
        let id = btn.getAttribute('data-id');

        if(!confirm('Are you sure to delete this holiday?')) return;

        fetch("{{ url('admin/delete-calendar-holiday') }}/" + id, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status){
                btn.closest('.holiday-row').remove();
            } else {
                alert('Delete failed');
            }
        })
        .catch(err => console.error(err));
    }

    if(e.target.classList.contains('remove-holiday') && !e.target.classList.contains('db-remove')){
        e.target.closest('.holiday-row').remove();
    }

});

</script>

@endsection
