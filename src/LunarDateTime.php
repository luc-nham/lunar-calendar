<?php

namespace LucNham\LunarCalendar;

use DateMalformedStringException;
use DateTimeZone;
use Exception;
use LucNham\LunarCalendar\Contracts\LunarDateTime as ContractsLunarDateTime;
use LucNham\LunarCalendar\Contracts\LunarDateTimeFormattable;
use LucNham\LunarCalendar\Contracts\LunarGuaranteedAccessible;
use LucNham\LunarCalendar\Contracts\ZoneAccessible;
use LucNham\LunarCalendar\Converters\DateTimeStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\LunarDateTimeToDateTimeString;
use LucNham\LunarCalendar\Converters\LunarStringToLunarGuaranteed;
use LucNham\LunarCalendar\Formatters\LunarDateTimeDefaultFormatter;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;

/**
 * Representation of Lunar calendar date and time
 */
class LunarDateTime implements ContractsLunarDateTime
{
    /**
     * Timezone offset inseconds
     *
     * @var integer
     */
    private int $offset;

    /**
     * Lunar date time interval guaranteed
     *
     * @var LunarDateTimeGuaranteed
     */
    private LunarDateTimeGuaranteed $interval;

    /**
     * Formatter class
     *
     * @var LunarDateTimeFormattable
     */
    protected LunarDateTimeFormattable $formatter;

    /**
     * Create new Lunar Date Time
     *
     * @param string $datetime              Lunar date time string to parse, default 'now' is current
     * @param DateTimeZone|null $timezone   DateTimeZone object if needed, default null. 
     */
    public function __construct(
        private string $datetime = 'now',
        private ?DateTimeZone $timezone = null,
    ) {
        $this->initComponents();
        $this->initFormatter();
    }

    /**
     * Access properties more conveniently
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        $value = match ($name) {
            'day'           => $this->interval->d,
            'month'         => $this->interval->m,
            'year'          => $this->interval->y,
            'hour'          => $this->interval->h,
            'minute'        => $this->interval->i,
            'second'        => $this->interval->s,
            'leap'          => $this->interval->l,
            'isLeapMonth'   => $this->interval->leap,
            'jdn'           => $this->interval->j,
            'timestamp'     => $this->getTimestamp(),
            default         => null
        };

        if ($value === null) {
            throw new Exception("Property dose not exists.");
        }

        return $value;
    }

    /**
     * Init necessary Lunar components
     *
     * @return void
     */
    protected function initComponents(): void
    {
        $datetime = $this->datetime;
        if (
            str_contains($this->datetime, 'G:') ||
            $datetime === '' ||
            $datetime === 'now'
        ) {
            $class = DateTimeStringToLunarGuaranteed::class;
            $datetime = str_replace('G:', '', $datetime);
        } else {
            $class = LunarStringToLunarGuaranteed::class;
        }

        try {
            /** @var ZoneAccessible&LunarGuaranteedAccessible */
            $component = new $class(
                datetime: $datetime,
                timezone: $this->timezone
            );

            $this->interval = $component->getGuaranteedLunarDateTime();
            $this->timezone = $component->getTimezone();
            $this->offset = $component->getOffset();
        } catch (\Throwable $th) {
            throw new DateMalformedStringException("Invalid date time format");
        }
    }

    /**
     * Initialize default formatter
     *
     * @return LunarDateTimeFormattable
     */
    protected function initFormatter(): void
    {
        $this->formatter = new LunarDateTimeDefaultFormatter(
            lunar: $this->getGuaranteedLunarDateTime(),
            timezone: $this->getTimezone(),
            offset: $this->getOffset()
        );
    }

    /**
     * @inheritDoc
     */
    public function getGuaranteedLunarDateTime(): LunarDateTimeGuaranteed
    {
        return $this->interval;
    }

    /**
     * @inheritDoc
     */
    public function format(string $formatter): string
    {
        return $this->formatter->format($formatter);
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp(): int
    {
        return floor(($this->interval->j - 2440587.5) * 86400);
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
        return $this->offset;
    }

    /**
     * Returns corresponding date time string with format: 'Y-m-d H:i:s P'
     *
     * @return string
     */
    public function toDateTimeString(): string
    {
        return (new LunarDateTimeToDateTimeString(
            lunar: $this->getGuaranteedLunarDateTime(),
            offset: $this->getOffset()
        ))->getOutput();
    }

    /**
     * Create new Lunar date time instance from Gregorian date time string
     *
     * @param string $datetime
     * @param DateTimeZone|null $timezone
     * @return self
     */
    public static function fromGregorian(
        string $datetime,
        ?DateTimeZone $timezone = null
    ): self {
        if ($datetime !== '' && $datetime !== 'now') {
            $datetime = 'G:' . $datetime;
        }

        return new self($datetime, $timezone);
    }

    /**
     * Create new Lunar date time instance with current time
     *
     * @param DateTimeZone|null $timezone
     * @return self
     */
    public static function now(?DateTimeZone $timezone = null): self
    {
        return self::fromGregorian('now', $timezone);
    }
}
