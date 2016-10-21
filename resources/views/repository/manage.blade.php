@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <div class="row">
                <div class="col s12">
                    <h4>{{ $repo->name }}</h4>
                </div>
            </div>
            @if($repo->status == $repo::STATUS_ERROR && $repo->last_action == "clone")
                <div class="row">
                    <div class="col s12 m12" style="text-align:center">
                        <div class="message-error">This repository has been unable to initialise. This may be due to not yet having the deploy key set up. <a href="{{ action('RepositoryController@initialise', $repo) }}">Try again now.</a></div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col s12 m12">
                    Deploy Key:<br />
                    <div class="monospace code">{{ $repo->public_key }}</div>
                    or <a href="{{ action('RepositoryController@key', $repo) }}">download this key as a file.</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    Web hook refresh URL:<br />
                    <div class="monospace code">{{ env('APP_URL') }}/api/refresh/{{ e($repo->secret_key) }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <a class="waves-effect waves-light btn btn-color-normal col s12 m5 offset-m7 l3 offset-l9" href="{{ action('EnvironmentController@new', $repo->id) }}">New Environment</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <thead>
                            <tr>
                                <th>
                                    Environment Name
                                </th>
                                <th>
                                    Environment Branch
                                </th>
                                <th>
                                    Number of servers
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($repo->environments) > 0)
                                @foreach($repo->environments as $environment)
                                    <tr>
                                        <td><a href="{{ action('EnvironmentController@manage', $environment->id) }}">{{ $environment->name }}</a></td>
                                        <td>
                                            {{ $environment->branch }}
                                        </td>
                                        <td>{{ $environment->servers->count() }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="center-align">
                                        This repository has no environments
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 right-align">
                    <a class="waves-effect waves-light btn btn-color-error delete-button col s12 m5 offset-m7 l3 offset-l9" href="#">Delete Repository</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.delete-button').on('click', function()
        {
            swal({
              title: "Delete repository",
              text: "Are you sure you want to delete this repository?<br /><br /><strong>This can't be undone!</strong><br /><br />Type the repository name <div class='monospace code'>{{ $repo->name }}</div> below to confirm deletion.",
              input: "text",
              showCancelButton: true,
              closeOnConfirm: false,
              type: "error",
              confirmButtonClass: "btn-color-error"
            }).then(function(inputValue) {
                if(inputValue === '{{ $repo->name }}')
                {
                    // window.location = '{{ action('RepositoryController@delete', $repo) }}';
                    $.post('{{ action('RepositoryController@delete', $repo) }}', {"_method": "DELETE", "_token": "{{ csrf_token() }}"}, function(data)
                    {
                        // Redirect to repostiory list
                        window.location = '{{ action('RepositoryController@list') }}';
                    });
                }
                else
                {
                    swal({
                        title: 'Oops...',
                        text: "The repository name didn't match",
                        type: 'error',
                        confirmButtonClass: 'btn-color-success'});
                }
            }).done();
        });
    </script>
@endsection