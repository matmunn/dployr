@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <div class="row">
                <div class="col s12">
                    <h4>{{ $repo->name }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <a class="waves-effect waves-light btn" href="{{ action('EnvironmentController@new', $repo->id) }}">New Environment</a>
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
                                    Environment Type
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($repo->environments) > 0)
                                @foreach($repo->environments as $environment)
                                    <tr>
                                        <td><a href="{{ action('EnvironmentController@manage', $environment->id) }}">{{ $environment->name }}</a></td>
                                        <td>{{ $environment->type }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="center-align">
                                        This repository has no environments
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