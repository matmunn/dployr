@extends('home')

@section('fill')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h4>Create New FTP Server</h4>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12">
                <form action="{{ action('ServerController@save') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="ftp">
                    <input type="hidden" name="environment" value="{{ $environment }}">
                    <div class="input-field">
                        <input type="text" name="name" value="{{ old('name') }}">
                        <label>Server Name</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="url" value="{{ old('url') }}">
                        <label>Server URL</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="user" value="{{ old('user') }}">
                        <label>FTP Username</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password">
                        <label>FTP Password</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="path">
                        <label>FTP Path</label>
                    </div>
                    <input type="submit" class="btn green" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection