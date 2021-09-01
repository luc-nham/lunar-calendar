<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class EarthlyBranchFormatter extends SexagenaryTermFormatter
{
    public const DAY                = 'd';
    public const MONTH              = 'm';
    public const YEAR               = 'y';
    public const HOUR               = 'h';

    /**
     * Earthly branch all keys using Vietnames formatter
     *
     * @var array
     */
    public static $keys = ['ty', 'suu', 'dan', 'mao', 'thin', 'ti', 'ngo', 'mui', 'than', 'dau', 'tuat', 'hoi'];

    /**
     * Earthly branch all label using Vietnames formatter
     *
     * @var array
     */
    public static $labels = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tị', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

    public function __construct(int $offset)
    {
        $key    = parent::_findAttr($offset, self::$keys);
        $label  = parent::_findAttr($offset, self::$labels);

        parent::__construct($offset, $key, $label);
    }
}