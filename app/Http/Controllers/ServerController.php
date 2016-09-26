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
    public function new($environment, $server)
    {
        if(!$repo = Environment::find($environment)->repository)
        {
            dd("Couldn't find the repository for this environment");
        }

        if(!Auth::user()->repositories->contains($repo))
        {
            dd('No matching environment could be found for this user.');
        }

        return view('server.new.'.$server)->with(compact('environment', 'server'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'url' => 'required|string',
            'user' => 'required|string',
            'password' => 'required|string',
            'path' => 'required|string',
            'environment' => 'required|integer',
        ]);

        if(!$environment = Auth::user()->environments->find($request->environment))
        {
            dd("No matching environment could be found for this user.");
        }

        $server = new Server([
            'name'=> $request->name,
            'type' => $request->type,
            'server_name' => $request->url,
            'server_username' => $request->user,
            'server_password' => $request->password,
            'server_path' => $request->path,
        ]);

        if(!$environment->servers()->save($server))
        {
            dd("Couldn't save the server");
        }

        return redirect()->action('ServerController@manage', $server->id);
    }

    public function manage($server)
    {
        $server = Server::find($server);
        if(!$server || !Auth::user()->environments->contains($server->environment) )
        {
            dd("Couldn't find the given server");
        }

        return view('server.manage')->with(compact('server'));
    }
}
