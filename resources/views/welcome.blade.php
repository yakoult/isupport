<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>iSupport Wide Network</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;`
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <div v-if="!isMobile" class="wrapper d-sm-none">
                <!-- Sidebar  -->
                <nav id="sidebar" :class="toggle" class="custom-yellow" style="overflow: visible;">
                    <div class="sidebar-header">
                        <h2 class="text-black">Services</h3>
                    </div>

                    <ul class="list-unstyled components">
                        <li class="active">
                            <div class="card custom-orange">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Maintenance'><h4>Maintenance</h4></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-red">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Internet Subscription'><h4>Internet Subscription</h4></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-green">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Fiber Optic Installation'><h4>Fiber Optic Cable Installation</h4></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-blue">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Wireless Installation'><h4>Wireless Installation</h4></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-dblue">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Telecom Business Consultation'><h4>Telecom Business Consultation</h4></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid" id="content">
                    <img class="d-block img-fluid" src="{{ asset('images/iSupport Wide Landing page.jpg') }}" alt="Bicol1 Cover">
                    <div class="bottom-right d-flex justify-content-between services mb-5 mr-3">

                        <button class="custom-green btn btn-success btn-width btn-text" v-b-modal.loginmodal>{{ __('Login') }}</button>

                        <button class="custom-green btn btn-success btn-width btn-text" v-on:click="toggleSidebar()">Services</button>

                        <button class="custom-green btn btn-success btn-width btn-text" onclick='window.location.href="{{ url('products') }}"'>Products</button>
                        
                    </div>
                    <b-modal id="loginmodal" size="xl" hide-foooter>
                        <request-lookup></request-lookup>
                    </b-modal>
                    <b-modal id="announcement" size="xl" hide-footer hide-header>
                        <announcements></announcements>
                    </b-modal>
                </div>
                <tutorial-popup></tutorial-popup>
            </div>
            <div v-if="isMobile" class="container-fluid d-md-none d-lg-none d-xl-none">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <button class="custom-green btn btn-success w-100 btn-text" v-b-modal.loginmodal>{{ __('Login') }}</button>
                            </li>
                            <li class="nav-item">
                                <button class="custom-green btn btn-success w-100 btn-text" v-on:click="toggleSidebar()">Services</button>
                            </li>
                            <li class="nav-item">
                                <button class="custom-green btn btn-success w-100 btn-text" onclick='window.location.href="{{ url('products') }}"'>Products</button>
                            </li>
                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Services
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Maintenance'><h4>Maintenance</h4></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Internet Subscription'><h4>Internet Subscription</h4></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Fiber Optic Installation'><h4>Fiber Optic Cable Installation</h4></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Wireless Installation'><h4>Wireless Installation</h4></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Telecom Business Consultation'><h4>Telecom Business Consultation</h4></a>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </nav>
                <nav id="sidebar" :class="toggle" class="custom-yellow" style="overflow: visible;">
                    <div class="sidebar-header">
                        <h2 class="text-black">Services</h3>
                    </div>

                    <ul class="list-unstyled components">
                        <li class="active">
                            <div class="card custom-orange">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Maintenance'><h5>Maintenance</h5></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-red">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Internet Subscription'><h5>Internet Subscription</h5></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-green">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Fiber Optic Installation'><h5>Fiber Optic Cable Installation</h5></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-blue">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Wireless Installation'><h5>Wireless Installation</h5></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card custom-dblue">
                                <div class="card-body">
                                    <a class="nav-link text-black" href='{{ route("submission") }}?service=Telecom Business Consultation'><h5>Telecom Business Consultation</h5></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- Page Content -->
                <div class="container-fluid" id="content">
                    <div class="row">
                        <img src="{{ asset('/images/isupportMobile.jpg') }}" class="img-fluid">
                    </div>
                    <b-modal id="loginmodal" size="xl" hide-foooter>
                        <request-lookup></request-lookup>
                    </b-modal>
                    <b-modal id="announcement" size="xl" hide-footer hide-header>
                        <announcements></announcements>
                    </b-modal>
                </div>
                <tutorial-popup></tutorial-popup>
            </div>
        </div>
    </body>
</html>