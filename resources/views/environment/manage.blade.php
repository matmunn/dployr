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
                <a class="waves-effect waves-light btn btn-color-normal col s12 m5 l3" href="{{ action('RepositoryController@manage', $env->repository) }}">Back to Repository</a>
                <a class="waves-effect waves-light btn btn-color-normal new-server-button col s12 m5 offset-m2 l3 offset-l6" href="#">New Server</a>
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
            @if(count($env->servers) > 0)
                <div class="row">
                    <a class="waves-effect waves-light btn btn-color-success col s12 m5 offset-m2 l3 offset-l6" href="#">Deploy Now</a>
                </div>
            @endif
            <div class="row">
                <div class="col s12 m12">
                    <h4>Slack Notifiers</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <a class="waves-effect waves-light btn btn-color-normal col s12 m5 offset-m7 l3 offset-l9" href="#">New Slack Notifier</a>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($env->notifierSlack) == 0)
                                <tr>
                                    <td class="center-align" colspan="2">
                                        This environment has no slack notifiers
                                    </td>
                                </tr>
                            @else
                                @foreach($env->notifierSlack as $notify)
                                    <tr>
                                        {{-- <td><a href="{{ action('ServerController@manage', $server) }}">{{ $server->name }}</a></td> --}}
                                        {{-- <td>{{ strtoupper($server->type) }} - {{ $server->server_name }}</td> --}}
                                        <td>{{ $notify->endpoint }}</td>
                                        <td>Delete</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <a class="waves-effect waves-light btn btn-color-error delete-button col s12 m5 offset-m7 l3 offset-l9" href="#">Delete Environment</a>
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
                else
                {
                    swal({
                        title: 'Oops...',
                        text: "The environment name didn't match",
                        type: 'error',
                        confirmButtonClass: 'btn-color-success'});
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