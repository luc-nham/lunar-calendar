<?php

namespace LucNham\LunarCalendar\Tests\Resolvers;

use LucNham\LunarCalendar\Resolvers\StemTermResolver;
use LucNham\LunarCalendar\Terms\StemIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StemTermResolver::class)]
#[CoversClass(StemIdentifier::class)]
class StemTermResolverTest extends TestCase
{
    public function testResovingSingleTerm()
    {
        $rs = new StemTermResolver();

        // Use term key to resolve without provide proprty name
        $term = $rs->resolve('giap');
        $this->assertEquals('Giap', $term->name);

        // Use term key to resolve
        $term = $rs->resolve('Giap', 'name');
        $this->assertEquals(0, $term->position);
    }

    public function testResolvingAllTerms()
    {
        $rs = new StemTermResolver();
        $terms = $rs->resolveAll();

        $this->assertEquals(10, count($terms));

        foreach ($terms as $index => $term) {
            $this->assertEquals($index, $term->position);
        }
    }
}
