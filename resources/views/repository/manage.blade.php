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
                <div class="col s12 m12">
                    Deploy Key:<br />
                    <div class="monospace code">{{ $repo->public_key }}</div>
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
                    <a class="waves-effect waves-light btn indigo accent-2" href="{{ action('EnvironmentController@new', $repo->id) }}">New Environment</a>
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
        </div>
    </div>
@endsection