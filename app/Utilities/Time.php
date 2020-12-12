<?php

namespace App\Utilities;

use Carbon\Carbon;
use DateTime as PhpDateTime;

class Time
{
    /**
     * Format micro timestamp to sql string format
     * @param string $stringTimestamp
     * @return bool|false|string
     */
    public static function timestampToSqlFormat($stringTimestamp)
    {
        $formattedString = substr($stringTimestamp, 0, 10);

        $formattedString = date('Y-m-d H:i:s', $formattedString);

        return $formattedString;
    }

    /**
     * Create Datetime by timestamp.
     * @param string $stringTimestamp
     * @return PhpDateTime|Carbon
     * @throws \Exception
     */
    public static function timestampToDate($stringTimestamp)
    {
        $formattedString = substr($stringTimestamp, 0, 10);

        $date = new Carbon();

        $date->setTimestamp($formattedString);

        return $date;
    }

    /**
     * Get micro timestamp by Datetime object
     * @param PhpDateTime|Carbon $datetime
     * @return float|int
     */
    public static function DateTimeToTimestampMs(PhpDateTime $datetime)
    {
        return $datetime->getTimestamp() * 1000;
    }

    /**
     * Check is timestamp
     *
     * @param $timestamp
     * @return bool
     */
    public static function validateTimeStampMs($timestamp)
    {
        try {
            Carbon::createFromTimestampMs($timestamp);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Format date by format string.
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function reformat($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }

    /**
     * @des get current time with format Y-m-d
     * @param string $format
     * @return string
     */
    public static function getCurrentTime($format = 'Y-m-d') {
        return Carbon::now()->format($format);
    }

    /**
     * @des Parse carbon time to date string
     * @param $time
     * @return string
     */
    public static function parseTimeToDateString($time)
    {
        return Carbon::parse($time)->toDateString();
    }

    /**
     * Format date by format string.
     * @param string $date
     * @param string $fromFormat
     * @param string $format
     * @return string|false
     */
    public static function reFormatDate($date, $fromFormat = 'Y/m/d', $format = 'Y-m-d')
    {
        try {
            return $date ? Carbon::createFromFormat($fromFormat, $date)->format($format) : null;
        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * Format month by format string.
     * @param string $month
     * @param string $fromFormat
     * @param string $format
     * @return string|false
     */
    public static function reFormatMonth($month, $fromFormat = 'Y/m', $format = 'Y-m-d')
    {
        try {
            return $month ? Carbon::createFromFormat($fromFormat, $month)->firstOfMonth()->format($format) : null;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get dates array from range
     *
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function getDatesFromRange($start, $end) {
        $dates = array($start);

        while(end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
        }

        return $dates;
    }
}
