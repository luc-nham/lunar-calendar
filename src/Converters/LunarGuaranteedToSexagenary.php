<?php

namespace LucNham\LunarCalendar\Converters;

use InvalidArgumentException;
use LucNham\LunarCalendar\Contracts\TermResolver;
use LucNham\LunarCalendar\Resolvers\BranchTermResolver;
use LucNham\LunarCalendar\Resolvers\StemTermResolver;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;

/**
 * Convert a guaranteed Lunar date time to Sexagenary milestone
 */
class LunarGuaranteedToSexagenary extends Converter
{
    /**
     * Fake Julian day number to format terms
     *
     * @var integer
     */
    private int $jd;

    public function __construct(
        private LunarDateTimeGuaranteed $lunar,
        int $offset = 0,
        private TermResolver $stemResolver = new StemTermResolver(),
        private TermResolver $branchResolver = new BranchTermResolver()
    ) {
        $this->setOffset($offset);
    }

    protected function getFakeLocalJd(float $jd, int $offset): int
    {
        $j = (new JdToMidnightJd($jd, $offset))->getOutput();
        $j += $this->toFixed($offset / 86400);
        $j += 0.5;

        return $j;
    }

    /**
     * Returns position of first hour stem
     *
     * @return integer
     */
    protected function N(): int
    {
        $D = $this->getPosition('D');
        $N = $compare = 0;

        while ($compare !== $D) {
            $compare = ($compare + 1) % 10;
            $N = ($N + 2) % 10;
        }

        return $N;
    }

    /**
     * Returns hour stem position equal hour branch
     *
     * @return integer
     */
    protected function H(): int
    {
        $H = 23;
        $N = $this->getPosition('N');
        $currentHour = $this->lunar->h;

        while ($H != $currentHour) {
            $H = ($H + 1) % 24;

            if ($H % 2 != 0) {
                $N = ($N + 1) % 10;
            }
        }

        return $N;
    }

    /**
     * Return hour branch position
     *
     * @return integer
     */
    protected function _h(): int
    {
        $com = 23;
        $h = 0;
        $currentHour = $this->lunar->h;

        while ($com != $currentHour) {
            $com = ($com + 1) % 24;

            if ($com % 2 != 0) {
                $h = ($h + 1) % 12;
            }
        }

        return $h;
    }

    /**
     * Returns week branch position
     *
     * @return void
     */
    protected function w()
    {
        $val = $this->getPosition('d') - $this->getPosition('D');

        return $val < 0 ? $val + 12 : $val;
    }

    /**
     * Returns term positions
     *
     * @param string $char
     * @return integer
     */
    protected function getPosition(string $char): int
    {
        return match ($char) {
            'D' => ($this->jd + 9) % 10,
            'M' => ($this->lunar->y * 12 + $this->lunar->m + 3) % 10,
            'Y' => ($this->lunar->y + 6) % 10,
            'H' => $this->H(),
            'N' => $this->N(),
            'W' => 0,
            'd' => ($this->jd + 1) % 12,
            'm' => ($this->lunar->m + 1) % 12,
            'y' => ($this->lunar->y + 8) % 12,
            'h' => $this->_h(),
            'w' => $this->w(),

            default => throw new InvalidArgumentException("Bad format '{$char}' character")
        };
    }

    /**
     * Returns a Sexagenary milestone corresponding to Lunar guaranteed
     *
     * @return SexagenaryMilestone
     */
    public function getOutput(): SexagenaryMilestone
    {
        if ($this->lunar->h === 23) {
            $this->lunar = (new JdToLunarDateTime($this->lunar->j + 0.0416667, $this->offset()))
                ->getGuaranteedLunarDateTime();
        }

        $this->jd = $this->getFakeLocalJd($this->lunar->j, $this->offset());

        $S = $this->stemResolver;
        $B = $this->branchResolver;

        return new SexagenaryMilestone(
            D: $S->resolve($this->getPosition('D'), 'position'),
            M: $S->resolve($this->getPosition('M'), 'position'),
            Y: $S->resolve($this->getPosition('Y'), 'position'),
            H: $S->resolve($this->getPosition('H'), 'position'),
            W: $S->resolve($this->getPosition('W'), 'position'),
            N: $S->resolve($this->getPosition('N'), 'position'),
            d: $B->resolve($this->getPosition('d'), 'position'),
            m: $B->resolve($this->getPosition('m'), 'position'),
            y: $B->resolve($this->getPosition('y'), 'position'),
            h: $B->resolve($this->getPosition('h'), 'position'),
            w: $B->resolve($this->getPosition('w'), 'position'),
        );
    }
}
