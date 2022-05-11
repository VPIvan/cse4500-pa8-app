<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/db-migrate', function()
{
    Artisan::call('migrate');
    echo Artisan::output();
});

Route::get('/authorization', function (Request $request) {  //Get authorization
    $request->session()->put('state', $state = Str::random(40));
 
    $query = http_build_query([
        'client_id' => '1',
        'redirect_uri' => 'https://pa8-v3-app.herokuapp.com/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect('https://pa8-v3-app.herokuapp.com/oauth/authorize?'.$query);
})->name('authorization');

Route::get('/callback', function (Request $request) {   //Get Token after authorization
    $state = $request->session()->pull('state');
    
    if(strlen($state) > 0 && $state === $request->state) {
 
        $response = Http::asForm()->post('https://pa8-v3-app.herokuapp.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '1',
            'client_secret' => '0XVuI95B4R0HmrTbUGvRWOu0ZHCqAPk37UDZZExx',
            'redirect_uri' => 'https://pa8-v3-app.herokuapp.com/callback',
            'code' => $request->code,
        ]);
        
        $accessToken = $response->json()['access_token'];
    
        //Use the token to request data
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ])->get('https://pa8-v8-app.herokuapp.com/api/users');
        
        return $response->json();
        
    } else {
        return redirect()->route('authorization');
    }
});

require __DIR__.'/auth.php';
