<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class TermFormatter implements TermInterface
{
    protected $offset;
    protected $key;
    protected $label;

    public function __construct(int $offset, string $key = '', string $label = '')
    {
        $this->offset   = $offset;
        $this->key      = $key;
        $this->label    = $label;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function setLabel(string $lable): self
    {
        $this->label = $lable;
        return $this;
    }
}