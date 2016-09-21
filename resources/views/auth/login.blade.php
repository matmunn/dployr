@extends('layouts.app')

@section('content')
<div class="section">
    <div class="row">
        <form class="col s12" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                    <label for="email">Email Address</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field">
                    <input id="password" type="password" class="form-control" name="password" required>
                    <label for="password">Password</label>
                    
                </div>
            </div>
            <div class="row">
                <input type="checkbox" name="remember" id="remember" />
                <label for="remember">Remember Me</label>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light" type="submit" name="action">Login</button>
                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                    Forgot Your Password?
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
