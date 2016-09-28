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
                <div class="col s12 right-align">
                    <div class="fixed-action-btn horizontal">
                        <a class="btn">
                            New Server
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating red" href="{{ action('ServerController@new', [$server->environment->id, 'ftp']) }}">FTP</a>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection