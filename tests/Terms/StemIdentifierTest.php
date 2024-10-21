<?php

namespace LucNham\LunarCalendar\Tests\Terms;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use LucNham\LunarCalendar\Terms\StemIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StemIdentifier::class)]
#[CoversClass(SexagenaryTermAttribute::class)]
#[CoversClass(SexagenaryIdentifier::class)]
class StemIdentifierTest extends TestCase
{
    public function testResolvingTerms()
    {
        for ($i = 0; $i < 10; $i++) {
            $term = StemIdentifier::resolve($i);

            $this->assertEquals($i, $term->position);
            $this->assertEquals('S', $term->type);
            $this->assertInstanceOf(StemIdentifier::class, $term);
        }
    }

    public function testResolvingTermNotFound()
    {
        $this->expectExceptionMessage("Stem term with name or key 'BAD_PROP' could not be found");
        StemIdentifier::resolve('BAD_PROP');
    }
}
