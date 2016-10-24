<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Server;
use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function new($environment, $server)
    {
        if (!$repo = Environment::find($environment)->repository) {
            return redirect()->action("HomeController@dashboard")
                ->with("error", "Couldn't get the repository for the given environment");
        }

        if (!Auth::user()->repositories->contains($repo)) {
            return redirect()->action("HomeController@dashboard")
                ->with("error", "No matching repository could be found for your account");
        }

        return view('server.new.'.$server)->with(compact('environment', 'server'));
    }

    public function save(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'type' => 'required|string',
                'url' => 'required|string',
                'user' => 'required|string',
                'password' => 'required|string',
                'path' => 'required|string',
                'environment' => 'required|integer',
                'port' => 'integer'
            ]
        );

        if  (!$environment = Auth::user()->environments->find($request->environment)) {
            return redirect()->action('HomeController@dashboard')
                ->with("error", "The specified environment couldn't be found.");
        }

        $server = new Server([
            'name'=> $request->name,
            'type' => $request->type,
            'server_name' => $request->url,
            'server_username' => $request->user,
            'server_password' => $request->password,
            'server_path' => $request->path,
        ]);

        if ($request->has('port')) {
            $server->server_port = $request->port;
        }

        if (!$environment->servers()->save($server)) {
            return redirect()->action("EnvironmentController@manage", $environment)
                ->with("error", "The server couldn't be saved. Please try again later");
        }

        return redirect()->action('ServerController@manage', $server->id);
    }

    public function manage($server)
    {
        $server = Server::where('id', $server)
            ->with(['deployments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->first();
        if (!$server || !Auth::user()->environments->contains($server->environment)) {
            return redirect()->action("HomeController@dashboard")->with("error", "The specified server couldn't be found");
        }

        return view('server.manage')->with(compact('server'));
    }
}
