@extends('layouts.app')

@section('content')
@if(Auth::user()->type == 1)
<div class="container text-xs-center">
    <div class="row">
        <div class="mt-5 col-lg-12">
            <h2>Dashboard</h2>
            <div class="row mt-3">
                <a href="{{ url('/admin/requests') }}" class="btn btn-squared-default btn-warning fa-2x mr-3">
                    <i class="mt-5 fa fa-book fa-5x"></i>
                    <br>
                    Ticket Status
                </a>
                <a href="{{ url('/admin/staff') }}" class="btn btn-squared-default btn-info fa-2x mr-3">
                    <i class="mt-5 fa fa-address-card fa-5x"></i>
                    <br>
                    Staff List
                </a>
                <a href="{{ url('/admin/announce') }}" class="btn btn-squared-default btn-primary purple fa-2x">
                    <i class="mt-5 fa fa-bell fa-5x"></i>
                    <br>
                    Announcements
                </a>
            </div>
        </div>
    </div>
</div>
@else
    <div class="container text-xs-center">
        <div class="row">
            <div class="mt-5 col-lg-12">
                <h2>Dashboard</h2>
                <div class="row mt-3">
                    <a href="{{ url('/staff/ticket-list') }}" class="btn btn-squared-default btn-warning fa-2x mr-3">
                        <i class="mt-5 fa fa-book fa-5x"></i>
                        <br>
                        Ticket Status
                    </a>
                    <a href="{{ url('/staff/announcements') }}" class="btn btn-squared-default btn-primary purple fa-2x">
                        <i class="mt-5 fa fa-bell fa-5x"></i>
                        <br>
                        Announcements
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection