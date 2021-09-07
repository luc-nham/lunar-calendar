<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface TermInterface
{
    /**
     * Get Term's offset
     *
     * @return integer
     */
    public function getOffset(): int;

    /**
     * Get Term's key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get Term's label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Allow to custom key
     *
     * @param string $key
     * @return self
     */
    public function setKey(string $key): self;

    /**
     * Allow to custom label
     *
     * @param string $lable
     * @return self
     */
    public function setLabel(string $lable): self;
}