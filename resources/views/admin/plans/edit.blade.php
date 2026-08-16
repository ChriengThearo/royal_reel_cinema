@extends('admin.layout')
@section('title', 'Edit Plan')
@section('page-title', 'Edit Plan')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.plans.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @csrf @method('PUT')
        @include('admin.plans._form', ['plan' => $plan])
        <button type="submit" class="btn btn-admin-primary">Save Changes</button>
    </form>
</div>
@endsection
