@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Dashboard</div>
    <div class="card-body">
        <h5>Welcome, {{ Auth::user()->name }}!</h5>
        <p>You are logged in!</p>
        
        @if(session('auth_token'))
            <div class="alert alert-info">
                <p><strong>Your API Token:</strong></p>
                <p class="text-break">{{ session('auth_token') }}</p>
                <p class="small">This token would normally be used for API authentication, not displayed on the page.</p>
            </div>
        @endif
    </div>
</div>
@endsection