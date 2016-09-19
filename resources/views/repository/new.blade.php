@extends('home')

@section('fill')
    <div class="container">
        <div class="section">

            <!--   Icon Section   -->
{{--             <div class="row">
                <div class="col s12 m4">
                    <a class="waves-effect waves-light btn" href="{{ action('RepositoryController@new') }}">Connect Repository</a>
                </div>
            </div> --}}
            <div class="row">
                <div class="col s12 m12">
                    <form action="{{ action('RepositoryController@save') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-field">
                            <input id="name" type="text" class="validate" name="name">
                            <label for="name">Repository name</label>
                        </div>
                        <div class="input-field">
                            <input id="url" type="text" class="validate" name="url">
                            <label for="url">Repository URL</label>
                        </div>
                        <input type="submit" class="btn green" value="Save">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection