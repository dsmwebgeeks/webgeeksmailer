<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventbriteEvents;
use App\Jobs\GenerateMailChimpCampaign;

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
        $events = EventbriteEvents::recent();

        return view('home', compact('events'));
    }

    public function generate($id)
    {
        $event = EventbriteEvents::get($id);

        GenerateMailChimpCampaign::dispatch($event);
    }
}
