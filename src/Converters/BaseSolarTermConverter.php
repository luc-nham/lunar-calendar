<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\SolarTermInterface;
use VanTran\LunarCalendar\Traits\SunlongitudeMutable;

/**
 * Bộ chuyển đổi cơ bản cho hệ thống Tiết khí
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class BaseSolarTermConverter extends BaseSunlongitudeConverter implements SolarTermInterface
{
    use SunlongitudeMutable;

    /**
     * {@inheritdoc}
     */
    public function getKey(): string 
    { 
        $map = [
            'xuan_phan',
            'thanh_minh',
            'coc_vu',
            'lap_ha',
            'tieu_man',
            'mang_chung',
            'ha_chi',
            'tieu_thu',
            'dai_thu',
            'lap_thu',
            'xu_thu',
            'bach_lo',
            'thu_phan',
            'han_lo',
            'suong_giang',
            'lap_dong',
            'tieu_tuyet',
            'dai_tuyet',
            'dong_chi',
            'tieu_han',
            'dai_han',
            'lap_xuan',
            'vu_thuy',
            'kinh_trap'
        ];

        return $map[$this->getIndex()];
    }

    /**
     * Nhãn hệ thống Tiết khí mặc định
     * @return array 
     */
    protected function getDefaultLabels(): array
    {
        return [
            'Xuân Phân',
            'Thanh Minh',
            'Cốc Vũ',
            'Lập Hạ',
            'Tiểu Mãn',
            'Mang Chủng',
            'Hạ Chí',
            'Tiểu Thử',
            'Đại Thử',
            'Lập Thu',
            'Xử Thử',
            'Bạch Lộ',
            'Thu Phân',
            'Hàn Lộ',
            'Sương Giáng',
            'Lập Đông',
            'Tiểu Tuyết',
            'Đại Tuyết',
            'Đông Chí',
            'Tiểu Hàn',
            'Đại Hàn',
            'Lập Xuân',
            'Vũ Thủy',
            'Kinh Trập'
        ];
    }

    /**
     * Trả về số đại diện cho Tiết hoặc Khí
     * @return int 
     */
    public function getIndex(): int 
    { 
        return (int)floor($this->getDegrees() / 15);
    }

    /**
     * Trả về ký tự đại diện cho Tiết hoặc Khí
     * @return string 
     */
    public function getCharacter(): string 
    { 
        $chars = range('a', 'x');
        return $chars[$this->getIndex()];
    }

    /**
     * Trả về ký tự đại diện phân loại cho Tiết (J) hoặc Khí (Z)
     * @return int|string 
     */
    public function getType(): int|string 
    { 
        return ($this->getIndex() % 2 === 0)
            ? 'Z'
            : 'J';
    }

    /**
     * Trả về nhãn/tên tiết khí
     * 
     * @return int|string 
     */
    public function getLabel(): string
    {
        $labels = $this->getDefaultLabels();
        $index = $this->getIndex();

        return $labels[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function begin(): SolarTermInterface 
    { 
        $beginDeg = $this->getIndex() * 15;
        $diff = $this->getDegrees(true) - $beginDeg;

        if ($diff === 0) {
            return $this;
        }
        
        return $this->subtract($diff);
    }

    /**
     * {@inheritdoc}
     */
    public function next(int $term = 1): SolarTermInterface 
    { 
        return $this->begin()->add($term * 15);
    }

    /**
     * {@inheritdoc}
     */
    public function previuos(int $term = 1): SolarTermInterface 
    { 
        return $this->begin()->subtract($term * 15);
    }
}
