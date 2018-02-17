<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zttp\Zttp;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->getEvents()
            ->take(5)
            ->map(function($event) {
                $event['date'] = Carbon::parse($event['start']['utc'])->format("Y-m-d g:i a");

                return $event;
            });
        // dd($events);

        return view('home', compact('events'));
    }

    protected function getEvents()
    {
        $response = Zttp::get(
            'https://www.eventbriteapi.com/v3/users/me/owned_events/',
            [
                'token' => env('EVENTBRITE_TOKEN'),
                'order_by' => 'start_desc',
            ]
        );
        return collect($response->json()['events']);
    }
}
