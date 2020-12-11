@extends('layouts.app')

@section('content')
    <staff-ticketlist :user="{{Auth::user()}}"></staff-ticketlist>
@endsection
