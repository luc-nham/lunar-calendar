<?php declare(strict_types=1);

namespace LunarCalendar;

use DateTimeZone;
use LunarCalendar\Converter\SexagenaryToDateTime;
use LunarCalendar\Formatter\EarthlyBranchTerm;
use LunarCalendar\Formatter\HeavenlyStemTerm;

class LunarSexagenaryMatching extends SexagenaryToDateTime
{
    /**
     * Quick create instance from foreseen Sexagenaries term key
     *
     * @param string $h_day         giap, at, binh, dinh,.. nham, quy
     * @param string $e_day         ty, suu, dan, mao, .. tuat, hoi
     * @param string $e_month       ty, suu, dan, mao, .. tuat, hoi
     * @param string $e_year        ty, suu, dan, mao, .. tuat, hoi
     * @param string|null $e_hour   ty, suu, dan, mao, .. tuat, hoi
     * @return self
     */
    public static function createFromTermKeys(string $h_day, string $e_day, string $e_month, string $e_year, ?string $e_hour = null): self
    {
        if(!$e_hour) {
            $e_hour = 'ty';
        }

        return new self(
            HeavenlyStemTerm::createFromKey($h_day),
            EarthlyBranchTerm::createFromKey($e_day),
            EarthlyBranchTerm::createFromKey($e_month),
            EarthlyBranchTerm::createFromKey($e_year),
            EarthlyBranchTerm::createFromKey($e_hour),
        );
    }

    /**
     * Quick create instance from foreseen Sexagenaries term offset
     *
     * @param integer $h_day    0 as 'giap', 1 as 'at'... 9 as 'quy'
     * @param integer $e_day    0 as 'ty', 1 as 'suu'... 11 as 'hoi'
     * @param integer $e_month  0 as 'ty', 1 as 'suu'... 11 as 'hoi'
     * @param integer $e_year   0 as 'ty', 1 as 'suu'... 11 as 'hoi'
     * @param integer $e_hour   0 as 'ty', 1 as 'suu'... 11 as 'hoi'
     * @return void
     */
    public static function createFromTermOffset(int $h_day, int $e_day, int $e_month, int $e_year, int $e_hour = 0)
    {
        return new self(
            new HeavenlyStemTerm($h_day),
            new EarthlyBranchTerm($e_day),
            new EarthlyBranchTerm($e_month),
            new EarthlyBranchTerm($e_year),
            new EarthlyBranchTerm($e_hour),
        );
    }

    /**
     * Return output by an instance of LunarDateTime object
     *
     * @param DateTimeZone|null $dateTimeZone
     * @return LunarDateTime|null
     */
    public function getOutputLunarDateTime(?DateTimeZone $dateTimeZone = null): ?LunarDateTime
    {
        if(!$this->isMatched()) {
            return null;
        }

        return new LunarDateTime($this->output(), $dateTimeZone);
    }

    /**
     * Return output by an instance of LunarSexagenary object
     *
     * @param DateTimeZone|null $dateTimeZone
     * @return LunarSexagenary|null
     */
    public function getOutputLunarSexagenary(?DateTimeZone $dateTimeZone = null): ?LunarSexagenary
    {
        if(!$this->isMatched()) {
            return null;
        }

        return new LunarSexagenary($this->output(), $dateTimeZone);
    }
}