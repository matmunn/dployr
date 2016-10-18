@extends('home')

@section('fill')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h4>Create New SFTP Server</h4>
            </div>
        </div>
        <form action="{{ action('ServerController@save') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="type" value="sftp">
            <input type="hidden" name="environment" value="{{ $environment }}">
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="text" name="name" value="{{ old('name') }}">
                        <label>Server Name</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="text" name="url" value="{{ old('url') }}">
                        <label>Server URL</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="text" name="user" value="{{ old('user') }}">
                        <label>FTP Username</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="password" name="password">
                        <label>FTP Password</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="text" name="path">
                        <label>FTP Path</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <input type="submit" class="btn btn-color-success col s12 m5 l3" value="Save">
                <a class="btn btn-color-error col s12 m5 offset-m2 l3 offset-l6" href="{{ action('EnvironmentController@manage', $environment) }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection