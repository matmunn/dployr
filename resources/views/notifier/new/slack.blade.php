@extends('home')

@section('fill')
    @if(count($errors) > 0)
        <div class="row btn-color-error white-text">
            <div class="col s12 m6 offset-m3">
                <div class="">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <h4>Create New Slack Notifier</h4>
                </div>
            </div>
            <form action="{{ action('NotifierController@save') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="slack">
                <input type="hidden" name="environment" value="{{ $env->id }}">
                <div class="row">
                    <div class="col s12 m12">
                        <div class="input-field">
                            <input type="text" name="endpoint" value="{{ old('endpoint') }}" required>
                            <label>Webhook URL</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" class="btn btn-color-success col s12 m5 l3" value="Save">
                    <a class="btn btn-color-error col s12 m5 offset-m2 l3 offset-l6" href="{{ action('EnvironmentController@manage', $env) }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection