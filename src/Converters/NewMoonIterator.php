<?php

namespace LucNham\LunarCalendar\Converters;

use Iterator;
use LucNham\LunarCalendar\Terms\NewMoonPhase;

/**
 * An Iterator helps navigate between new Moon phases
 */
class NewMoonIterator extends ToNewMoon implements Iterator
{
    private $key = 0;

    /**
     * Create new Iterator
     *
     * @param NewMoonPhase $nm  New moon phase used as start element.
     * @param integer $offset   UTC offset.
     * @param boolean $reverse  If false (default), navigate to next New moon phases, otherwise,
     *                          navigate to previous New moon phase.
     */
    public function __construct(private NewMoonPhase $nm, int $offset = 0, private bool $reverse = false)
    {
        $this->setOffset($offset);
    }

    /**
     * Set the key value, allow point to specific New moon phase
     *
     * @param integer $key
     * @return void
     */
    public function setKey(int $key): void
    {
        $this->key = abs($key);
    }

    /**
     * Return current new moon phase. Because the class implements Iterator interface, 'current' 
     * method can be used instead, they return same output.
     *
     * @return NewMoonPhase
     */
    public function getOutput(): NewMoonPhase
    {
        if ($this->key() === 0) {
            return $this->nm;
        }

        $total = $this->reverse
            ? $this->nm->total - $this->key()
            : $this->nm->total + $this->key();

        $jd = $this->truephase($total);

        return new NewMoonPhase(
            $total,
            (new JdToMidnightJd($jd, $this->offset()))->getOutput()
        );
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->key += 1;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->key = 0;
    }

    /**
     * Return the current New moon phase
     *
     * @return NewMoonPhase
     */
    public function current(): NewMoonPhase
    {
        return $this->getOutput();
    }

    /**
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->key() >= 0;
    }
}
