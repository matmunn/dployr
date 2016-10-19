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
            <div class="col s12">
                <h4>Reset Your Password</h4>
            </div>
        </div>
        <div class="row">
            <form class="col m6 offset-m3 s12" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
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
                    <div class="input-field">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        <label for="password">Confirm Password</label>
                        
                    </div>
                </div>
                <div class="row">
                    <button class="btn waves-effect waves-light btn-color-normal col s12 m5" type="submit" name="action">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
