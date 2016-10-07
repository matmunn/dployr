@extends('home')

@section('fill')
    <div class="section no-pad-bot" id="index-banner">
        <div class="container">
            <br><br>
            <h1 class="header center dployr-blue font-serif">Pricing</h1>
            <br><br>

        </div>
    </div>

    <div class="container">

        <div class="section">
            <div class="row">
                <div class="col s12 m3 card">
                    <div class="icon-block">
                        <h5 class="center">Free</h5>

                        <ul>
                            <li>5 repositories</li>
                            <li>Unlimited deployments</li>
                        </ul>
                        <br><br>
                        Free!
                    </div>
                </div>
                <div class="col m1 hidden-on-sm"></div>
                <div class="col s12 m3 card">
                    <div class="icon-block">
                        <h5 class="center">Basic</h5>

                        <ul>
                            <li>20 repositories</li>
                            <li>Unlimited deployments</li>
                        </ul>
                        <br><br>
                        10 bucks
                    </div>
                </div>
                <div class="col m1 hidden-on-sm"></div>
                <div class="col s12 m3 card">
                    <div class="icon-block">
                        <h5 class="center">Professional</h5>

                        <ul>
                            <li>Unlimited repositories</li>
                            <li>Unlimited deployments</li>
                        </ul>
                        <br><br>
                        tree fiddys
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection