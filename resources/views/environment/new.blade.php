@extends('home')

@section('fill')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <h4>Create New Environment</h4>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12">
                <form action="{{ action('EnvironmentController@save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="input-field">
                        <input type="text" class="validate" name="name">
                        <label>Environment name</label>
                    </div>
                    <div class="input-field">
                        <select name="type">
                            <option selected disabled>--</option>
                            <option value="FTP">FTP</option>
                        </select>
                        <label>Environment Type</label>
                    </div>
                    <div class="input-field">
                        <select name="type">
                            <option selected disabled>--</option>
                            @foreach($repo->getBranches('remote') as $branch)
                                <option value="{{ $branch }}">{{ $branch}}</option>
                            @endforeach
                        </select>
                        <label>Branch</label>
                    </div>
                    <input type="submit" class="btn green" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
    $('[name="type"]').on('change', function()
    {
        console.log('type changed');
    });
});
</script>
@endsection