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

        <!-- Piwik -->
        <script async type="text/javascript">
            var _paq = _paq || [];
            _paq.push(["setDomains", ["*.dployr.io","*.dployr.io","*.dployr.testbed.ml"]]);
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//analytics.savi.com.au/";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', '1']);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
    </head>

    <body>
        <noscript><p><img src="//analytics.savi.com.au/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->

    
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
                &copy; 2016 Mat Munn. <a href="{{ action('HomeController@about') }}">About</a>
            </div>
        </div>
    </footer>
    </body>
</html>
