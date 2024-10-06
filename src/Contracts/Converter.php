<?php

namespace LucNham\LunarCalendar\Contracts;

use Closure;

/**
 * Definition for converters
 */
interface Converter
{
    /**
     * Return the ouput value
     */
    public function getOutput();

    /**
     * Forward the output to a callback function
     * 
     * @param $cb (($output): mixed)
     * @return mixed
     */
    public function forward(Closure $cb);
}
