<?php namespace VanTran\LunarCalendar\Terms;

use VanTran\LunarCalendar\Interfaces\SexagenaryTermInterface;

/**
 * Lớp trừu tượng cho 2 loại đối tượng Can và Chi
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Terms
 */
abstract class AbstractSexagenaryTerm implements SexagenaryTermInterface
{
    public function __construct(private int $index, private string $char)
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
}