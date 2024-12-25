<?php

namespace LucNham\LunarCalendar\Tests;

use DateInterval;
use DateTime;
use LucNham\LunarCalendar\SolarTerm;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class Issues89Test extends TestCase
{
    /**
     * @param integer $year
     * @return array{term:SolarTerm,date:DateTime}
     */
    protected function getTerm(int $year): array
    {
        $date = new DateTime($year . '-03-10 23:59:59 Asia/Ho_Chi_Minh');
        $term = SolarTerm::fromDate($date);

        if ($term->angle > 345) {
            $date->add(new DateInterval('P1D'));
            $term = SolarTerm::fromDate($date);
        }

        return [
            'term' => $term,
            'date' => $date,
        ];
    }

    /**
     * Fixed issuse
     *
     * @return void
     */
    public function test_fixed()
    {
        for ($y = 1900; $y < 2099; $y++) {
            $data = $this->getTerm($y);
            $term = $data['term'];
            $date = $data['date'];
            $begin = (new DateTime('now', $date->getTimezone()))->setTimestamp($term->begin);
            $next = $term->next();
            $prev = $term->previous();
            $secs = 17 * 86400;

            $this->assertEquals($date->format('Y'), $begin->format('Y'));
            $this->assertLessThan($secs, $next->begin - $term->begin);
            $this->assertLessThan($secs, $term->begin - $prev->begin);
        }
    }
}
