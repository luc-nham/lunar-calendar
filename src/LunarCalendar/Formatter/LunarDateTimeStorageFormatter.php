<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class LunarDateTimeStorageFormatter implements LunarDateTimeStorageInterface
{
    protected $datetime = [
        // 'd'     => 1,
        // 'm'     => 1,
        // 'y'     => 1990,
        // 'h'     => 0,
        // 'i'     => 0,
        // 's'     => 0,
        // 'l'     => 0,
        // 'j'     => 0,
        // 'o'     => 0
    ];

    public static function create(): self
    {
        return new self;
    }

    public function has(string $key): bool
    {
        return (isset($this->datetime[$key]))
                    ? true
                    : false;
    }

    public function get(string $key): mixed
    {
        return ($this->has($key))
                    ? $this->datetime[$key]
                    : throw new \Exception("Try to get value does not exist with key '$key'");
                    
    }

    public function set(string $key, mixed $value): self
    {
        $this->datetime[$key] = $value;
        return $this;
    }
}