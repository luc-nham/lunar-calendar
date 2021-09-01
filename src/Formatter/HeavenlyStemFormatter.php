<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class HeavenlyStemFormatter extends SexagenaryTermFormatter
{
    /**
     * Hevenly Stem all keys using Vietnames formatter
     *
     * @var array
     */
    public static $keys = ['giap', 'at', 'binh', 'dinh', 'mau', 'ky', 'canh', 'tan', 'nham', 'quy'];

    /**
     * Hevenly Stem all label using Vietnames formatter
     *
     * @var array
     */
    public static $labels = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];

    public function __construct(int $offset)
    {
        $key    = parent::_findAttr($offset, self::$keys);
        $label  = parent::_findAttr($offset, self::$labels);

        parent::__construct($offset, $key, $label);
    }
}