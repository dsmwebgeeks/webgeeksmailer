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

    public static function get($id)
    {
        $response = (new static)->request('events/' . $id, [
            'expand' => 'venue'
        ]);

        return (new static)->formatEvent($response);
    }

    public function request($endpoint, $args = [])
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
            'id' => $data['id'],
            'name' => $data['name']['text'],
            'url' => $data['url'],
            'image' => $data['logo']['url'],
            'description' => $data['description']['html'],
            'date' => Carbon::parse($data['start']['local'])->format("F j, Y"),
            'start' => Carbon::parse($data['start']['local'])->format("g:ia"),
            'end' => Carbon::parse($data['end']['local'])->format("g:ia"),
            'venue' => empty($data['venue']) ? '' :
                '<b>' . $data['venue']['name'] . "</b><br>" . implode("<br>", $data['venue']['address']['localized_multi_line_address_display'])
        ];
    }
}
