<?php

namespace LucNham\LunarCalendar\Resolvers;

use Exception;
use LucNham\LunarCalendar\Contracts\TermResolver;
use ReflectionClass;

/**
 * To resolve diffirent term types such as Solar terms, Stem terms, Branch terms,...
 */
abstract class BaseTermResolver implements TermResolver
{
    /**
     * Target term class name
     *
     * @var string
     */
    private string $target;

    /**
     * Target term attribute class name
     *
     * @var string
     */
    private string $attribute;

    /**
     * Returns target term class
     *
     * @return string
     */
    abstract public function getTargetTermClass(): string;

    /**
     * Returns target term attribute class
     *
     * @return string
     */
    abstract public function getTargetAttributeClass(): string;

    /**
     * Create new resolver
     */
    public function __construct()
    {
        $this->target = $this->getTargetTermClass();
        $this->attribute = $this->getTargetAttributeClass();
    }

    /**
     * Change target term class
     *
     * @param string $class
     * @return self
     */
    public function setTargetTermClass(string $class): self
    {
        $this->target = $class;
        return $this;
    }

    /**
     * Create terms if possible
     *
     * @param mixed $value      Value of term attribute
     * @param string|null $name Name of term attribute
     * @return array            Aray of resolved terms
     */
    protected function createTerms(mixed $value = null, ?string $name = null): array
    {
        $terms = [];

        try {
            $class = new ReflectionClass($this->target);
            $attributes = $class->getAttributes($this->attribute);

            if (count($attributes) <= 0) {
                throw new Exception("Missing data definitions of target term");
            }

            foreach ($attributes as $att) {
                $args = $att->getArguments();

                if ($value === null && $name === null) {
                    array_push($terms, new $this->target(...$args));
                } else {
                    if ($name) {
                        if (isset($args[$name]) && $args[$name] === $value) {
                            array_push($terms, new $this->target(...$args));
                            break;
                        }
                    } else {
                        foreach ($args as $val) {
                            if ($val === $value) {
                                array_push($terms, new $this->target(...$args));
                                break;
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $terms;
    }

    /**
     * @inheritDoc
     */
    public function resolve(mixed $value, ?string $name = null)
    {
        $terms = $this->createTerms($value, $name);

        if (count($terms) === 0) {
            throw new Exception("Can't resolve target term");
        }

        return $terms[0];
    }

    /**
     * @inheritDoc
     */
    public function resolveAll(): array
    {
        return $this->createTerms(null, null);
    }
}
