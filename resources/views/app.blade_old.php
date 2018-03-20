<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
    @if(!is_null($modules_submenu_name))
        <title>{{$modules_submenu_name->{'name_'.$lang} or trans('variables.title_page')}}</title>
    @elseif(!is_null($modules_name))
        <title>{{$modules_name->{'name_'.$lang} or trans('variables.title_page')}}</title>
    @else
        <title>{{trans('variables.title_page')}}</title>
    @endif
    <link rel="stylesheet" href="{{asset('css/normalize.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('css/chosen.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('css/fancybox/jquery.fancybox.css')}}">
    <link rel="stylesheet" href="{{asset('css/fancybox/jquery.fancybox-buttons.css')}}">
    <link rel="stylesheet" href="{{asset('css/fancybox/jquery.fancybox-thumbs.css')}}">
    <script src="{{asset('js/jquery-2.1.4.js')}}"></script>
    <script src="{{asset('js/jsvalidation.js')}}"></script>
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/toastr.js')}}"></script>
    <script src="{{asset('js/jquery.tablednd_0_5.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('js/chosen.jquery.js')}}"></script>
    <script src="{{asset('js/dropzone.js')}}"></script>
    <script src="{{asset('js/dropzone-config.js')}}"></script>
    <script src="{{asset('js/fancybox/jquery.fancybox.js')}}"></script>
    <script src="{{asset('js/fancybox/jquery.fancybox-buttons.js')}}"></script>
    <script src="{{asset('js/fancybox/jquery.fancybox-thumbs.js')}}"></script>
    <script src="{{asset('js/fancybox/jquery.mousewheel.js')}}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>

</head>
<body>
    <nav role="navigation">
        <div class="nav-wrapper">
            @yield('nav-bar')
        </div>
    </nav>

    @yield('left-menu')

    <section class="main-wrapper">
        @yield('content')
    </section>

    @yield('footer')

</body>
</html>
