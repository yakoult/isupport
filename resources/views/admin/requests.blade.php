@extends('layouts.app')

@section('content')
    <customer-requests :user="{{Auth::user()}}"></customer-requests>
@endsection
