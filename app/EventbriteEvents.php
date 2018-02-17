<?php

namespace App;

use Zttp\Zttp;
use Illuminate\Support\Carbon;

class EventbriteEvents
{
    const API_BASE = 'https://www.eventbriteapi.com/v3/';

    public static function recent()
    {
        $response = (new static)->request('users/me/owned_events', [
            'order_by' => 'start_desc',
        ]);

        return collect($response['events'])->take(5)
            ->map(function($event) {
                return (new static)->formatEvent($event);
            });
    }

    public function request($endpoint, $args)
    {
        $response = Zttp::get(
            static::API_BASE . $endpoint . '/',
            array_merge(['token' => env('EVENTBRITE_TOKEN')], $args)
        );

        return $response->json();
    }

    public function formatEvent($data)
    {
        return (object) [
            'name' => $data['name']['text'],
            'image' => $data['logo']['url'],
            'date' => Carbon::parse($data['start']['local'])->format("Y-m-d g:i a"),
        ];
    }
}
