<?php

use App\Jobs\GenerateMailChimpCampaign;
use App\EventbriteEvents;

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
})->middleware('guest');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/emails/generate/{id}', 'HomeController@generate')->name('generate');

Route::post('/webhooks/eventbrite', function() {
    $url = request()->input('api_url');
    $pieces = explode('/', $url);
    $id = array_pop($pieces);
    $event = EventbriteEvents::get($id);

    GenerateMailChimpCampaign::dispatch($event);

    return response()->json(['status' => 'ok']);
});
