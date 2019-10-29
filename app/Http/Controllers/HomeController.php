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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(EventbriteEvents::get($id));
    }

    public function generate($id)
    {
        $event = EventbriteEvents::get($id);

        GenerateMailChimpCampaign::dispatch($event);

        return redirect('/home')->with('status', 'MailChimp campaign generated. Check your email for a link.');
    }
}
