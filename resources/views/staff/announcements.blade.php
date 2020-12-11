@extends('layouts.app')

@section('content')
    <staff-announcements :user="{{Auth::user()}}"></staff-announcements>
@endsection
