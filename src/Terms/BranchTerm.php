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

    /**
     * {@inheritdoc}
     */
    public function getKey(): string 
    { 
        $map = ['ty', 'suu', 'dan', 'mao', 'thin', 'ti', 'ngo', 'mui', 'than', 'dau', 'tuat', 'hoi'];
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