@extends('layouts.app')

@section('content')
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
        <form class="col m6 offset-m3 s12" role="form" method="POST" action="{{ url('/login') }}">
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
                <button class="btn waves-effect waves-light btn-color-normal col s12 m5" type="submit" name="action">Login</button>
{{--             </div>
            <div class="row"> --}}
                <a class="btn btn-link btn-color-normal col s12 m5 offset-m2" href="{{ url('/password/reset') }}">
                    Forgot Your Password?
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
