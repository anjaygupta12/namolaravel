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
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="nse_start_time" class="form-control"
                                    value="{{ $nseTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="nse_end_time" class="form-control"
                                    value="{{ $nseTime->end_time ?? '' }}" >
                            </div>
                        </div>

                        <hr>

                        {{-- ================== MCX TIME ================== --}}
                        <h5 class="mb-3">MCX Timing</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="mcx_start_time" class="form-control"
                                    value="{{ $mcxTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="mcx_end_time" class="form-control"
                                    value="{{ $mcxTime->end_time ?? '' }}" >
                            </div>
                        </div>

                        <hr>

                        {{-- ================== OPTIONS TIME ================== --}}
                        <h5 class="mb-3">Options Timing</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="options_start_time" class="form-control"
                                    value="{{ $optionsTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="options_end_time" class="form-control"
                                    value="{{ $optionsTime->end_time ?? '' }}" >
                            </div>
                        </div>

                        <hr>

                        {{-- ================== COMIX TIME ================== --}}
                        <h5 class="mb-3">Comix Timing</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="comix_start_time" class="form-control"
                                    value="{{ $comixTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="comix_end_time" class="form-control"
                                    value="{{ $comixTime->end_time ?? '' }}" >
                            </div>
                        </div>

                        <hr>

                        {{-- ================== FOREX TIME ================== --}}
                        <h5 class="mb-3">Forex Timing</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="forex_start_time" class="form-control"
                                    value="{{ $forexTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="forex_end_time" class="form-control"
                                    value="{{ $forexTime->end_time ?? '' }}" >
                            </div>
                        </div>

                        <hr>

                        {{-- ================== CRYPTO TIME ================== --}}
                        <h5 class="mb-3">Crypto Timing</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Start Time</label>
                                <input type="time" name="crypto_start_time" class="form-control"
                                    value="{{ $cryptoTime->start_time ?? '' }}" >
                            </div>
                            <div class="col-md-6">
                                <label>End Time</label>
                                <input type="time" name="crypto_end_time" class="form-control"
                                    value="{{ $cryptoTime->end_time ?? '' }}" >
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
                                            <select name="holiday_market[]" class="form-control" >
                                                <option value="">Select Market</option>
                                                <option value="NSE"     {{ $row->market == 'NSE'     ? 'selected' : '' }}>NSE</option>
                                                <option value="MCX"     {{ $row->market == 'MCX'     ? 'selected' : '' }}>MCX</option>
                                                <option value="OPTIONS" {{ $row->market == 'OPTIONS' ? 'selected' : '' }}>Options</option>
                                                <option value="COMIX"   {{ $row->market == 'COMIX'   ? 'selected' : '' }}>Comix</option>
                                                <option value="FOREX"   {{ $row->market == 'FOREX'   ? 'selected' : '' }}>Forex</option>
                                                <option value="CRYPTO"  {{ $row->market == 'CRYPTO'  ? 'selected' : '' }}>Crypto</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="date" name="holidays[]" class="form-control"
                                                value="{{ $row->holiday_date }}" >
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" name="holiday_titles[]" class="form-control"
                                                value="{{ $row->title }}" placeholder="Holiday Name">
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
                                        <select name="holiday_market[]" class="form-control" >
                                            <option value="">Select Market</option>
                                            <option value="NSE">NSE</option>
                                            <option value="MCX">MCX</option>
                                            <option value="OPTIONS">Options</option>
                                            <option value="COMIX">Comix</option>
                                            <option value="FOREX">Forex</option>
                                            <option value="CRYPTO">Crypto</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="holidays[]" class="form-control" >
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
        // ── Market dropdown HTML shared across "Add More" rows ──
        const marketOptions = `
            <option value="">Select Market</option>
            <option value="NSE">NSE</option>
            <option value="MCX">MCX</option>
            <option value="OPTIONS">Options</option>
            <option value="COMIX">Comix</option>
            <option value="FOREX">Forex</option>
            <option value="CRYPTO">Crypto</option>
        `;

        // ── Add new holiday row ──
        document.getElementById('addHoliday').addEventListener('click', function () {
            const html = `
                <div class="row holiday-row mb-2">
                    <div class="col-md-3">
                        <select name="holiday_market[]" class="form-control" >
                            ${marketOptions}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="holidays[]" class="form-control" >
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="holiday_titles[]" class="form-control" placeholder="Holiday Name">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-holiday">Remove</button>
                    </div>
                </div>`;
            document.getElementById('holiday-wrapper').insertAdjacentHTML('beforeend', html);
        });

        // ── Remove / DB-delete holiday rows ──
        document.addEventListener('click', function (e) {

            // DB record – call AJAX then remove row
            if (e.target.classList.contains('db-remove')) {
                const btn = e.target;
                const id  = btn.getAttribute('data-id');

                if (!confirm('Are you sure to delete this holiday?')) return;

                fetch("{{ url('admin/delete-calendar-holiday') }}/" + id, { method: 'GET' })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            btn.closest('.holiday-row').remove();
                        } else {
                            alert('Delete failed');
                        }
                    })
                    .catch(err => console.error(err));

                return; // stop here so next block doesn't also fire
            }

            // New (unsaved) row – just remove from DOM
            if (e.target.classList.contains('remove-holiday')) {
                e.target.closest('.holiday-row').remove();
            }
        });
    </script>
@endsection