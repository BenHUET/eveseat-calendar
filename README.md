# eveseat-calendar
Calendar plugin for EVE SeAT 2.X

# Features
* Create/Update/Cancel/Delete/Close & Tag operations
* Register to operations
* Slack integration
* Permissions

# Release
https://packagist.org/packages/kassie/calendar

# Installation
* `composer require kassie/calendar` in the SeAT root directory
* Append to the provider array in `config/app.php` the service provider of this package : `Seat\Kassie\Calendar\CalendarServiceProvider::class`
* `php artisan vendor:publish --force`
* `php artisan migrate`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\Seeders\\CalendarSettingsTableSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\Seeders\\CalendarTagsSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\Seeders\\ScheduleSeeder`

# Feedbacks or support
@kassie_yvo on eve-seat.slack.com  
kassie.yvo@gmail.com  
Kassie Yvo in-game  

If you like this module, consider giving some ISK, I'm space poor.

# Screenshots
## Main display  
![Main display](http://i.imgur.com/UXr9LfX.png "Main display")

## Details of an operation  
![Details of an operation](http://i.imgur.com/TNZMp4t.png "Details of an operation")

## Customize your tags  
![Customize your tags](http://i.imgur.com/byVK549.png "Customize your tags")

## Slack integration  
![Slack integration](http://i.imgur.com/zV2w9sx.png "Slack integration")