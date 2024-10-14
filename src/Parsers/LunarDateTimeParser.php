<?php

namespace LucNham\LunarCalendar\Parsers;

use DateInterval;
use DateTime;
use DateTimeZone;
use LucNham\LunarCalendar\Contracts\LunarParser;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarParsingResults;

/**
 * Parse Lunar date time string information into individual attributes
 */
class LunarDateTimeParser implements LunarParser
{
    /**
     * @var string Stores temporary modifying date time string
     */
    private string $datetime = '1970-01-01T00:00+0000';

    /**
     * Stores parse results
     *
     * @var array
     */
    private $results = [
        'day'           => 1,
        'month'         => 1,
        'year'          => 1970,
        'hour'          => 0,
        'minute'        => 0,
        'second'        => 0,
        'leap'          => false,
        'warning_count' => 0,
        'warnings'      => [],
        'error_count'   => 0,
        'errors'        => [],
        'timezone'      => null,
        'offset'        => null,
    ];

    /**
     * Set part of parsing result
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    protected function set(string $key, mixed $value): self
    {
        $this->results[$key] = $value;
        return $this;
    }

    /**
     * Get part of parsing result value
     *
     * @param string $key
     * @return mixed
     */
    protected function get(string $key): mixed
    {
        return $this->results[$key];
    }

    /**
     * Parse leap month signs
     *
     * @param string $datetime
     * @return self
     */
    protected function parseLeapMonth(string $datetime): self
    {
        foreach (['(+)', '[+]'] as $leapSign) {
            if (str_contains($datetime, $leapSign)) {
                $datetime = str_replace($leapSign, '', $datetime);

                $this->set('leap', true);
                $this->datetime = $datetime;
            }
        }

        $pattern = '/[0-9]{1,2}\+[\/|\-|\.]/';
        preg_match($pattern, $datetime, $matches);

        if (count($matches) == 0) {
            return $this;
        }

        $replacement = str_replace('+', '', $matches[0]);

        $datetime = str_replace($matches[0], $replacement, $datetime);

        $this->set('leap', true);
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Parse slashed string format
     *
     * @param string $datetime
     * @return self
     */
    protected function parseSlashedFormat(string $datetime): self
    {
        $pattern = '/[0-9]{1,4}[\/]/';
        preg_match_all($pattern, $datetime, $output);

        foreach ($output as $key => $item) {
            $item = str_replace('/', '-', $item);
            $datetime = str_replace($output[$key], $item, $datetime);

            $this->datetime = $datetime;
        }

        return $this;
    }

    /**
     * Parse date time, timezone and offset
     *
     * @param string $datime
     * @param DateTimeZone|null $timezone
     * @return self
     */
    protected function parseDateTime(string $datime, ?DateTimeZone $timezone = null): self
    {
        $data = date_parse($datime);

        if ($data['day'] === 30 && $data['month'] === 2) {
            foreach ($data['warnings'] as $k => $w) {
                if ($w === 'The parsed date was invalid') {
                    $data['warning_count'] = $data['warning_count'] - 1;
                    unset($data['warnings'][$k]);

                    break;
                }
            }
        }

        foreach ($data as $key => $value) {
            if (isset($this->results[$key])) {
                $this->results[$key] = $value;
            }
        }

        // Timezone & offset parse
        if (
            isset($data['zone']) &&
            isset($data['zone_type']) &&
            $data['zone_type'] == 1
        ) {
            $this->set('offset', $data['zone']);
            $h = $data['zone'] / 3600;
            $d = abs($h - floor($h));

            $h = ($h >= 0)
                ? str_pad($h, 3, '+0', STR_PAD_LEFT)
                : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

            $tz = $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);
            $this->set('timezone', new DateTimeZone($tz));
        } elseif (
            isset($data['zone_type']) &&
            isset($data['tz_id'])
        ) {
            $timezone = new DateTimeZone($data['tz_id']);

            $gre = (new DateTime(
                datetime: "{$this->get('year')}-{$this->get('month')}-{$this->get('day')}",
                timezone: $timezone
            ))
                ->add(new DateInterval('P30D'));

            $this->set('timezone', $timezone);
            $this->set('offset', $timezone->getOffset($gre));
        } else {
            $timezone = $timezone ? $timezone : new DateTimeZone(date_default_timezone_get());

            $gre = (new DateTime(
                datetime: "{$this->get('year')}-{$this->get('month')}-{$this->get('day')}",
                timezone: $timezone
            ))
                ->add(new DateInterval('P30D'));

            $this->set('timezone', $timezone);
            $this->set('offset', $timezone->getOffset($gre));
        }

        return $this;
    }

    /**
     * Return the Lunar date time parsing results
     * 
     *
     * @param string $lunar
     * @param DateTimeZone|null $timezone
     * @return LunarParsingResults
     */
    public function parse(string $lunar, ?DateTimeZone $timezone = null): LunarParsingResults
    {
        $this->datetime = $lunar;

        $this
            ->parseLeapMonth($this->datetime)
            ->parseSlashedFormat($this->datetime)
            ->parseDateTime($this->datetime);

        return new LunarParsingResults(
            interval: new LunarDateTimeInterval(
                d: $this->get('day'),
                m: $this->get('month'),
                y: $this->get('year'),
                h: $this->get('hour'),
                i: $this->get('minute'),
                s: $this->get('second'),
                leap: $this->get('leap')
            ),
            timezone: $this->get('timezone'),
            offset: $this->get('offset'),
            warnings: $this->get('warnings'),
            warning_count: $this->get('warning_count'),
            errors: $this->get('errors'),
            error_count: $this->get('error_count')
        );
    }
}
