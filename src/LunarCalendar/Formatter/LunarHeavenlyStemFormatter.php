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
     * Create instance from input key
     *
     * @param string $key
     * @return self
     */
    public static function createFromKey(string $key): self
    {
        foreach(self::$terms as $offset => $attrs) {
            if($attrs['key'] == $key) {
                $h_term = new LunarHeavenlyStemFormatter($offset);

                break;
            }
        }

        if(!isset($h_term)) {
            throw new \Exception("Invalid Heavenly Stem key.");
        }
        
        return $h_term;
    }

    public function __construct(int $offset)
    {
        if($offset < 0 || $offset > 9) {
            throw new \Exception("Error. Heavenly Stems only have offset from 0 to 9. Your offset is $offset.");
        }

        parent::__construct($offset);
        $this->setAttrsByOffset();
    }

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