@extends('home')

@section('fill')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h4>Create New Environment</h4>
            </div>
        </div>
        <form action="{{ action('EnvironmentController@save') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $repo->id }}" name="repo" />
            <div class="row">
                <div class="col s12 m12">
                    <div class="input-field">
                        <input type="text" class="validate" name="name">
                        <label>Environment name</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="input-field">
                        <select name="type">
                            <option selected disabled>--</option>
                            <option value="ftp">FTP</option>
                            <option value="sftp">SFTP</option>
                        </select>
                        <label>Environment Type</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="input-field">
                        <select name="branch">
                            <option selected disabled>--</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}">{{ $branch}}</option>
                            @endforeach
                        </select>
                        <label>Branch</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="input-field">
                        Deployments
                        <p>
                            <input name="deploy_mode" type="radio" value="2" checked id="auto">
                            <label for="auto">Automatic</label>
                        </p>
                        <p>
                            <input name="deploy_mode" type="radio" value="1" id="manual">
                            <label for="manual">Manual</label>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="input-field">
                        <input type="submit" class="btn btn-color-success col s12 m5 l3" value="Save">
                        <a class="btn btn-color-error col s12 m5 offset-m2 l3 offset-l6" href="{{ action('RepositoryController@manage', $repo) }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection