<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface HasTermInterface
{
    /**
     * Receive a Term with stored key
     *
     * @param string $key
     * @return BaseTerm
     */
    public function getTerm(string $key): TermInterface;
}