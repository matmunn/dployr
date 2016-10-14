@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12 m12">
                    Your current plan: {{ Auth::user()->plan->name }} (Max repositories: 
                    @if(Auth::user()->plan->repository_limit == 0)
                        Unlimited
                    @else
                        {{ Auth::user()->plan->repository_limit }}
                    @endif)
                    <br />
                    You are currently using {{ Auth::user()->repositories->count() }} repositories of your allocation.
                </div>
            </div>
        </div>
    </div>
@endsection
