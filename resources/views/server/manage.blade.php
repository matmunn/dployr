@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <div class="row">
                <div class="col s12">
                    <h4>{{ $server->name }} ({{ strtoupper($server->type) }}) - {{ $env->name }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    {{-- <a class="waves-effect waves-light btn" href="{{ action('EnvironmentController@new', $env->id) }}">New Server</a> --}}
                    <div class="fixed-action-btn horizontal">
                        <a class="btn">
                            New Server
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating red" href="{{ action('ServerController@new', [$env->id, 'ftp']) }}">FTP</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <thead>
                            <tr>
                                <th>
                                    Server Name
                                </th>
                                <th>
                                    Server Type - URL
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($env->servers) > 0)
                                @foreach($env->servers as $server)
                                    <tr>
                                        <td><a href="#">{{ $server->name }}</a></td>
                                        <td>{{ strtoupper($server->type) }} - {{ $server->server_name }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="center-align">
                                        This environment has no servers
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection