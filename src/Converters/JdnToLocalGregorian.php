<?php namespace VanTran\LunarCalendar\Converters;

use DateTimeZone;
use Exception;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Bộ chuyển đổi mốc ngày Julian (JDN) thành lịch Gregorian theo giờ địa phương.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToLocalGregorian extends JdnToUtcGregorian
{
    /**
     * Tạo đối tượng mới
     * 
     * @param JulianDayNumberInterface $jdn Mốc ngày JDN. Nếu chênh lệch giờ địa phương (offset) bằng 0, đầu ra vẫn sẽ
     *                                      là ngày tháng tương ứng với UTC.
     * @return void 
     * @throws Exception 
     */
    public function __construct(private JulianDayNumberInterface $jdn)
    {
        parent::__construct($this->fakeLocalJdn());
    }

    /**
     * Làm giả giá trị JDN tương ứng với UTC thành JDN tương ứng với giờ địa phương
     * 
     * @return float 
     */
    private function fakeLocalJdn(): float
    {
        if ($this->getOffset() === 0) {
            return $this->jdn->getJd();
        }

        return $this->jdn->getJd() + $this->getOffset() / 86400;
    }

    /**
     *{@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->jdn->getOffset();
    }

    /**
     *{@inheritdoc}
     */
    public function getTimezone(): ?DateTimeZone
    {
        if ($this->getOffset() === 0) {
            return parent::getTimezone();
        }

        if (!$this->timezone) {
            $zoneH = $this->getOffset() / 3600;
            $preffix = str_pad(
                abs(floor($zoneH)), 
                2,
                '0',
                STR_PAD_LEFT
            );

            $preffix = ($zoneH < 0) ? '-' . $preffix : '+' . $preffix;

            $subfix = abs($zoneH - floor($zoneH)) * 60;
            
            if ($subfix < 10) {
                $subfix = str_pad($subfix, 2, '0', STR_PAD_LEFT);
            }

            $this->timezone = new DateTimeZone($preffix . $subfix);
        }

        return $this->timezone;
    }
}