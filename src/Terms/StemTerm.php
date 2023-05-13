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

    public function getType(): int|string 
    { 
        return $this->type;
    }
}