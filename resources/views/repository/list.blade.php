@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <!--   Icon Section   -->
            <div class="row">
                <div class="col s12 m4">
                    <a class="waves-effect waves-light btn">Connect Repository</a>
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
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($repositories) > 0)
                                @foreach($repositories as $repository)
                                    <tr>
                                        <td><a href="{{ action('RepositoryController@manage', $repository->id) }}">{{ $repository->name }}</a></td>
                                        <td>{{ $repository->url }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="center-align">
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