@extends('layouts.admin')

@section('title', 'Data Import')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Import Forex Options Data</h4>
                <a href="{{ route('admin.data-import-auto') }}" class="btn btn-info">Auto Import</a>
            </div>
            @isset($dbInfo)
            <div class="card-body bg-light mb-3">
                <h5>Database Connection Information</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Connection</th>
                        <td>{{ $dbInfo['connection'] }}</td>
                    </tr>
                    <tr>
                        <th>Database</th>
                        <td>{{ $dbInfo['database'] }}</td>
                    </tr>
                    <tr>
                        <th>Host</th>
                        <td>{{ $dbInfo['host'] }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $dbInfo['connectionStatus'] ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>ForexOptions Table Exists</th>
                        <td>{{ $dbInfo['tableExists'] ?? 'Unknown' }}</td>
                    </tr>
                </table>
            </div>
            @endisset
            <div class="card-body">
                <form action="{{ route('admin.data-import') }}" method="POST">
                    @csrf

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('debug'))
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                Debug Information
                            </div>
                            <div class="card-body">
                                <h5>CSV Sample (First 5 rows):</h5>
                                <div style="overflow-x: auto;">
                                    <table class="table table-sm table-bordered">
                                        @foreach(session('debug')['csv_sample'] as $index => $row)
                                            <tr>
                                                @foreach($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <h5>Statistics:</h5>
                                <ul>
                                    <li>Total CSV Lines: {{ session('debug')['csv_line_count'] }}</li>
                                    <li>Processed Records: {{ session('debug')['total_processed'] ?? 0 }}</li>
                                    <li>Imported Records: {{ session('debug')['total_imported'] ?? 0 }}</li>
                                    @if(isset(session('debug')['batch_count']))
                                    <li>Batches Processed: {{ session('debug')['batch_count'] }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="url">CSV URL</label>
                        <input type="text"
                               class="form-control"
                               id="url"
                               name="url"
                               placeholder="Enter CSV URL (e.g., https://public.fyers.in/sym_details/NSE_FO.csv)"
                               value="{{ old('url') }}">
                    </div>

                    <div class="form-group">
                        <label for="exchange_name">Exchange Name</label>
                        <select class="form-control" id="exchange_name" name="exchange_name">
                            <option value="NSE_FO" {{ old('exchange_name') == 'NSE_FO' ? 'selected' : '' }}>NSE Futures & Options</option>
                            <option value="NSE_CD" {{ old('exchange_name') == 'NSE_CD' ? 'selected' : '' }}>NSE Currency Derivatives</option>
                            <option value="NSE_CM" {{ old('exchange_name') == 'NSE_CM' ? 'selected' : '' }}>NSE Cash Market</option>
                            <option value="BSE_CM" {{ old('exchange_name') == 'BSE_CM' ? 'selected' : '' }}>BSE Cash Market</option>
                            <option value="BSE_FO" {{ old('exchange_name') == 'BSE_FO' ? 'selected' : '' }}>BSE Futures & Options</option>
                            <option value="MCX_COM" {{ old('exchange_name') == 'MCX_COM' ? 'selected' : '' }}>MCX Commodities</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Import Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
