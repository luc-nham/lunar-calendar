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

    /**
     * The method helps chain converters with a more concise syntax. The output of the previous 
     * converter will be passed as the input parameter of the next converter through the constructor 
     * method. The global configuration values ​​will also be forwarded to the next converter. This 
     * helps ensure uniformity of output results.
     * 
     * When using this chaining method, it is important to know the type of output of the previous 
     * converter, and the type of input parameters for the next converter.
     *
     * @param string $c         Next Converter class name to handles ouput value
     * @param mixed ...$params  Addition parameters for next converter via it's constructor
     * @return Converter
     */
    public function then(string $c, mixed ...$params): Converter;
}
