<?php

namespace LucNham\LunarCalendar\Formatters;

use LucNham\LunarCalendar\Contracts\SexagenaryFormattable;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;

/**
 * Default formattter for Sexagenary milestones, suit for human readable.
 */
class SexagenaryDefaultFormatter implements SexagenaryFormattable
{
    /**
     * Replace format characters and return formatted string
     *
     * @param string $formatter             Input formatter
     * @param SexagenaryMilestone $terms    Sexagenary milestone
     * @return string                       Formatted string
     */
    protected function replace(string $formatter, SexagenaryMilestone $terms): string
    {
        preg_match_all("/\[([^\]]*)\]/", $formatter, $matches);

        if (!isset($matches[1]) || empty($matches[1])) {
            return $formatter;
        }

        $target = $matches[0];
        $replacement = $matches[1];
        $filtered1 = [];
        $filtered2 = [];

        foreach ($replacement as $string) {
            $characters = str_split($string);

            $filtered1[] = $characters;
            $filtered2[] = $characters;
        }

        foreach ($filtered1 as $i => $chars) {
            foreach ($chars as $k => $char) {
                if (property_exists($terms, $char)) {
                    $filtered2[$i][$k] = $terms->{$char}->name;
                }

                if ($char === '+' && $k > 0) {
                    $prevChar = $chars[$k - 1];

                    if (ctype_upper($prevChar) && property_exists($terms, $prevChar)) {
                        $prevChar = strtolower($prevChar);
                        $filtered2[$i][$k] = ' ' . $terms->{$prevChar}->name;
                    }
                }
            }
        }

        foreach ($filtered2 as $k => $v) {
            $replacement[$k] = implode("", $v);
        }

        return str_replace($target, $replacement, $formatter);
    }

    /**
     * @inheritDoc
     */
    public function format(string $formatter, SexagenaryMilestone $terms): string
    {
        if (strlen($formatter) === 1) {
            return property_exists($terms, $formatter) ? $terms->{$formatter}->name : $formatter;
        }

        return $this->replace($formatter, $terms);
    }
}
