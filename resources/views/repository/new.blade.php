@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <h4>Connect New Repository</h4>
                </div>
            </div>
            <form action="{{ action('RepositoryController@save') }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col s12 m12">
                        <div class="input-field">
                            <input id="name" type="text" class="validate" name="name">
                            <label for="name">Repository name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12">
                        <div class="input-field">
                            <input id="url" type="text" class="validate" name="url">
                            <label for="url">Repository URL</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12">
                        <input type="submit" class="btn btn-color-success col s12 m5 l3" value="Save">
                        <a class="btn btn-color-error col s12 m5 offset-m2 l3 offset-l6" href="{{ action('RepositoryController@list') }}">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection