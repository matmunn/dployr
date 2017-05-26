@extends('layouts.app')

@section('fill')
    <div class="section no-pad-bot laptop-image" id="index-banner">
        <div class="container text-center">
            <br><br>
            <div class="center-align">
                <img src="img/logo.svg" class="large-logo">
            </div>
            <div class="row center">
                <h5 class="header col s12 font-serif dployr-grey">Spend less time deploying, more time developing</h5>
            </div>
            <div class="row center">
                <a href="{{ action('HomeController@pricing') }}" id="download-button" class="btn-large waves-effect waves-light btn-color-normal">See our pricing</a>
            </div>
            <br><br>

        </div>
    </div>

    <div class="container">

        <div class="section">

            <!--   Icon Section   -->
            <div class="row">
                <div class="col s12 m4 offset-m2">
                    <div class="icon-block">
                        <h2 class="center dployr-blue">
                            <i class="material-icons medium">flash_on</i>
                        </h2>
                        <h5 class="center">
                            Speeds up deployment<br>
                            &nbsp;
                        </h5>

                        <p class="light">No more worrying about FTP account details and trying to keep track of which files you changed, dployr will take care of all the legwork in the background to ensure that as soon as you push to your repository your changes are reflected on your site.
                        </p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center dployr-blue">
                            <i class="material-icons medium">public</i>
                        </h2>
                        <h5 class="center">
                            Multi-regional deployments<br />
                            <em>coming soon</em>
                        </h5>

                        <p class="light">
                            Dployr is an Australian based product, but what if your servers are in another country? Choose the deployment region closest to your servers and deploy your files at the best possible speed, no matter where your code is hosted, no matter where your servers are located.
                        </p>
                    </div>
                </div>

{{--                 <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center light-blue-text"><i class="material-icons">group</i></h2>
                        <h5 class="center">User Experience Focused</h5>

                        <p class="light">By utilizing elements and principles of Material Design, we were able to create a framework that incorporates components and animations that provide more feedback to users. Additionally, a single underlying responsive system across all platforms allow for a more unified user experience.</p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center light-blue-text"><i class="material-icons">settings</i></h2>
                        <h5 class="center">Easy to work with</h5>

                        <p class="light">We have provided detailed documentation as well as specific code examples to help new users get started. We are also always open to feedback and can answer any questions a user may have about Materialize.</p>
                    </div>
                </div> --}}
            </div>

        </div>
        <br><br>

        <div class="section">

        </div>
    </div>
@endsection

@section('content')
    @yield('fill')
@endsection
