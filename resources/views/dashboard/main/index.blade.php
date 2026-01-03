@extends('dashboard.layout.master')
@section('title', 'Simonjar Payakumbuh')
@section('menuDashboard', 'active')

@section('content')
    @if(Auth::user()->level_id == '1')
        @include('admin.index')
    @endif
@endsection
