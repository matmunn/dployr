<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Spend less time deploying, more time developing.">
        <meta name="author" content="Mat Munn">
        <meta name="keywords" content="ftp deployment automatic sftp version control website site upload">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#303030">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#303030">


        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>

        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.3.1/sweetalert2.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.3.1/sweetalert2.min.js"></script>

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

        <!-- Hotjar Tracking Code for https://dployr.io -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:317800,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
        </script>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-99924925-1', 'auto');
          ga('send', 'pageview');

        </script>
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
                <ul>
                    <li><a href="{{ action('RepositoryController@list') }}">Repositories</a></li>
                </ul>
            @endif
            <ul class="right">
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
    @if(session()->has('message') || session()->has('status'))
        <div class="card-panel btn-color-success white-text center-align">
            {{ !empty(session('message')) ? session()->pull('message') : session()->pull('status') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="card-panel btn-color-error center-align white-text text-lighten-2">
            {{ session()->pull('error') }}
        </div>
    @endif

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
                &copy; {{ date('Y') }}. {{-- <a href="{{ action('HomeController@about') }}">About</a> --}} <a href="{{ action('HomeController@privacy') }}">Privacy Policy</a>
            </div>
        </div>
    </footer>
    @yield('scripts')
    </body>
</html>
