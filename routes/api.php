<?php

use App\Models\Repository;
use Illuminate\Http\Request;
use App\Jobs\UpdateRepository;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/deploy/{env_id}/{token}', function($env_id, $token)
{
    echo $token . ' ' . $env_id;
});

Route::get('/refresh/{token}', function($token)
{
    if(!$repo = Repository::where('secret_key', $token)->first())
    {
        return response()->json("The specified refresh key is invalid.", 400);
    }

    $repo->status = 4;
    $repo->save();

    dispatch(new UpdateRepository($repo));

    return response()->json("success", 200);
});