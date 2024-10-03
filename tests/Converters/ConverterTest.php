<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\Converter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Converter::class)]
class ConverterTest extends TestCase
{
    private Converter $converter;

    public function setup(): void
    {
        $c = new class extends Converter
        {
            public function getOuput(): float
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
        $this->assertEquals(1.1234568, $this->converter->getOuput());

        // Set new fixed option
        $this->converter->setFixed(1);
        $this->assertEquals(1.1, $this->converter->getOuput());
    }

    public function testForwartOuput()
    {
        $this->converter->forward(function ($o) {
            $this->assertEquals(2.1234568, $o + 1);
        });
    }
}
