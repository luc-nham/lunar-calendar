<?php

namespace LucNham\LunarCalendar\Terms;

use Exception;
use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use ReflectionClass;

/**
 * Stores basic identification information of a Sexagenary terms
 */
readonly abstract class SexagenaryIdentifier
{
    /**
     * A string or character to classify term
     *
     * @var string
     */
    public string $type;

    /**
     * Create Stem
     * 
     * @param string $key   Key of term
     * @param string $name  Display name
     * @param int $order    Position of the term in term group
     */
    public function __construct(
        public string $key,
        public string $name,
        public int $position,
    ) {
        $this->type = $this->registerType();
    }

    /**
     * Register the term type
     *
     * @return string
     */
    protected abstract function registerType(): string;

    /**
     * Returns the Stem term identification
     * 
     * @param string|int $term The term key, name or position
     * @param string $target Target class name
     * @return SexagenaryIdentifier
     */
    public static function resolve(
        string | int $term,
        string $target,
    ): SexagenaryIdentifier {
        try {
            $class = new ReflectionClass($target);
            $attributes = $class->getAttributes(SexagenaryTermAttribute::class);

            foreach ($attributes as $att) {
                $instance = $att->newInstance();

                if (
                    $instance->key === $term ||
                    $instance->name === $term ||
                    $instance->position === $term
                ) {
                    return new $target(...(array)$instance);
                }
            }

            throw new Exception("The Term could not be found");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
