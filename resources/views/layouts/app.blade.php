@include('layouts.header')

@if(Auth::check())

    @include('layouts.sidebar')

    <div class="main-panel">
        <nav style="background-color: #ffffff" class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('brand')</a>
                </div>
                {{--<div class="collapse navbar-collapse">--}}
                    {{--<ul class="nav navbar-nav navbar-right">--}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                                {{--<i class="material-icons">notifications</i>--}}
                                {{--<span class="notification">5</span>--}}
                                {{--<p class="hidden-lg hidden-md">Notifications</p>--}}
                            {{--</a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li><a href="#">Mike John responded to your email</a></li>--}}
                                {{--<li><a href="#">You have 5 new tasks</a></li>--}}
                                {{--<li><a href="#">You're now friend with Andrew</a></li>--}}
                                {{--<li><a href="#">Another Notification</a></li>--}}
                                {{--<li><a href="#">Another One</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">--}}
                                {{--<i class="material-icons">person</i>--}}
                                {{--<p class="hidden-lg hidden-md">Profile</p>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            </div>
        </nav>
@endif

<div class="content" style="width: 100%">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>

@include('layouts.footer')
