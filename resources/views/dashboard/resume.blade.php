@extends('layouts.dashboard-app')

@section('dashboard.sidebar')
	@include('layouts.dashboard-sidebar')
@endsection

@section('content')
<div class="card mb-4">
    <h1 class="h2 mb-0 font-weight-bold card-body text-center">Resume</h1>
</div>
<div class="card">
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <resume-component></resume-component>
    </div>
</div>
@endsection