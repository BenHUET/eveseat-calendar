# eveseat-calendar

[![Latest Stable Version](https://img.shields.io/packagist/v/kassie/calendar.svg?style=for-the-badge)](https://packagist.org/packages/kassie/calendar)
[![Next Version](https://img.shields.io/packagist/vpre/kassie/calendar.svg?style=for-the-badge)](https://packagist.org/packages/kassie/calendar)
[![Downloads](https://img.shields.io/packagist/dt/kassie/calendar?style=for-the-badge)](https://packagist.org/packages/BenHUET/eveseat-calendar)
[![Core Version](https://img.shields.io/badge/SeAT-4.0.x-blueviolet?style=for-the-badge)](https://github.com/eveseat/seat)
[![License](https://img.shields.io/github/license/BenHUET/eveseat-calendar?style=for-the-badge)](https://github.com/BenHUET/eveseat-calendar/blob/master/LICENCE)

Calendar plugin for EVE SeAT

# Features

* Create/Update/Cancel/Delete/Close & Tag operations
* Register to operations
* Slack integration
* Permissions

# Release

https://packagist.org/packages/kassie/calendar

# Compatibility

| SeAT Core | Calendar | Branch                                                           |
| --------- | -------- | ---------------------------------------------------------------- |
| 2.x       | 1.x      | [2.0.x](https://github.com/BenHUET/eveseat-calendar/tree/2.0.x)  |
| 3.x       | 3.x      | [3.0.x](https://github.com/BenHUET/eveseat-calendar/tree/3.0.x)  |
| 4.x       | 4.x      | [master](https://github.com/BenHUET/eveseat-calendar)            |

# Installation

* `composer require kassie/calendar` in the SeAT root directory
* `php artisan vendor:publish --force`
* `php artisan migrate`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\CalendarSettingsTableSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\CalendarTagsSeeder`
* `php artisan db:seed --class=Seat\\Kassie\\Calendar\\database\\seeds\\ScheduleSeeder`

## Since 1.3.2

Since 1.3.2, the PAP mechanism has been implemented. You need `esi-fleets.read_fleet.v1` into your requested scopes list.

# Feedbacks or support

@kassie_yvo on eve-seat.slack.com  
kassie.yvo@gmail.com  
Kassie Yvo in-game  

If you like this module, consider giving some ISK, I'm space poor.

# Screenshots

## Main display  

![Main display](./img/main_display.png "Main display")

## Details of an operation

![Details of an operation](./img/operation_details.png "Details of an operation")

## Customize your tags

![Customize your tags](./img/tags_creation.png "Customize your tags")
![Customize your tags](./img/tags_management.png "Manage your tags")

## Slack integration

![Slack integration](http://i.imgur.com/zV2w9sx.png "Slack integration")

## Pap feature

![Paps charts](https://user-images.githubusercontent.com/648753/34275321-0af18d90-e69d-11e7-9a93-31c07f4b303c.png "Paps charts")
![Paps character tracking](https://user-images.githubusercontent.com/648753/34328226-dc165886-e8d9-11e7-8084-731b0d674f8d.png "Paps character tracking")
