<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Models\Group;
use App\Models\Invite;
use App\Notifications\Registered;
use App\Jobs\NotifyOfRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/repository';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data The data to validate.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:8|confirmed',
                'group_name' => 'required|string',
                // 'site_name' => 'required|string',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'site_name' => "",
            ]
        );

        // For now we just want to associate the default plan
        $plan = Plan::find(session()->pull('signup_plan'));

        $group = new Group;
        $group->group_name = $data['group_name'];
        $group->admin_user = $user->id;

        $plan->groups()->save($group);
        $group->users()->save($user);

        // $user->attachRole(Role::where('name', 'owner')->first());

        // I'd like to know when someone registers
        dispatch(new NotifyOfRegistration($user));
        
        $user->notify(new Registered($user));
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($invite = null)
    {
        $code = null;
        if (!is_null($invite)) {
            if (!$code = Invite::where('hash', $invite)
                ->where('expires_at', '>', Carbon::now())->first()) {
                session()->flash(
                    'error',
                    "Your registration token is invalid and you ".
                        "won't join a group if you continue registration."
                );
                session()->forget('invite');
            } else {
                session('invite', $code);
            }
        }

        // This is for local only,

        if (!session()->has('signup_plan')) {
            // Probably need to change this once we go live,
            // set it to the ID of the free plan
            session(['signup_plan' => 1]);
        }

        return view('auth.register')->with(compact('code'));
    }
}
