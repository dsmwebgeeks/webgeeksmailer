<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CampaignReadyForReview;

class GenerateMailChimpCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailChimp $mailchimp)
    {
        $templateId = 407189;

        $response = $mailchimp->post('/campaigns', [
            'recipients' => [
                'list_id' => 'c4a8d2acdd',
            ],
            'type' => 'regular',
            'settings' => [
                'title' => $this->event->name,
                'subject_line' => "You're invited: {$this->event->name}",
                'preview_text' => "RSVP for our next event",
                'from_name' => "Des Moines Web Geeks",
                'reply_to' => 'team@dsmwebgeeks.com',
                'template_id' => $templateId,
            ],
        ]);

        $campaignId = $response['id'];
        $campaignWebId = $response['web_id'];
        $campaignUrl = $response['archive_url'];

        $response = $mailchimp->put("/campaigns/{$campaignId}/content", [
            'template' => [
                'id' => $templateId,
                'sections' => [
                    "event_title" => '<a href="' . $this->event->url . '">' . $this->event->name . '</a>',
                    "event_image" => '<img alt="' . $this->event->name . '" src="' . $this->event->image . '" width="600" style="max-width:784px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">',
                    "event_link" => '<a href="' . $this->event->url . '" style="color:#FFFFFF;text-decoration:none;" target="_blank">Attend this event</a>',
                    "event_date" => '<b>' . $this->event->date . ' from ' . $this->event->start . ' to ' . $this->event->end . '</b>',
                    "event_location" => $this->event->venue,
                    "event_description" => $this->event->description,
                ],
            ],
        ]);

        $campaign = [
            'id' => $campaignId,
            'web_id' => $campaignWebId,
            'url' => $campaignUrl,
        ];

        Notification::route('mail', 'jplhomer@gmail.com')
            ->notify(new CampaignReadyForReview($this->event, $campaign));
    }
}
