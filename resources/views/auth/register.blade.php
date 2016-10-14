@extends('layouts.app')

@section('content')
<div class="section">
    <div class="row">
        <form class="col s6 offset-s3" role="form" method="POST" action="{{ url('/register') }}">
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
                    <label for="password_confirm">Password</label>
                    
                </div>
            </div>
            <div class="row">
                <button class="btn waves-effect waves-light btn-color-normal" type="submit" name="action">Register</button>
            </div>
        </form>
    </div>
</div>
@endsection
