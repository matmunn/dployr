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
                    {{-- <div class="fixed-action-btn horizontal">
                        <a class="btn">
                            New Server
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating red" href="{{ action('ServerController@new', [$env->id, 'ftp']) }}">FTP</a>
                            </li>
                        </ul>
                    </div> --}}
                    {{-- <a class="waves-effect waves-light btn btn-color-normal" href="{{ action('ServerController@new', [$env->id, 'ftp']) }}">New Server</a> --}}
                    <a class="waves-effect waves-light btn btn-color-normal new-server-button" href="#">New Server</a>
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
{{--}}            <div class="row">
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
                            @if(count($env->notifierSlack) == 0)
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
                                        {{-- <td>{{ $notify->endpoint }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>--}}
            <div class="row">
                <div class="col s12 m12 right-align">
                    <a class="waves-effect waves-light btn btn-color-error delete-button" href="#">Delete Environment</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.delete-button').on('click', function()
        {
            swal({
              title: "Delete environment",
              text: "Are you sure you want to delete this environment?<br /><br /><strong>This can't be undone!</strong><br /><br />Type the environment name <div class='monospace code'>{{ $env->name }}</div> below to confirm deletion.",
              input: "text",
              showCancelButton: true,
              closeOnConfirm: false,
              type: "error",
              confirmButtonClass: "btn-color-error"
            }).then(function(inputValue) {
                if(inputValue === '{{ $env->name }}')
                {
                    $.post('{{ action('EnvironmentController@delete', $env) }}', {"_method": "DELETE", "_token": "{{ csrf_token() }}"}, function(data)
                    {
                        // Redirect to repostiory list
                        window.location = '{{ action('RepositoryController@manage', $env->repository) }}';
                    });
                }
            }).done();
        });

        $('.new-server-button').on('click', function()
        {
            swal({
                title: "New server",
                html: "Choose your server type:<br /><br /><a class='btn btn-color-normal' href='{{ action('ServerController@new', [$env->id, 'ftp']) }}'>FTP</a> <a class='btn btn-color-normal' href='{{ action('ServerController@new', [$env->id, 'sftp']) }}'>SFTP</a>",
                showCancelButton: true ,
                showConfirmButton: false
            });
        });
    </script>
@endsection