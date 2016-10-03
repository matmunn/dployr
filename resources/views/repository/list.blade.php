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
                    <a class="waves-effect waves-light btn btn-color-normal" href="{{ action('RepositoryController@new') }}">Connect Repository</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    <table class="bordered striped">
                        <thead>
                            <tr>
                                <th>
                                    Repository Name
                                </th>
                                <th>
                                    Repository URL
                                </th>
                                <th style="width: 15%">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($repositories) > 0)
                                @foreach($repositories as $repository)
                                    <tr>
                                        <td><a href="{{ action('RepositoryController@manage', $repository->id) }}">{{ $repository->name }}</a></td>
                                        <td>{{ $repository->url }}</td>
                                        <td>
                                            @if($repository->status & 2)
                                                Initialising 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & 4)
                                                Updating 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & 8)
                                                Deploying 
                                                <div class="sk-folding-cube">
                                                    <div class="sk-cube1 sk-cube"></div>
                                                    <div class="sk-cube2 sk-cube"></div>
                                                    <div class="sk-cube4 sk-cube"></div>
                                                    <div class="sk-cube3 sk-cube"></div>
                                                </div>
                                            @endif
                                            @if($repository->status & 16)
                                                <span class="red-text">
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
@endsection