@extends('home')

@section('fill')
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12">
                    <h4>My Group ({{ Auth::user()->group->group_name }})</h4>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s3">
                            <a href="#my_account">
                                My Group
                            </a>
                        </li>
                        @if(Auth::user()->can('manage-users'))
                            <li class="tab col s3">
                                <a href="#manage_group_users">
                                    Manage Group Users
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('manage-group'))
                            <li class="tab col s3">
                                <a href="#manage_group_settings">
                                    Manage Group Settings
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('billing'))
                            <li class="tab col s3">
                                <a href="#billing">
                                    Billing Info
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <div id="my_account">
                        Your group is currently on the {{ Auth::user()->group->plan->name }} plan.
                        <br />
                        Your group is currently using {{ Auth::user()->group->repositories->count() }} {{ str_plural('repository', Auth::user()->group->repositories->count()) }} out of your
                        @if(Auth::user()->group->plan->repository_limit == 0)
                            unlimited
                        @else
                            {{ Auth::user()->group->plan->repository_limit }}
                        @endif
                        repository limit.
                        <br />
                        You currently have {{ Auth::user()->group->users->count() }} {{ str_plural('member', Auth::user()->group->users->count()) }} out of your
                        @if(Auth::user()->group->plan->user_limit == 0)
                            unlimited
                        @else
                            {{ Auth::user()->group->plan->user_limit }}
                        @endif
                        member limit.
                    </div>
                    @if(Auth::user()->can('manage-users'))
                        <div id="manage_group_users">
                            <div class="row">
                                <div class="col s12 right-align">
                                    <a class="waves-effect waves-light btn btn-color-normal col s12 m5 offset-m7 l3 offset-l9" href="{{ action('GroupController@invite') }}">Invite User</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 m12">
                                    <table class="bordered striped col s12 responsive-table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Email
                                                </th>
                                                <th>
                                                    Role
                                                </th>
                                                <th>
                                                    Join Date
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(Auth::user()->group->users as $user)
                                                <tr>
                                                    <td>
                                                        {{ $user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                    <td>
                                                        {{ $user->roles()->first()->display_name }}
                                                    </td>
                                                    <td>
                                                        {{ $user->created_at->format('Y-m-d') }}
                                                    </td>
                                                    <td>
                                                        <i class="material-icons dployr-blue">mode_edit</i>
                                                        <i class="material-icons red-text">close</i>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->can('manage-group'))
                        <div id="manage_group_settings">
                            <div class="row">
                                <div class="col s12 right-align">
                                    Settings here
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->can('billing'))
                        <div id="billing">
                            <div class="row">
                                <div class="col s12 center-align">
                                    Billing doesn't exist yet while we focus on making dployr as good as it can be for you!
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
