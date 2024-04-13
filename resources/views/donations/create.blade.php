@extends('layouts.app')

@section('content')
<div class="container" style="margin-top:120px">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 rounded shadow-sm">
                <div class="card-body">
                    <form action="{{ route('donations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="fullName" value="{{ old('name') }}" name="name" placeholder="Enter your full name">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" name="email" placeholder="Enter your email address">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Donation Amount (Rp.)</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" value="{{ old('amount') }}" name="amount" placeholder="Enter donation amount">
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Note</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="notes" rows="3" name="note" placeholder="Enter note message">{{ old('note') }}</textarea>
                            @error('note')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Donate Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection