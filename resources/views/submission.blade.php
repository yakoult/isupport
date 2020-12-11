@extends('layouts.app')

@section('content')
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">{{ $service }}</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('custrequest') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Full Name') }}</label>

                                        <div class="col-md-3 pr-0">
                                            <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" placeholder="First Name" autofocus>

                                            @error('firstname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-2 px-0">
                                            <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" value="{{ old('middlename') }}" required autocomplete="middlename" placeholder="Middle Name" autofocus>

                                            @error('middlename')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 pl-0">
                                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" placeholder="Last Name" autofocus>

                                            @error('lastname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-2 col-form-label text-md-right">{{ __('Address') }}</label>

                                        <div class="col-md-8">
                                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address" placeholder="Address" autofocus>

                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="mobile" class="col-md-2 col-form-label text-md-right">{{ __('Mobile Number') }}</label>

                                        <div class="col-md-3">
                                            <input id="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile Number" required autocomplete="mobile">

                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-3">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email@address" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="company" class="col-md-2 col-form-label text-md-right">{{ __("Company") }}</label>

                                        <div class="col-md-8">
                                            <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" required autocomplete="company" placeholder="Company" autofocus>

                                            @error('company')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="pmobile" class="col-md-2 col-form-label text-md-right">{{ __("Company Number") }}</label>

                                        <div class="col-md-3">
                                            <input id="pmobile" type="tel" class="form-control @error('pmobile') is-invalid @enderror" name="pmobile" value="{{ old('pmobile') }}" required autocomplete="pmobile">

                                            @error('pmobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <label for="pmail" class="col-md-2 col-form-label text-md-right">{{ __("Company E-Mail") }}</label>

                                        <div class="col-md-3">
                                            <input id="pmail" type="pmail" class="form-control @error('pmail') is-invalid @enderror" name="pmail" value="{{ old('pmail') }}" required autocomplete="pmail">

                                            @error('pmail')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if($service != "Internet Subscription" && $service != "Telecom Business Consultation")
                                        <div class="form-group row">
                                            <label for="POI" class="col-md-2 col-form-label text-md-right">{{ __("POI") }}</label>

                                            <div class="col-md-8">
                                                <input id="POI" type="text" class="form-control @error('POI') is-invalid @enderror" name="POI" required autocomplete="POI" placeholder="POI" autofocus>

                                                @error('POI')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="headend" class="col-md-2 col-form-label text-md-right">{{ __("Head End") }}</label>

                                            <div class="col-md-8">
                                                <input id="headend" type="text" class="form-control @error('headend') is-invalid @enderror" name="headend" required autocomplete="headend" placeholder="Head End" autofocus>

                                                @error('headend')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @else
                                        <input id="POI" type="hidden" name="POI" required value="N/A">
                                        <input id="headend" type="hidden" name="headend" required value="N/A">
                                    @endif
                                    <div class="form-group row">
                                        <label for="service" class="col-md-2 col-form-label text-md-right">{{ __('Service') }}</label>

                                        <div class="col-md-8">
                                            <select id="service" class="form-control @error('service') is-invalid @enderror" name="service">
                                                <option selected disabled="true">Please select a Service</option>
                                                @if($service == 'Maintenance')
                                                    <option>Maintenance of ISP</option>
                                                    <option>Maintenance of OSP</option>
                                                    <option>Maintenance of Network</option>
                                                @elseif(strpos($service, 'Subscription'))
                                                    <option>Corporate Subscription</option>
                                                    <option>Residential Subscription</option>
                                                @elseif(strpos($service, 'Optic'))
                                                    <option>OSP Fiber Optic Cable Installation</option>
                                                    <option>ISP Fiber Optic Cable Installation</option>
                                                @elseif(strpos($service, 'Business'))
                                                    <option>Telecom Business Consultation</option>
                                                    <option>Government Compliance Requirements</option>
                                                @else()
                                                    <option>{{ $service }}</option>
                                                @endif
                                            </select>

                                            @error('service')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="payment" class="col-md-2 col-form-label text-md-right">{{ __('Terms of Payment') }}</label>

                                        <div class="col-md-8">
                                            <select id="payment" class="form-control @error('payment') is-invalid @enderror" name="payment">
                                                <option selected disabled="true">Please select a payment method</option>
                                                <option>Full Payment</option>
                                                @if(strpos($service, 'Installation'))
                                                <option>40% Downpayment</option>
                                                <option>60% Downpayment</option>
                                                @endif
                                            </select>

                                            @error('payment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-5">
                                            <button type="submit" class="custom-green btn btn-success">
                                                {{ __('Submit Request') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection

