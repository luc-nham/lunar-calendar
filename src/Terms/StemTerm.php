<?php namespace VanTran\LunarCalendar\Terms;

/**
 * Lớp xác định một đối tượng thiên Can
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Terms
 */
class StemTerm extends AbstractTerm
{
    /**
     * @var string Phân loại thiên Can
     */
    private $type = 'stem';

    /**
     * {@inheritdoc}
     */
    public function getKey(): string 
    { 
        $map = ['giap', 'at', 'binh', 'dinh', 'mau', 'ky', 'canh', 'tan', 'nham', 'quy'];
        return $map[$this->getIndex()];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): int|string 
    { 
        return $this->type;
    }
}