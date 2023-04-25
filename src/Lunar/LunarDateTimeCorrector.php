<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;
use Exception;
use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\MoonPhases\BaseNewMoonPhase;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhase;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\MoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;

class LunarDateTimeCorrector extends BaseMjd implements LunarBaseComponentInterface
{
    /**
     * Năm tối thiểu hỗ trợ lập lịch
     */
    public const MIN_YEAR = 1901;

    /**
     * Năm tối đa hỗ trợ lập lịch
     */
    public const MAX_YEAR = 2100;

    /**
     * @var MoonPhaseInterface
     */
    protected $newMoon;

    /**
     * @var MoonPhaseInterface 
     */
    protected $newMoon11th;

    /**
     * @var null|LunarLeapMonthInterface
     */
    protected $leapMonth;

    /**
     * @var int Số ngày trong tháng Âm lịch
     */
    protected $dayOfMonth;

    public function __construct(protected LunarInputInterface $input)
    {
        $this->init();
    }

    /**
     * Thực hiện các bước tuần tự làm chính xác lại dữ liệu từ đầu vào
     * @return void 
     */
    protected function init(): void
    {
        // Xác thực năm đầu vào nằm trong khoảng thời gian cho phép
        $this->validateLunarYear();

        // Khởi tạo được dữ liệu tháng 11 Âm lịch của năm
        $this->init11thNewMoon();

        // Khởi tạo và xác thực dữ liệu tháng nhuận
        $this->initLeapMonth();
        $this->validateLeapMonth();

        // Khởi tạo điểm sóc đầu vào
        $this->initNewMoon();

        // Khởi tạo số ngày của tháng
        $this->initDayOfMonth();
        $this->validateLunarDay();

        // Khởi tạo mốc ngày MJD tương ứng với điểm Âm lịch đầu vào
        $this->initJd();
    }
    
    /**
     * Xác thực năm âm lịch đầu vào
     * @return void 
     * @throws Exception 
     */
    protected function validateLunarYear(): void
    {
        $year = $this->input->getYear();

        if ($year < self::MIN_YEAR || $year > self::MAX_YEAR) {
            throw new Exception('Error. Support lunar year from ' . self::MIN_YEAR . ' to ' . self::MAX_YEAR);
        }
    }

    /**
     * Xác thực tháng nhuận đầu vào và tháng nhuận tính toán được
     * 
     * @return void 
     * @throws Exception 
     */
    protected function validateLeapMonth(): void
    {
        if ($this->input->isLeapMonth()) {
            $leap = $this->getLeapMonth();

            if ($leap == null) {
                throw new Exception('Error. The Lunar year ' . $this->input->getYear() . ' dose not have leap month.');
            }

            if ($leap->getMonth() != $this->input->getMonth()) {
                throw new Exception(sprintf(
                    "Error. The leap month of lunar year %d is %d, not %d.",
                    $this->input->getYear(),
                    $leap->getMonth(),
                    $this->input->getMonth()
                ));
            }
        }
    }

    /**
     * Xác thực ngày Âm lịch đầu vào không được vượt quá tổng số ngày của tháng
     * 
     * @return void 
     * @throws Exception 
     */
    protected function validateLunarDay(): void
    {
        if ($this->input->getDay() > $this->getDayOfMonth()) {
            throw new Exception('Lunar day invalid. Maximum day of current lunar month is ' . $this->getDayOfMonth() . ' days.');
        }
    }

    /**
     * Khởi tạo điểm Sóc tháng 11 của năm Âm lịch cần tìm
     * @return LunarDateTimeinitor 
     */
    protected function init11thNewMoon(): void
    {
        $this->newMoon11th = new Lunar11thNewMoonPhase(
            $this->input->getYear(), 
            $this->input->getOffset()
        );
    }

    /**
     * Khởi tạo dữ liệu tháng nhuận âm lịch
     * @return void 
     */
    protected function initLeapMonth(): void
    {
        $leap = new LunarLeapMonth($this->get11thNewMoon());
        
        if ($leap->isLeap()) {
            $this->leapMonth = $leap;
        }
    }

    /**
     * Khởi tạo dữ liệu điểm Sóc đầu vào
     * 
     * @return void 
     */
    protected function initNewMoon(): void
    {
        $month = $this->input->getMonth();
        $isLeap = $this->input->isLeapMonth();
        $leap = $this->getLeapMonth();

        if ($month == 11) {
            $this->newMoon = ($isLeap)
                ? $this->get11thNewMoon()->add(1)
                : $this->get11thNewMoon();

            return;
        }

        if ($isLeap && $month == $leap->getMonth()) {
            $this->newMoon = new BaseNewMoonPhase($leap->getJd(), $leap->getOffset());
            return;
        }

        $k = $month - 11;

        if ($leap) {
            if ($k >= 0) {
                if ($leap->getMonth() == 11) {
                    if ($isLeap && $month == 11 || $month == 12) {
                        $k += 1;
                    }
                }
            } else {
                if ($month <= $leap->getMonth() && $leap->getMonth() != 11) {
                    $k -= 1;
                }
            }
        }
        
        $this->newMoon = $this->get11thNewMoon()->add($k);
    }

    /**
     * Khởi tạo tổng số ngày của tháng
     * @return void 
     */
    protected function initDayOfMonth(): void
    {
        $nextNm = $this->getNewMoon()->add(1);
        $this->dayOfMonth = $nextNm->getMidnightJd() - $this->getNewMoon()->getMidnightJd();
    }

    /**
     * Tính toán và khởi tạo số ngày MJD tương ứng với mốc Âm lịch đầu vào
     * @return void 
     */
    protected function initJd(): void
    {
        if ($this->input->getDay() == 1) {
            $jd = $this->getNewMoon()->getJd();
        } else {
            $jd = $this->getNewMoon()->getJd() + $this->input->getDay() - 1;
        }

        $this->jd = $jd;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewMoon(): NewMoonPhaseInterface 
    { 
        return $this->newMoon;
    }

    /**
     * {@inheritdoc}
     */
    public function get11thNewMoon(): Lunar11thNewMoonPhaseInterface 
    { 
        return $this->newMoon11th;
    }

    /**
     * {@inheritdoc}
     */
    public function getLeapMonth(): ?LunarLeapMonthInterface 
    { 
        return $this->leapMonth;
    }

    /**
     * {@inheritdoc}
     */
    public function getDayOfMonth(): int
    {
        return $this->dayOfMonth;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->input->getOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeZone(): ?DateTimeZone 
    { 
        return $this->input->getTimezone();
    }
}