<?php

namespace LucNham\LunarCalendar\Converters;

use DateInterval;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use LucNham\LunarCalendar\Contracts\ZoneAccessible;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;

/**
 * Converts a Lunar date time string to Lunar guaranteed date time properties
 */
class LunarStringToLunarGuaranteed extends LunarUnsafeToLunarGuaranteed implements ZoneAccessible
{
    /**
     * Create new converter
     *
     * @param string $datetime
     * @param DateTimeZone|null $timezone
     */
    public function __construct(private string $datetime, private ?DateTimeZone $timezone = null)
    {
        // Parse leap month signs
        $leap = false;

        foreach (['(+)', '[+]'] as $leapSign) {
            if (str_contains($datetime, $leapSign)) {
                $datetime = str_replace($leapSign, '', $datetime);
                $datetime = trim($datetime);
                $leap = true;
            }
        }

        $pattern = '/[0-9]{1,2}\+[\/|\-|\.]/';
        preg_match($pattern, $datetime, $matches);

        if (count($matches) > 0) {
            $replacement = str_replace('+', '', $matches[0]);
            $datetime = str_replace($matches[0], $replacement, $datetime);
            $leap = true;
        }

        // Parse slashed format
        $pattern = '/[0-9]{1,4}[\/]/';
        preg_match_all($pattern, $datetime, $output);

        foreach ($output as $key => $item) {
            $item = str_replace('/', '-', $item);
            $datetime = str_replace($output[$key], $item, $datetime);
        }

        // Parse date time
        $data = date_parse($datetime);

        if ($data['error_count']) {
            $key = array_key_first($data['errors']);
            $message = array_shift($data['errors']);

            throw new DateMalformedStringException("Failed to parse time string ({$datetime}) at position {$key}: {$message}");
        }

        // Parse timezone & offset
        if (
            isset($data['zone']) &&
            isset($data['zone_type']) &&
            $data['zone_type'] == 1
        ) {
            $data['offset'] = $data['zone'];

            $h = $data['zone'] / 3600;
            $d = abs($h - floor($h));

            $h = ($h >= 0)
                ? str_pad($h, 3, '+0', STR_PAD_LEFT)
                : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

            $tz = $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);

            $data['timezone'] = new DateTimeZone($tz);
        } elseif (
            isset($data['zone_type']) &&
            isset($data['tz_id'])
        ) {
            $timezone = new DateTimeZone($data['tz_id']);

            $gre = (new DateTime(
                datetime: "{$data['year']}-{$data['month']}-{$data['day']}",
                timezone: $timezone
            ))
                ->add(new DateInterval('P30D'));

            $data['timezone'] = $timezone;
            $data['offset'] = $timezone->getOffset($gre);
        } else {
            $timezone = $timezone ? $timezone : new DateTimeZone(date_default_timezone_get());

            $gre = (new DateTime(
                datetime: "{$data['year']}-{$data['month']}-{$data['day']}",
                timezone: $timezone
            ))
                ->add(new DateInterval('P30D'));

            $data['timezone'] = $timezone;
            $data['offset'] = $timezone->getOffset($gre);
        }

        $this->timezone = $data['timezone'];

        parent::__construct(
            lunar: new LunarDateTimeInterval(
                d: $data['day'],
                m: $data['month'],
                y: $data['year'],
                h: $data['hour'],
                i: $data['minute'],
                s: $data['second'],
                leap: $leap
            ),
            offset: $data['offset']
        );
    }

    /**
     * @inheritDoc
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * @inheritDoc
     */
    public function getOffset(): int
    {
        return $this->offset();
    }
}
