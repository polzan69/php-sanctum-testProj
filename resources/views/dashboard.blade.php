@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Dashboard</div>
    <div class="card-body">
        <h5>Welcome, {{ Auth::user()->name }}!</h5>
        <p>You are logged in!</p>
        
        @if(session('auth_token'))
            <div class="alert alert-info">
                <p><strong>Your Generated Token:</strong></p>
                <p class="text-break">{{ session('auth_token') }}</p>
                <p class="small">This token would normally be used for API authentication, and not to be displayed on any page.</p>
                <p class="small">but for this example, it is displayed on the dashboard to ensure that every login generates a new token.</p>
                <p class="small">To generate a new token, please log out and log back in.</p>
            </div>
        @endif
    </div>
</div>
@endsection