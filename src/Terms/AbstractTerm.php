<?php namespace VanTran\LunarCalendar\Terms;

use VanTran\LunarCalendar\Interfaces\TermInterface;

/**
 * Lớp trừu tượng cho các loại đối tượng Can, Chi và Tiết khí
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Terms
 */
abstract class AbstractTerm implements TermInterface
{
    public function __construct(private int $index, private string $char, private string $label)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getCharacter(): string
    {
        return $this->char;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}