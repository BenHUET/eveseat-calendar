<?php

namespace Seat\Kassie\Calendar\Helpers;

/**
 * Class Helper.
 *
 * @package Seat\Kassie\Calendar\Helpers
 */
class Helper
{
    /**
     * @param $importance
     * @param $emoji_full
     * @param $emoji_half
     * @param $emoji_empty
     * @return string
     */
    public static function ImportanceAsEmoji($importance, string $emoji_full, string $emoji_half, string $emoji_empty): string {
        $output = "";

        $tmp = explode('.', (string) $importance);
        $val = $tmp[0];
        $dec = 0;

        if (count($tmp) > 1)
            $dec = $tmp[1];

        for ($i = 0; $i < $val; $i++)
            $output .= $emoji_full;

        $left = 5;
        if ($dec != 0) {
            $output .= $emoji_half;
            $left--;
        }

        for ($i = $val; $i < $left; $i++)
            $output .= $emoji_empty;

        return $output;
    }

    /**
     * @param $op
     * @return \Closure
     */
    public static function BuildSlackNotificationAttachment($op) {
        $url = url('/calendar/operation', [$op->id]);

        $fields = [];

        $fields[trans('calendar::seat.starts_at')] = $op->start_at->format('F j @ H:i EVE');
        $fields[trans('calendar::seat.duration')] = $op->getDurationAttribute() ?: trans('calendar::seat.unknown');

        $fields[trans('calendar::seat.importance')] =
            self::ImportanceAsEmoji(
                $op->importance,
                setting('kassie.calendar.slack_emoji_importance_full', true),
                setting('kassie.calendar.slack_emoji_importance_half', true),
                setting('kassie.calendar.slack_emoji_importance_empty', true));

        $fields[trans('calendar::seat.fleet_commander')] = $op->fc ?: trans('calendar::seat.unknown');
        $fields[trans('calendar::seat.staging_system')] = $op->staging_sys ?: trans('calendar::seat.unknown');
        $fields[trans('calendar::seat.staging_info')] = $op->staging_info ?: trans('calendar::seat.unknown');
	$fields[trans('calendar::seat.description')] = $op->description ?: trans('calendar::seat.unknown');

        return function ($attachment) use ($op, $url, $fields): void {
            $attachment->title($op->title, $url)
                ->fields($fields)
                ->footer(trans('calendar::seat.created_by') . ' ' . $op->user->name)
                ->markdown(['fields']);
        };
    }

}
