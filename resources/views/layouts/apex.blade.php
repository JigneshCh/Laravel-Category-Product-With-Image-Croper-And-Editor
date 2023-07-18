<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}" class="loading" @if(\App::getLocale() == 'he') dir="rtl" @endif>
    <head>
        <title>@yield('title','Home') | {{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta content='text/html;charset=utf-8' http-equiv='content-type'>
        <meta content='Inspection and Engineering Services' name='description'>
        <link href='{{ asset('favicon.png') }}' rel='shortcut icon' type='image/x-icon'>
        @include('apex.include.cssfiles')
        @stack('css')
    </head>

    <body class="@yield('body_class','') @if(\App::getLocale() == 'he') body_he @else body_en @endif">
        
        <div class="wrapper">
            @include('apex.include.sidebar')
            @include('apex.include.topnav')
            <div class="main-panel">
                <div class="main-content">
                    <div class="content-wrapper">
                        @yield('content')
                        @include('apex.include.footer')
                    </div>
                </div>
            </div>
        </div>
    </body>
    @include('apex.include.jsfiles')
    @include('apex.include.page_notification')
    
@stack('js')


</body>
</html>