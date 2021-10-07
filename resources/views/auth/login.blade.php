<!DOCTYPE html>
<html>
<head>
<title>Welcome to Smart Clinic System</title>
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/fonts/fonts.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/animate.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/style.css')}}">
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-migrate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/additional-methods.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/js.cookie.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jqClock.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/script.js')}}"></script>
</head>
<body class="login">
    <div id="login-top-header">
        <div class="container">
            <div class="login-header-bg"></div>
        </div>
    </div>
    <div id="login-header" class="clearfix">
        <div class="container">
            <div class="login-header-title pull-left">
                <p class="login-title">Smart Clinic System</p>
            </div>

            <div class="login-header-info pull-right">
                <div class="login-header-info-wrapper">
                    <span id="clock"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="login">
        <div class="container">
            <div class="login-content pull-left">
                <h1>Welcome <span>to Enter System</span></h1>

                <p>This is Software to Manage Your 
                Organization Record and Medical Record
                Starting from Entry Data to Report Data
                Patient, Management, Employee, etc
                For the first User you can register with klik 
                sign up by the administrator and for guest
                your can view visit report with klik view datamailm
                thank for use this information system</p>

                <div class="login-action">
                    <!--<a href="{{ url( '/register' ) }}" class="btn btn-signup" data-toggle="modal" data-target="#register-modal">SIGN UP</a>-->
                    <a href="{{ url( '/register' ) }}" class="btn btn-signup">SIGN UP</a>
                    <!--<a href="#" class="btn btn-view-data">VIEW DATA</a>-->
                </div>
            </div>
            <div class="login-form pull-right">
                <form id="login-form" method="post" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="login-details">
                        <h3>User <span>Login</span></h3>
                        <div class="login-name">Guest</div>

                        <div class="login-picture"><img src="{{URL::asset('assets/images/guest.png')}}" /></div>
                    </div>
                    <div class="login-input">
                        <div class="form-group{{ $errors->has( 'username' ) ? ' has-error' : '' }}">
                            <input type="text" name="username" id="username" placeholder="username" class="form-control required" value="{{ old( 'username' ) }}" />
                        
                            @if ( $errors->has( 'username' ) )
                                <span class="help-block">
                                    <strong>{{ $errors->first( 'username' ) }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has( 'password' ) ? ' has-error' : '' }}">
                            <input type="password" name="password" id="password" placeholder="password" class="form-control required" />
                            @if ($errors->has( 'password' ))
                                <span class="help-block">
                                    <strong>{{ $errors->first( 'password' ) }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="remember" id="rememberme" />
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="form-group form-submit">
                            <input type="submit" class="btn btn-login-submit" id="login-submit" value="SIGN IN" />
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="login-footer">
        <div class="container">
            <div class="login-footer-copyright">
                Copyright &copy; 2016 PT Prima Yasa Medika.
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

    <div class="modal fade" tabindex="-1" role="dialog" id="register-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="admin-login-form" method="post" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <input type="password" name="password" id="admin_password" />
                        <input type="hidden" name="username" id="admin_username" value="admin" />
                        <input type="hidden" name="redirect_to" id="redirect_to" value="/user/user-registration" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        $('#register-modal').on('shown.bs.modal', function(e){
            $('#admin_password').focus();
        });
    });
    </script>
</body>
</html>