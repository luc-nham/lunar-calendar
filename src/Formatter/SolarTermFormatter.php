<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class SolarTermFormatter extends AbstractTermFormatter
{
    /**
     * 24 Solar Term with key is Sunlongitude dgrees at start new term
     *
     * @var array
     */
    protected static $terms = [
        0       => ['key' => 'xuan_phan',   'label' => 'Xuân Phân'  ],
        15      => ['key' => 'thanh_minh',  'label' => 'Thanh Minh' ],
        30      => ['key' => 'coc_vu',      'label' => 'Cốc Vũ'     ],
        45      => ['key' => 'lap_ha',      'label' => 'Lập Hạ'     ],
        60      => ['key' => 'tieu_man',    'label' => 'Tiểu Mãn'   ],
        75      => ['key' => 'mang_chung',  'label' => 'Mang Chủng' ],
        90      => ['key' => 'ha_chi',      'label' => 'Hạ Chí'     ],
        105     => ['key' => 'tieu_thu',    'label' => 'Tiểu Thử'   ],
        120     => ['key' => 'dai_thu',     'label' => 'Đại Thử'    ],
        135     => ['key' => 'lap_thu',     'label' => 'Lập Thu'    ],
        150     => ['key' => 'xu_thu',      'label' => 'Xử Thử'     ],
        165     => ['key' => 'bach_lo',     'label' => 'Bạch Lộ'    ],
        180     => ['key' => 'thu_phan',    'label' => 'Thu Phân'   ],
        195     => ['key' => 'han_lo',      'label' => 'Hàn Lộ'     ],
        210     => ['key' => 'suong_giang', 'label' => 'Sương Giáng'],
        225     => ['key' => 'lap_dong',    'label' => 'Lập Đông'   ],
        240     => ['key' => 'tieu_tuyet',  'label' => 'Tiểu Tuyết' ],
        255     => ['key' => 'dai_tuyet',   'label' => 'Đại Tuyết'  ],
        270     => ['key' => 'dong_chi',    'label' => 'Đông Chí'   ],
        285     => ['key' => 'tieu_han',    'label' => 'Tiểu Hàn'   ],
        300     => ['key' => 'dai_han',     'label' => 'Đại Hàn'    ],
        315     => ['key' => 'lap_xuan',    'label' => 'Lập Xuân'   ],
        330     => ['key' => 'vu_thuy',     'label' => 'Vũ Thủy'    ],
        345     => ['key' => 'kinh_trap',   'label' => 'Kinh Trập'  ],
    ];

    // Single Term attribute
    protected $sunlongitude;

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

    public function create(float $sunlongitude): self
    {
        if($sunlongitude >= 360 || $sunlongitude < 0) {
            throw new \Exception("Error. Degrees of Sunlongitude must be in range 0 - 359.xxxx");
        }

        $offset = 0;

        foreach(self::$terms as $slBegin => $attrs) {
            if($slBegin + 15 > $sunlongitude) {
                
                $this->setOffset($offset);
                $this->setSunlongitude($slBegin);
                $this->setKey($attrs['key']);
                $this->setLabel($attrs['label']);

                break;
            }

            ++$offset;
        }

        return $this;
    }

    public function setSunlongitude(float $sunlongitude): void
    {
        $this->sunlongitude = $sunlongitude;
    }

    public function getSunlongitude(): float
    {
        return $this->sunlongitude;
    }
}