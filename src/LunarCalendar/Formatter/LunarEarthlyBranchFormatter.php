<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class LunarEarthlyBranchFormatter extends TermFormatter
{
    protected static $terms = [
        0   => ['key' => 'ty',      'label' => 'Tý'     ],
        1   => ['key' => 'suu',     'label' => 'Sửu'    ],
        2   => ['key' => 'dan',     'label' => 'Dần'    ],
        3   => ['key' => 'mao',     'label' => 'Mão'    ],
        4   => ['key' => 'thin',    'label' => 'Thìn'   ],
        5   => ['key' => 'ti',      'label' => 'Tị'     ],
        6   => ['key' => 'ngo',     'label' => 'Ngọ'    ],
        7   => ['key' => 'mui',     'label' => 'Mùi'    ],
        8   => ['key' => 'than',    'label' => 'Thân'   ],
        9   => ['key' => 'dau',     'label' => 'Dậu'    ],
        10  => ['key' => 'tuat',    'label' => 'Tuất'   ],
        11  => ['key' => 'hoi',     'label' => 'Hợi'    ],
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