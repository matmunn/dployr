<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Automatic site deployment made easy.">
        <meta name="author" content="Mat Munn">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>


        <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Bootstrap core CSS -->
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
        <script src="{{ elixir('js/app.js') }}"></script>
    </head>

    <body>
    
    @if(!Request::is('/'))
        <nav class="nav-header-part" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="/" class="brand-logo">
                    <img src="/img/logo.svg" alt="dployr" class="logo">
                </a>
            </div>
        </nav>
    @endif

    <nav class="navbar" role="navigation">
        <div class="nav-wrapper container">
            @if(Auth::user())
                <ul class="hide-on-med-and-down">
                    <li><a href="{{ action('RepositoryController@list') }}">Repositories</a></li>
                </ul>
            @endif
            <ul class="right hide-on-med-and-down">
                @if(Auth::user())
                    <li
                        @if(request()->is('myaccount'))
                            class="active"
                        @endif
                    ><a class="" href="{{ action('HomeController@dashboard') }}">My Account</a>
                    <li><a class="" href="/logout">Logout</a>
                @else
                    <li><a class="" href="/register">Register</a></li>
                    <li><a class="" href="/login">Login</a></li>
                @endif
            </ul>
        </div>
    </nav>

    @yield('content')

    <footer class="page-footer nav-header-part">
{{--         <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="black-text">Company Bio</h5>
                    <p class="black-text">We are a team of college students working on this project like it's our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>


                </div>
                <div class="col l3 s12">
                    <h5 class="black-text">Settings</h5>
                    <ul>
                        <li><a class="black-text" href="#!">Link 1</a></li>
                        <li><a class="black-text" href="#!">Link 2</a></li>
                        <li><a class="black-text" href="#!">Link 3</a></li>
                        <li><a class="black-text" href="#!">Link 4</a></li>
                    </ul>
                </div>
                <div class="col l3 s12">
                    <h5 class="black-text">Connect</h5>
                    <ul>
                        <li><a class="black-text" href="#!">Link 1</a></li>
                        <li><a class="black-text" href="#!">Link 2</a></li>
                        <li><a class="black-text" href="#!">Link 3</a></li>
                        <li><a class="black-text" href="#!">Link 4</a></li>
                    </ul>
                </div>
            </div>
        </div> --}}
        <div class="footer-copyright">
            <div class="container black-text">
                &copy; 2016 Mat Munn.
            </div>
        </div>
    </footer>
    </body>
</html>
