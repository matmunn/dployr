@extends('layouts.app')

@section('content')
<div class="section">
    @if(count($errors) > 0)
        <div class="row btn-color-error white-text">
            <div class="col s12 m10 offset-m1 l6 offset-l3">
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
        <div class="col s12 m10 offset-m1 l6 offset-l3">
            <h4>Register</h4>
        </div>
    </div>
    <div class="row">
        <form class="col s12 m10 offset-m1 l6 offset-l3" role="form" method="POST" action="{{ url('/register') }}">
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field">
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                    <label for="name">Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    <label for="email">Email</label>
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
                    <input id="password_confirm" type="password" class="form-control" name="password_confirmation" required>
                    <label for="password_confirm">Password Confirmation</label>
                    
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light btn-color-normal col s12 m5 l3" type="submit" name="action">Register</button>
            </div>
        </form>
    </div>
</div>
@endsection
