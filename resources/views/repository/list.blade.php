@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <div class="row">
                <div class="col s12">
                    <h4>Your Repositories</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <a class="waves-effect waves-light btn btn-color-normal col s12 m5 offset-m7 l3 offset-l9 new-repository-button" href="#">Connect Repository</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped col s12 responsive-table">
                        <thead>
                            <tr>
                                <th>
                                    Repository Name
                                </th>
                                <th>
                                    Repository URL
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($repositories) > 0)
                                @foreach($repositories as $repository)
                                    <tr>
                                        <td><a href="{{ action('RepositoryController@manage', $repository->id) }}">{{ $repository->name }}</a></td>
                                        <td>{{ $repository->url }}</td>
                                        <td>
                                            @if($repository->status & $repository::STATUS_INITIALISING)
                                                Initialising 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & $repository::STATUS_UPDATING)
                                                Updating 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & $repository::STATUS_DEPLOYING)
                                                Deploying 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & $repository::STATUS_ERROR)
                                                <span class="red-text tooltipped" data-position="top" data-delay="50" data-tooltip="
                                                @if($repository->last_action == "update")
                                                    There was a problem updating your repository.
                                                @endif

                                                @if($repository->last_action == "deploy")
                                                    There was a problem deploying one of your environments.
                                                @endif

                                                @if($repository->last_action == "clone")
                                                    There was a problem initialising your repository.
                                                @endif
                                                ">
                                                    <i class="material-icons" style="vertical-align: bottom">clear</i>
                                                    Error
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="center-align">
                                        You have no repositories
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function()
    {
        $('.new-repository-button').on('click', function () {
            swal({
                title: "New repository",
                html: "Choose your repository type:<br /><br /><a class='btn btn-color-normal' href='{{ action('RepositoryController@new') }}'>Self Hosted</a> <a class='btn btn-color-normal disabled' href='#' title='Coming Soon!'>Github</a><br /><br /><a class='btn btn-color-normal disabled' href='#' title='Coming Soon!'>Bitbucket</a>",
                showCancelButton: true ,
                showConfirmButton: false
            });
        });
    });
    </script>
@endsection