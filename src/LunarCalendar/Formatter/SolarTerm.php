<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class SolarTerm extends BaseTerm
{
    /**
     * 24 Solar Term with key is Sunlongitude dgrees at start new term
     *
     * @var array
     */
    protected static $terms = [
        0       => ['key' => 'xuan_phan',   'label' => 'Xuân Phân'  ],
        1       => ['key' => 'thanh_minh',  'label' => 'Thanh Minh' ],
        2       => ['key' => 'coc_vu',      'label' => 'Cốc Vũ'     ],
        3       => ['key' => 'lap_ha',      'label' => 'Lập Hạ'     ],
        4       => ['key' => 'tieu_man',    'label' => 'Tiểu Mãn'   ],
        5       => ['key' => 'mang_chung',  'label' => 'Mang Chủng' ],
        6       => ['key' => 'ha_chi',      'label' => 'Hạ Chí'     ],
        7       => ['key' => 'tieu_thu',    'label' => 'Tiểu Thử'   ],
        8       => ['key' => 'dai_thu',     'label' => 'Đại Thử'    ],
        9       => ['key' => 'lap_thu',     'label' => 'Lập Thu'    ],
        10      => ['key' => 'xu_thu',      'label' => 'Xử Thử'     ],
        11      => ['key' => 'bach_lo',     'label' => 'Bạch Lộ'    ],
        12      => ['key' => 'thu_phan',    'label' => 'Thu Phân'   ],
        13      => ['key' => 'han_lo',      'label' => 'Hàn Lộ'     ],
        14      => ['key' => 'suong_giang', 'label' => 'Sương Giáng'],
        15      => ['key' => 'lap_dong',    'label' => 'Lập Đông'   ],
        16      => ['key' => 'tieu_tuyet',  'label' => 'Tiểu Tuyết' ],
        17      => ['key' => 'dai_tuyet',   'label' => 'Đại Tuyết'  ],
        18      => ['key' => 'dong_chi',    'label' => 'Đông Chí'   ],
        19      => ['key' => 'tieu_han',    'label' => 'Tiểu Hàn'   ],
        20      => ['key' => 'dai_han',     'label' => 'Đại Hàn'    ],
        21      => ['key' => 'lap_xuan',    'label' => 'Lập Xuân'   ],
        22      => ['key' => 'vu_thuy',     'label' => 'Vũ Thủy'    ],
        23      => ['key' => 'kinh_trap',   'label' => 'Kinh Trập'  ],
    ];

    private static $allow_keys = ['key', 'label'];

    /**
     * Allow modifiy terms
     *
     * @param array $terms
     * @return void
     */
    public static function customTerms(array $terms): void
    {
        foreach($terms as $sunlongitude => $attrs) {
            if(!isset(self::$terms[$sunlongitude])) {
                continue;
            }

            foreach($attrs as $key => $value) {
                self::$terms[$sunlongitude][$key] = $value;
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