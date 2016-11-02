@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            @if(count($errors) > 0)
                <div class="row btn-color-error white-text">
                    <div class="col s12 m6 offset-m3">
                        <div class="">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col s12">
                    <h4>Invite New User</h4>
                </div>
            </div>
            <form action="{{ action('GroupController@sendInvite') }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col s12 m12">
                        <div class="input-field">
                            <input id="email" type="email" class="validate" name="email" required>
                            <label for="email">Email Address</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12">
                        <input type="submit" class="btn btn-color-success col s12 m5 l3" value="Send">
                        <a class="btn btn-color-error col s12 m5 offset-m2 l3 offset-l6" href="{{ action('HomeController@dashboard') }}">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection