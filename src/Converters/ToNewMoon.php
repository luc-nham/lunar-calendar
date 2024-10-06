<?php

namespace LucNham\LunarCalendar\Converters;

/**
 * The class includes methods that help calculate the new moon phase
 */
abstract class ToNewMoon extends Converter
{
    /**
     * Average number of days for the Moon to complete 1 revolution around the Earth (1 cycle)
     */
    public const SYN_MOON = 29.53058868;

    /**
     * Convert degrees to radian
     */
    protected function degtorad(int | float $deg): float
    {
        return ($deg * M_PI) / 180;
    }

    /**
     * The first filter to calculate Julian day numebr of New moon
     */
    protected function meanphase(int | float $jd, int $total): float
    {
        $jt = ($jd - 2415020.5) / 36525;
        $t2 = $jt * $jt;
        $t3 = $t2 * $jt;

        return (
            2415020.75933 +
            self::SYN_MOON * $total +
            0.0001178 * $t2 -
            0.000000155 * $t3 +
            0.00033 * sin($this->degtorad(166.56 + 132.87 * $jt - 0.009173 * $t2))
        );
    }

    /**
     * converts a known total number of new moon cycles into the corresponding Julian day number, with
     * totals starting at 0 since 1900-01-01
     *
     * @param integer $total
     * @return float
     */
    protected function truephase(int $total): float
    {
        $t = $total / 1236.85;
        $t2 = $t * $t;
        $t3 = $t2 * $t;

        $pt =
            2415020.75933 +
            self::SYN_MOON * $total +
            0.0001178 * $t2 -
            0.000000155 * $t3 +
            0.00033 * sin($this->degtorad(166.56 + 132.87 * $t - 0.009173 * $t2));

        $m = 359.2242 + 29.10535608 * $total - 0.0000333 * $t2 - 0.00000347 * $t3;
        $mprime =
            306.0253 + 385.81691806 * $total + 0.0107306 * $t2 + 0.00001236 * $t3;
        $f = 21.2964 + 390.67050646 * $total - 0.0016528 * $t2 - 0.00000239 * $t3;

        $pt +=
            (0.1734 - 0.000393 * $t) * sin($this->degtorad($m)) +
            0.0021 * sin($this->degtorad(2 * $m)) -
            0.4068 * sin($this->degtorad($mprime)) +
            0.0161 * sin($this->degtorad(2 * $mprime)) -
            0.0004 * sin($this->degtorad(3 * $mprime)) +
            0.0104 * sin($this->degtorad(2 * $f)) -
            0.0051 * sin($this->degtorad($m + $mprime)) -
            0.0074 * sin($this->degtorad($m - $mprime)) +
            0.0004 * sin($this->degtorad(2 * $f + $m)) -
            0.0004 * sin($this->degtorad(2 * $f - $m)) -
            0.0006 * sin($this->degtorad(2 * $f + $mprime)) +
            0.001 * sin($this->degtorad(2 * $f - $mprime)) +
            0.0005 * sin($this->degtorad($m + 2 * $mprime));

        return $pt;
    }

    /**
     * Get the total New moon phase corresponds with input Julian day number. The start total begin 
     * 0 at 1900-01-01 UTC
     */
    protected function total(int | float $jd)
    {
        $sdate = $jd;
        $gre = (new JdToGregorian($sdate - 45))->getOutput();

        $k1 = floor(($gre->y + ($gre->m - 1) * (1 / 12) - 1900) * 12.3685);
        $nt1 = $this->meanphase(floor($sdate - 45), $k1);
        $adate = $nt1;

        while (true) {
            $k2 = $k1 + 1;
            $adate += self::SYN_MOON;

            $nt2 = $this->meanphase(floor($adate), $k2);

            if (abs($nt2 - $sdate) < 0.75) {
                $nt2 = $this->truephase($k2);
            }

            if ($nt1 <= $sdate && $nt2 > $sdate) {
                break;
            }

            $nt1 = $nt2;
            $k1 = $k2;
        }

        return $k1;
    }
}
