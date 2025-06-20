@extends('layouts.user')

@section('head')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@endsection

@section('content')
<div id="appCapsule">
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <div class="tab-content">
                <div class="tab-pane show active">
                    <h3 class="text-center">Deposit Request Form</h3>

                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('deposit.submit') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Screenshot --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Payment Screenshot</label>
                                <input type="file" name="screenshot" class="form-control" onchange="checkImageSize(this);" required>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Amount</label>
                                <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{ old('amount') }}" required>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group basic">
                            <div class="input-wrapper text-center">
                                <button type="submit" class="btn btn-success w-100" style="border-radius: 0">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function checkImageSize(input) {
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        const valid = ['jpeg', 'jpg', 'png'];
        if (!valid.includes(ext)) {
            alert('Only jpg, jpeg, png files are allowed.');
            input.value = '';
            return;
        }
        if (file.size > 1048576) { // 1MB
            alert('Max allowed file size is 1 MB');
            input.value = '';
        }
    }
</script>
@endpush
