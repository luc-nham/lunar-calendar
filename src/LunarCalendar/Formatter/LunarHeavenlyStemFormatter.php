<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class LunarHeavenlyStemFormatter extends TermFormatter
{
    protected static $terms = [
        0   => ['key' => 'giap',    'label' => 'Giáp'   ],
        1   => ['key' => 'at',      'label' => 'Ất'     ],
        2   => ['key' => 'binh',    'label' => 'Bính'   ],
        3   => ['key' => 'dinh',    'label' => 'Đinh'   ],
        4   => ['key' => 'mau',     'label' => 'Mậu'    ],
        5   => ['key' => 'ky',      'label' => 'Kỷ'     ],
        6   => ['key' => 'canh',    'label' => 'Canh'   ],
        7   => ['key' => 'tan',     'label' => 'Tân'    ],
        8   => ['key' => 'nham',    'label' => 'Nhâm'   ],
        9   => ['key' => 'quy',     'label' => 'Quý'    ],
    ];

    protected static $allow_keys = ['key', 'label'];

    /**
     * Allow to custom Term's label, key and other additional attributes
     *
     * @param array $terms
     * @return void
     */
    public static function customTerms(array $terms): void
    {
        foreach($terms as $offset => $attrs) {
            if(isset(self::$terms[$offset])) {
                self::$terms[$offset] = array_merge(self::$terms[$offset], $attrs);
            }
        }
    }

    /**
     * Set attributes directly by offset
     *
     * @return self
     */
    public function setAttrsByOffset(): self
    {
        if(isset(self::$terms[$this->offset])) {
            foreach(self::$terms[$this->offset] as $name => $attr) {
                if(in_array($name, self::$allow_keys)) {
                    $this->{$name} = $attr;
                }
            }
        }

        return $this;
    }
}