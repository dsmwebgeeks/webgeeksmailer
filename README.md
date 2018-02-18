# Web Geeks Mailer

When new Web Geeks events are published on Eventbrite, this app generates a new MailChimp campaign based on existing templates and populates the event data.

It sends an email to Web Geeks organizers when the campaign is ready to be viewed. Then, the campaign can be edited or scheduled for sending.

## Development

### Requirements

- PHP 7.1+ and Composer
- MySQL
- Redis

### Setup

1. Clone the repo
1. `composer install`
1. Set the proper keys in `.env`, especially for Eventbrite and MailChimp integrations. Don't forget to create and set the values for your MySQL and Redis databases.
1. Run `php artisan migrate`
1. Visit the site using a web server or `php artisan serve`
