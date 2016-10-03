@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <h4>{{ $server->name }} ({{ strtoupper($server->type) }}) - {{ $server->environment->name }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <a class="waves-effect waves-light btn btn-color-normal" href="{{ action('EnvironmentController@manage', $server->environment) }}">Back to Environment</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <tbody>
                            <tr>
                                <td class="font-bold">Server URL</td>
                                <td>{{ $server->server_name }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Server Username</td>
                                <td>{{ $server->server_username }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Server Password</td>
                                <td>
                                    @if(!empty($server->server_password))
                                        *****
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold">Server Path</td>
                                <td>{{ $server->server_path }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection