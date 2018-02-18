<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CampaignReadyForReview extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $campaign;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event, $campaign)
    {
        $this->event = $event;
        $this->campaign = $campaign;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = "https://us8.admin.mailchimp.com/campaigns/show/?id=" . $this->campaign['web_id'];

        return (new MailMessage)
                    ->from('webgeeksmailer@jplhomer.org', 'Web Geeks Mailer')
                    ->subject('A MailChimp campaign has been created for your new event')
                    ->line("A MailChimp campaign has been created for the Eventbrite event you just created, {$this->event->name}.")
                    ->line('You can view the campaign to make changes, schedule and send.')
                    ->action('View the Campaign', $url)
                    ->line('Hope the event goes well, humans!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
