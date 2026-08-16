@extends('admin.layout')
@section('title', 'Add Plan')
@section('page-title', 'Add Plan')

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.plans.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <form method="POST" action="{{ route('admin.plans.store') }}">
        @csrf
        @include('admin.plans._form', ['plan' => null])
        <button type="submit" class="btn btn-admin-primary">Create Plan</button>
    </form>
</div>
@endsection
