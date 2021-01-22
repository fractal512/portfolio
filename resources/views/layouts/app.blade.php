<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/fractal512-logo.jpg') }}" alt="fractal512 logo" width="36" height="36">
                        <strong style="text-transform: uppercase">fractal512</strong>
                    </a>
                    <span class="navbar-brand site-description">{{ __("HTML coding, web programming &amp; website development") }}</span>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{{ url('/about') }}">{{ __('About') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/portfolio') }}">{{ __('Portfolio') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}">{{ __('Contact') }}</a>
                        </li>
                        <li class="dropdown">
                            <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-1" role="img" viewBox="0 0 24 24" focusable="false"><title>{{ __('Search') }}</title><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M21 21l-5.2-5.2"></path></svg>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <div class="dropdown-item-text">
                                    <form class="form-inline" method="get" action="{{ url('/search') }}">
                                        <input name="s" class="form-control" type="text" placeholder="{{ __('Search for...') }}" aria-label="{{ __('Search') }}">
                                        <button class="btn" type="submit" style="width:100%">{{ __('Search') }}</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown">
                            <?php $langs = ['en' => 'ENG', 'ru'=>'RUS', 'uk'=>'UKR']; ?>
                            @foreach($langs as $locale => $lang)
                                @if($locale == session('locale'))
                                    <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ __($lang) }}
                                    </a>
                                @endif
                            @endforeach

                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @foreach($langs as $locale => $lang)
                                    @if($locale != session('locale'))
                                        <li><a href="{{ url('setlocale/'.__($locale)) }}">{{ __($lang) }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main">
            @yield('content')
        </main>

        <footer class="footer text-center">
            <div class="container">
                <span class="text-muted">© 2008 &#8211; {{ date('Y') }} <a class="site-link" href="{{ url('/') }}" rel="home">{{ config('app.name') }}</a></span>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
