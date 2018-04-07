@extends('layouts.dashboard-app')

@section('dashboard.sidebar')
	@include('layouts.dashboard-sidebar')
@endsection

@section('content')
<div class="card mb-4">
    <h1 class="h2 mb-0 font-weight-bold card-body text-center">Testimonial</h1>
</div>

<testimonial-component></testimonial-component>

<mediamodal-component></mediamodal-component>
@endsection