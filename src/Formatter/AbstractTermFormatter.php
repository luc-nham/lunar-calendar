<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class AbstractTermFormatter
{
    protected int $offset;
    protected string $key;
    protected string $label;

    /**
     * Set offset
     *
     * @param integer $offset
     * @return void
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * Set custom key
     *
     * @param string $key
     * @return self
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Set custom label
     *
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Get offset
     *
     * @return integer
     */
    public function getOffset(): int
    {
        return $this->offset; 
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get display label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}