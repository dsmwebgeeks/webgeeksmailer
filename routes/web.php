<?php

use App\Jobs\GenerateMailChimpCampaign;
use App\EventbriteEvents;
use Illuminate\Support\Facades\Log;

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
Route::get('/events/{id}', 'HomeController@show');
Route::post('/emails/generate/{id}', 'HomeController@generate')->name('generate');

Route::post('/webhooks/eventbrite', function() {
    $url = request()->input('api_url');
    $pieces = array_filter(explode('/', $url));
    $id = array_pop($pieces);

    try {
        $event = EventbriteEvents::get($id);

        GenerateMailChimpCampaign::dispatch($event);
    } catch (\Exception $e) {
        Log::warning("Eventbrite webhook failed: {$e->getMessage()}");
    }

    return response()->json(['status' => 'ok']);
});
