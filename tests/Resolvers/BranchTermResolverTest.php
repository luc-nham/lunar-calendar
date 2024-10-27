<?php

namespace LucNham\LunarCalendar\Tests\Resolvers;

use LucNham\LunarCalendar\Resolvers\BranchTermResolver;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BranchTermResolver::class)]
#[CoversClass(BranchIdentifier::class)]
class BranchTermResolverTest extends TestCase
{
    public function testResovingSingleTerm()
    {
        $rs = new BranchTermResolver();

        // Use term key to resolve without provide proprty name
        $term = $rs->resolve('ty');
        $this->assertEquals('Ty', $term->name);

        // Use term key to resolve
        $term = $rs->resolve('Ty', 'name');
        $this->assertEquals(0, $term->position);
    }

    public function testResolvingAllTerms()
    {
        $rs = new BranchTermResolver();
        $terms = $rs->resolveAll();

        $this->assertEquals(12, count($terms));

        foreach ($terms as $index => $term) {
            $this->assertEquals($index, $term->position);
        }
    }
}
