@extends('layouts.admin')

@section('title', 'Comex Margins')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Comex Margins</h4>
                    <p class="card-category">Configure Comex settings for {{ $user->name }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users-update', $user->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Comex Config</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="comex_enabled" value="1" {{ $user->comex_enabled ? 'checked' : '' }}>
                                        Comex Trading
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_brokerage_type">Comex brokerage type</label>
                                    <select class="form-control" name="comex_brokerage_type" id="comex_brokerage_type">
                                        <option value="Per Lot" {{ ($user->comex_brokerage_type ?? '') == 'Per Lot' ? 'selected' : '' }}>Per Lot</option>
                                        <option value="Per Crore" {{ ($user->comex_brokerage_type ?? '') == 'Per Crore' ? 'selected' : '' }}>Per Crore</option>
                                    </select>
                                    @error('comex_brokerage_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_brokerage">Comex brokerage</label>
                                    <input type="text" id="comex_brokerage" class="form-control" name="comex_brokerage" value="{{ old('comex_brokerage', $user->comex_brokerage ?? '1000.0000') }}">
                                    @error('comex_brokerage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_min_lot_per_trade">Minimum lots required per single trade of Comex</label>
                                    <input type="text" id="comex_min_lot_per_trade" class="form-control" name="comex_min_lot_per_trade" value="{{ old('comex_min_lot_per_trade', $user->comex_min_lot_per_trade ?? '0') }}">
                                    @error('comex_min_lot_per_trade')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_max_lot_per_trade">Maximum lots allowed per single trade of Comex</label>
                                    <input type="text" id="comex_max_lot_per_trade" class="form-control" name="comex_max_lot_per_trade" value="{{ old('comex_max_lot_per_trade', $user->comex_max_lot_per_trade ?? '5') }}">
                                    @error('comex_max_lot_per_trade')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_max_lot_per_script">Maximum lots allowed per script of Comex</label>
                                    <input type="text" id="comex_max_lot_per_script" class="form-control" name="comex_max_lot_per_script" value="{{ old('comex_max_lot_per_script', $user->comex_max_lot_per_script ?? '10') }}">
                                    @error('comex_max_lot_per_script')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_max_lot_total">Maximum lots allowed in total of Comex</label>
                                    <input type="text" id="comex_max_lot_total" class="form-control" name="comex_max_lot_total" value="{{ old('comex_max_lot_total', $user->comex_max_lot_total ?? '20') }}">
                                    @error('comex_max_lot_total')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4 class="bg-info p-2 text-white">Comex Margins</h4>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_gold_margin">Gold Margin</label>
                                    <input type="text" id="comex_gold_margin" class="form-control" name="comex_gold_margin" value="{{ old('comex_gold_margin', $user->comex_gold_margin ?? '5000') }}">
                                    @error('comex_gold_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_silver_margin">Silver Margin</label>
                                    <input type="text" id="comex_silver_margin" class="form-control" name="comex_silver_margin" value="{{ old('comex_silver_margin', $user->comex_silver_margin ?? '5000') }}">
                                    @error('comex_silver_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_copper_margin">Copper Margin</label>
                                    <input type="text" id="comex_copper_margin" class="form-control" name="comex_copper_margin" value="{{ old('comex_copper_margin', $user->comex_copper_margin ?? '5000') }}">
                                    @error('comex_copper_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_crude_margin">Crude Oil Margin</label>
                                    <input type="text" id="comex_crude_margin" class="form-control" name="comex_crude_margin" value="{{ old('comex_crude_margin', $user->comex_crude_margin ?? '5000') }}">
                                    @error('comex_crude_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_natural_gas_margin">Natural Gas Margin</label>
                                    <input type="text" id="comex_natural_gas_margin" class="form-control" name="comex_natural_gas_margin" value="{{ old('comex_natural_gas_margin', $user->comex_natural_gas_margin ?? '5000') }}">
                                    @error('comex_natural_gas_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_zinc_margin">Zinc Margin</label>
                                    <input type="text" id="comex_zinc_margin" class="form-control" name="comex_zinc_margin" value="{{ old('comex_zinc_margin', $user->comex_zinc_margin ?? '5000') }}">
                                    @error('comex_zinc_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_lead_margin">Lead Margin</label>
                                    <input type="text" id="comex_lead_margin" class="form-control" name="comex_lead_margin" value="{{ old('comex_lead_margin', $user->comex_lead_margin ?? '5000') }}">
                                    @error('comex_lead_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comex_nickel_margin">Nickel Margin</label>
                                    <input type="text" id="comex_nickel_margin" class="form-control" name="comex_nickel_margin" value="{{ old('comex_nickel_margin', $user->comex_nickel_margin ?? '5000') }}">
                                    @error('comex_nickel_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Update Settings</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
