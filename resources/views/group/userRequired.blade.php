@extends('home')

@section('fill')
    @if(count($errors) > 0)
        <div class="section">
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
        </div>
    @endif
    <div class="container">
        <div class="section">
                <div class="row">
                    <div class="col s12 m12" style="text-align:center">
                        <div class="message-success">Thanks for choosing dployr!<br />We've recently added groups and so now we'd like to offer you the opportunity to choose your group name, even if you use dployr for yourself.<br />Once you've chosen a group name you're free to get back to deploying your code!</div>
                    </div>
                </div>
            <div class="row">
                <div class="col s12">
                    <h4>New Group</h4>
                </div>
            </div>
            <form action="{{ action('GroupController@saveUserRequired') }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col s12 m12">
                        <div class="input-field">
                            <input type="text" name="name" value="{{ old('name') }}">
                            <label>Your Group Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m5 l3">
                        <input type="submit" class="btn btn-color-success col s12" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection