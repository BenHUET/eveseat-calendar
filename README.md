# eveseat-calendar
[![Latest Stable Version](https://img.shields.io/packagist/v/kassie/calendar.svg?style=flat-square)](https://packagist.org/packages/kassie/calendar)
[![Next Version](https://img.shields.io/packagist/vpre/kassie/calendar.svg?style=flat-square)](https://packagist.org/packages/kassie/calendar)
[![License](https://img.shields.io/badge/license-GPLv3-blue.svg?style=flat-square)](https://raw.githubusercontent.com/warlof/slackbot/master/LICENSE)

Calendar plugin for EVE SeAT 2.x and 3.x

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
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\CalendarSettingsTableSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\CalendarTagsSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\ScheduleSeeder`

## Since 1.3.2
Since 1.3.2, the PAP mechanism has been implemented. A few extra setup steps are required :
* Create a new application on https://developers.eveonline.com with authentication & api access
  * add the scope `esi-fleets.read_fleet.v1` to it
  * put the following value into callback URL : `{seatpublicuri}/calendar/auth/callback` (replace `seatpublicuri` with the url you're using to access to your SeAT)
* Open `.env` file which is located at root SeAT directory
* At the end of the files, add the following values
  * CALENDAR_EVE_CLIENT_ID=`The client ID from the created application`
  * CALENDAR_EVE_CLIENT_SECRET=`The client secret from the created application`
  * CALENDAR_SSO_BASE=https://login.eveonline.com/oauth
  * CALENDAR_ESI_SERVER=tranquility

# Feedbacks or support
@kassie_yvo on eve-seat.slack.com  
kassie.yvo@gmail.com  
Kassie Yvo in-game  

If you like this module, consider giving some ISK, I'm space poor.

# Screenshots (out of date)
## Main display  
![Main display](http://i.imgur.com/UXr9LfX.png "Main display")

## Details of an operation  
![Details of an operation](http://i.imgur.com/TNZMp4t.png "Details of an operation")

## Customize your tags  
![Customize your tags](http://i.imgur.com/byVK549.png "Customize your tags")

## Slack integration  
![Slack integration](http://i.imgur.com/zV2w9sx.png "Slack integration")

## Pap feature
![Paps charts](https://user-images.githubusercontent.com/648753/34275321-0af18d90-e69d-11e7-9a93-31c07f4b303c.png "Paps charts")
![Paps character tracking](https://user-images.githubusercontent.com/648753/34328226-dc165886-e8d9-11e7-8084-731b0d674f8d.png "Paps character tracking")
![Bind FC](https://user-images.githubusercontent.com/648753/34275364-4af34726-e69d-11e7-8349-8ecd9d785161.png)
![Paps fleet as FC](https://user-images.githubusercontent.com/648753/34275326-17840196-e69d-11e7-8c2e-7a86c7632bf9.png)
