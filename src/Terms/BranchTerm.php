<?php namespace VanTran\LunarCalendar\Terms;

/**
 * Lớp xác định 1 đối tượng địa chi
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Terms
 */
class BranchTerm extends AbstractTerm
{
    /**
     * @var string Phân loại thiên Can
     */
    private $type = 'branch';

    public function getType(): int|string 
    { 
        return $this->type;
    }
}