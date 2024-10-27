<?php

namespace LucNham\LunarCalendar\Tests\Resolvers;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Resolvers\SolarTermResolver;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use LucNham\LunarCalendar\Terms\VnSolarTermIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SolarTermResolver::class)]
#[CoversClass(SolarTermIdentifier::class)]
#[CoversClass(VnSolarTermIdentifier::class)]
class SolarTermResolverTest extends TestCase
{
    public function testResovingSingleTerm()
    {
        $rs = new SolarTermResolver();

        // Use term key to resolve without provide proprty name
        $term = $rs->resolve('begin_spring');
        $this->assertEquals('Beginning of Spring', $term->name);

        // Use term key to resolve
        $term = $rs->resolve('Beginning of Spring', 'name');
        $this->assertEquals(0, $term->position);

        // Use term ls to resolve without provide proprty name
        $term = $rs->resolve(315.0);
        $this->assertEquals('Beginning of Spring', $term->name);
    }

    public function testResolvingAllTerms()
    {
        $rs = new SolarTermResolver();
        $terms = $rs->resolveAll();

        $this->assertEquals(24, count($terms));

        foreach ($terms as $index => $term) {
            $this->assertEquals($index, $term->position);
        }
    }

    public function testMissingAttachedAttributeException()
    {
        $rs = (new class() extends SolarTermResolver {
            public function getTargetAttributeClass(): string
            {
                return SexagenaryTermAttribute::class;
            }
        });

        $this->expectExceptionMessage("Missing data definitions of target term");
        $rs->resolve(0);
    }

    public function testFailureResolvingTermException()
    {
        $this->expectExceptionMessage("Can't resolve target term");
        (new SolarTermResolver())->resolve('bad_key_value', 'key');
    }

    public function testChangeTargetClass()
    {
        $rs = new SolarTermResolver();
        $rs->setTargetTermClass(VnSolarTermIdentifier::class);

        $term = $rs->resolve(0);

        $this->assertEquals('Lập Xuân', $term->name);
        $this->assertEquals('lap_xuan', $term->key);
        $this->assertEquals(315.0, $term->ls);
    }
}
