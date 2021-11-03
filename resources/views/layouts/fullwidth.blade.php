<!DOCTYPE html>
<html>
<head>
<title>@yield( 'page_title' )</title>
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/fonts/fonts.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/animate.css')}}">
@yield( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/style.css')}}">
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-migrate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/additional-methods.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/js.cookie.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jqClock.min.js')}}"></script>
@yield( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/script.js')}}"></script>
</head>
<body class="dashboard">
    <div id="header">
        <div class="container">
            <div id="header-title"><a href="#" title="Back to dashboard"><img src="{{URL::asset('assets/images/logo.png')}}" /></a></div>
            <div id="header-right">
                <div class="login-header-info-wrapper">
                    <span id="clock"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="content-wrapper">
        <div class="container">
            <div id="content" class="full-width">
                <div class="content-container">
                    @yield( 'content' )
                </div>
            </div>
        </div>
    </div>

    <div id="login-footer">
        <div class="container">
            <div class="login-footer-copyright">
                Copyright &copy; 2021 PT Indo Graha Dharmala.
            </div>
            <div class="login-colorpicker-wrapper">
                <p>Set Your Best Color</p>

                <div class="login-colorpicker">
                    <ul class="clearfix">
                        <li><a href="#" data-color="#43a6d4" class="color-43a6d4"></a></li>
                        <li><a href="#" data-color="#a3e0fd" class="color-a3e0fd"></a></li>
                        <li><a href="#" data-color="#d4fda3" class="color-d4fda3"></a></li>
                        <li><a href="#" data-color="#f1a3fd" class="color-f1a3fd"></a></li>
                        <li><a href="#" data-color="#89a898" class="color-89a898"></a></li>
                        <li><a href="#" data-color="#8aa669" class="color-8aa669"></a></li>
                        <li><a href="#" data-color="#a96eb2" class="color-a96eb2"></a></li>
                        <li><a href="#" data-color="#5b6d64" class="color-5b6d64"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>