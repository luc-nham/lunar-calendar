<?php namespace VanTran\LunarCalendar\Lunar;

use VanTran\LunarCalendar\Mjd\MjdInterface;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhase;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\MoonPhaseInterface;

class LunarDateTimeCorrector implements LunarBaseComponentInterface
{
    /**
     * @var MjdInterface
     */
    protected $mjd;

    /**
     * @var MoonPhaseInterface
     */
    protected $newMoon;

    /**
     * @var MoonPhaseInterface 
     */
    protected $newMoon11th;

    /**
     * @var LunarLeapMonthInterface
     */
    protected $leapMonth;

    public function __construct(protected LunarInputInterface $lunar)
    {
        $this->correct();
    }

    /**
     * Thực hiện các bước tuần tự làm chính xác lại dữ liệu từ đầu vào
     * @return void 
     */
    protected function correct(): void
    {
        $this->correct11thNewMoon();
        $this->correctLeapMonth();
    }

    /**
     * Xác định điểm Sóc tháng 11 của năm Âm lịch cần tìm
     * @return LunarDateTimeCorrector 
     */
    protected function correct11thNewMoon(): void
    {
        $this->newMoon11th = new Lunar11thNewMoonPhase(
            $this->lunar->getYear(), 
            $this->lunar->getOffset()
        );
    }

    protected function correctLeapMonth(): void
    {
        $this->leapMonth = new LunarLeapMonth($this->get11thNewMoon());
    }

    /**
     * {@inheritdoc}
     */
    public function getMjd(): MjdInterface 
    { 
        return $this->mjd;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewMoon(): MoonPhaseInterface 
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

}