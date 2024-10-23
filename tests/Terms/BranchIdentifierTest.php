<?php

namespace LucNham\LunarCalendar\Tests\Terms;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BranchIdentifier::class)]
#[CoversClass(SexagenaryTermAttribute::class)]
#[CoversClass(SexagenaryIdentifier::class)]
class BranchIdentifierTest extends TestCase
{
    public function testResolvingTerms()
    {
        for ($i = 0; $i < 12; $i++) {
            $term = BranchIdentifier::resolve($i);

            $this->assertEquals($i, $term->position);
            $this->assertEquals('B', $term->type);
            $this->assertInstanceOf(BranchIdentifier::class, $term);
        }
    }

    public function testResolvingTermNotFound()
    {
        $this->expectExceptionMessage("Branch term with name or key 'BAD_PROP' could not be found");
        BranchIdentifier::resolve('BAD_PROP');
    }
}
