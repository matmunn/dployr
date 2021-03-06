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
                    <a class="waves-effect waves-light btn btn-color-success col s12 m5 offset-m2 l3 offset-l9" href="{{ action('EnvironmentController@deploy', $env) }}">Deploy Now</a>
                </div>
            @endif
            <div class="row">
                <div class="col s12 m12">
                    <h4>Notifiers</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <a class="waves-effect waves-light btn btn-color-normal new-notifier-button col s12 m5 offset-m7 l3 offset-l9" href="#">New Notifier</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <thead>
                            <tr>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Contact
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($env->notifiers) == 0)
                                <tr>
                                    <td class="center-align" colspan="2">
                                        This environment has no notifiers
                                    </td>
                                </tr>
                            @else
                                @foreach($env->notifiers as $notify)
                                    <tr>
                                        {{-- <td><a href="{{ action('ServerController@manage', $server) }}">{{ $server->name }}</a></td> --}}
                                        {{-- <td>{{ strtoupper($server->type) }} - {{ $server->server_name }}</td> --}}
                                        <td>{{ $notify->type == "sms" ? strtoupper($notify->type) : ucfirst($notify->type) }}</td>
                                        <td>{{ $notify->data1 }}</td>
                                        <td><a href="#" data-notifier="{{ $notify->id }}" class="delete-notifier-link">Delete</a></td>
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

        $('.new-notifier-button').on('click', function()
        {
            swal({
                title: "New notifier",
                html: "Choose your notification type:<br /><br /><a class='btn btn-color-normal' href='{{ action('NotifierController@new', [$env->id, 'slack']) }}'>Slack</a> <a class='btn btn-color-normal' href='{{ action('NotifierController@new', [$env->id, 'email']) }}'>Email</a> <a class='btn btn-color-normal disabled' href='#' title='Coming Soon!'>SMS</a>",
                showCancelButton: true ,
                showConfirmButton: false
            });
        });

        $('.delete-notifier-link').on('click', function()
        {
            var notifier = $(this).data('notifier');

            swal({
              title: 'Are you sure you want to delete this notifier?',
              text: "This can't be undone!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonClass: 'btn-color-error',
              confirmButtonText: 'Yes, delete it!'
            }).then(function() {
                $.post('{{ action('NotifierController@delete') }}', {"_method": "DELETE", "_token": "{{ csrf_token() }}", "notifier": notifier}, function(data)
                {
                    location.reload();
                })
            })
        });

    </script>
@endsection