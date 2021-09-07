<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface LunarDateTimeStorageInterface
{
    public static function create(): self;

    public function set(string $key, mixed $value): self;

    public function get(string $key): mixed;

    public function has(string $key): bool;
}