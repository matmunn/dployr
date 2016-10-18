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
                <a class="waves-effect waves-light btn btn-color-normal col s12 m5 l3" href="{{ action('EnvironmentController@manage', $server->environment) }}">Back to Environment</a>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped responsive-table">
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
                                    @else
                                        (empty)
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

            <div class="row">
                <div class="col s12">
                    <h4>Deployments</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped responsive-table">
                        <thead>
                            <th>Commit Message</th>
                            <th>Started at</th>
                            <th>Finished at</th>
                            <th>Files deployed</th>
                        </thead>
                        <tbody>
                            @foreach($server->deployments as $deployment)
                                <tr>
                                    <td class="truncated">{!! str_replace("\n", "<br />", htmlentities($deployment->commit_message)) !!}</td>
                                    <td valign="top">{{ $deployment->started_at }}</td>
                                    <td valign="top">{{ $deployment->finished_at }}</td>
                                    <td valign="top">{{ $deployment->file_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection