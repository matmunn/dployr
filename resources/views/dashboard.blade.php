@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <h4>My Account</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12">
                    Your group is currently on the {{ Auth::user()->group->plan->name }} plan.
                    <br />
                    Your group is currently using {{ Auth::user()->group->repositories->count() }} repositories out of your
                    @if(Auth::user()->group->plan->repository_limit == 0)
                        unlimited
                    @else
                        {{ Auth::user()->group->plan->repository_limit }}
                    @endif
                    repository limit.
                </div>
            </div>
        </div>
    </div>
@endsection
