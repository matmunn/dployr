@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12 m12">
                    <h3>{{ $repo->name }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col m12 s12">
                    Please see below for your deploy key:<br />
                    <pre>{{ $repo->public_key }}</pre>
                </div>
            </div>
        </div>
    </div>
@endsection