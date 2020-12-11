@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <staff-creation :user="{{Auth::user()}}"></staff-creation>
    </div>
</div>
@endsection
