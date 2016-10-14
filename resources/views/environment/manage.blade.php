@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <div class="row">
                <div class="col s12">
                    <h4>{{ $env->repository->name }} - {{ $env->name }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s6">
                    <a class="waves-effect waves-light btn btn-color-normal" href="{{ action('RepositoryController@manage', $env->repository) }}">Back to Repository</a>
                </div>
                <div class="col s6 right-align">
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
                                        <td><a href="{{ action('ServerController@manage', $server) }}">{{ $server->name }}</a></td>
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
            <div class="row">
                <div class="col s12 m12">
                    <h4>Slack Notifiers</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <thead>
                            <tr>
                                <th>
                                    Endpoint
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($env->notifierSlack) == 0) {{-- This line will need to count email notifiers too --}}
                                <tr>
                                    <td class="center-align">
                                        This environment has no slack notifiers
                                    </td>
                                </tr>
                            @else
                                @foreach($env->notifierSlack as $notify)
                                    <tr>
                                        {{-- <td><a href="{{ action('ServerController@manage', $server) }}">{{ $server->name }}</a></td> --}}
                                        {{-- <td>{{ strtoupper($server->type) }} - {{ $server->server_name }}</td> --}}
                                        <td>{{ $notify->endpoint }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection