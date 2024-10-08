<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use Exception;
use LucNham\LunarCalendar\Converters\Converter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(Converter::class)]
class ConverterTest extends TestCase
{
    private Converter $converter;

    public function setup(): void
    {
        $c = new class extends Converter
        {
            public function getOutput(): float
            {
                return $this->toFixed(1.123456789);
            }
        };

        $this->converter = $c;
    }

    public function testOffsetOption()
    {
        $this->assertEquals(0, $this->converter->offset());

        // Set new offset option
        $this->converter->setOffset(25200);

        $this->assertEquals(25200, $this->converter->offset());
    }

    public function testFixedOption()
    {
        // Default is fixed 7 number of decimal part
        $this->assertEquals(1.1234568, $this->converter->getOutput());

        // Set new fixed option
        $this->converter->setFixed(1);
        $this->assertEquals(1.1, $this->converter->getOutput());
    }

    public function testForwardOuput()
    {
        $this->converter->forward(function ($o) {
            $this->assertEquals(2.1234568, $o + 1);
        });
    }

    public function testChaning()
    {
        $output = $this->converter->then(T::class)->getOutput();
        $this->assertEquals(1.1234568, $output);

        $this->expectException(RuntimeException::class);
        $this->converter->then('Wrong class name');
    }

    public function testChaningWithAdditionParameters()
    {
        $output = $this->converter
            ->then(T::class)
            ->then(T2::class, 1)
            ->getOutput();

        $this->assertEquals(2.1234568, $output);

        $output = $this->converter
            ->then(T::class)
            ->then(T2::class, ...[1])
            ->then(T3::class, 1, 2)
            ->getOutput();

        $this->assertEquals(5.1234568, $output);

        $output = $this->converter
            ->then(T::class)
            ->then(T2::class, ...[1])
            ->then(T3::class, ...[0, 3])
            ->getOutput();

        $this->assertEquals(5.1234568, $output);

        $this->expectException(Exception::class);
        $this->converter->then(T2::class);
    }
}

/**
 * A simple testing converter with single parameter
 */
class T extends Converter
{
    function __construct(private float $num) {}

    public function getOutput()
    {
        return $this->num;
    }
}

/**
 * Testing converter with two input parameters
 */
class T2 extends Converter
{
    public function __construct(private float $num, private float $num2) {}

    public function getOutput()
    {
        return $this->num + $this->num2;
    }
}

/**
 * Testing converter with three input parameters
 */
class T3 extends Converter
{
    public function __construct(private float $num, private float $num2, private float $num3) {}

    public function getOutput()
    {
        return $this->num + $this->num2 + $this->num3;
    }
}
