<?php namespace VanTran\LunarCalendar\Lunar;

use VanTran\LunarCalendar\Mjd\GregorianToMjd;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhase;
use VanTran\LunarCalendar\MoonPhases\MjdToNewMoonPhase;

/**
 * Lớp tính toán các thành phần Âm lịch từ mốc dương lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class GregorianToLunarCorrector extends LunarDateTimeCorrector
{
    protected function init(): void
    {
        // Khởi tạo MJD
        $this->initJd();

        // Khởi tạo điểm sóc từ dương lịch đầu vào
        $this->initNewMoon();

        // Khởi tạo điểm Sóc tháng 11 Âm lịch
        $this->init11thNewMoon();

        // Khởi tạo tháng nhuận
        $this->initLeapMonth();

        // Khởi tạo số ngày của tháng
        $this->initDayOfMonth();
    }

    /**
     * {@inheritdoc}
     */
    protected function initNewMoon(): void
    {
        $this->newMoon = new MjdToNewMoonPhase($this);
    }

    /**
     * khởi tạo mốc ngày MJD từ dương lịch đầu vào
     * @return void 
     */
    protected function initJd(): void
    {
        $jd = new GregorianToMjd(
            $this->input->getOffset(),
            $this->input->getYear(),
            $this->input->getMonth(),
            $this->input->getDay(),
            $this->input->getHour(),
            $this->input->getMinute(),
            $this->input->getSecond(),
        );

        $this->jd = $jd->getJd();
    }

    /**
     * {@inheritdoc}
     */
    protected function init11thNewMoon(): void
    {
        parent::init11thNewMoon();

        /**
         * Trường hợp năm mới Dương lịch đến trước năm mới Âm lịch (thường rơi vào các tháng 1 và 2 dương lịch, tương
         * ứng với khoảng tháng 11 - 12 Âm lịch năm cũ), thì điểm Sóc tháng 11 cần tính là điểm của năm trước đó nữa.
         * Đoạn mã sau đây xác định điểm Sóc hiện tại thuộc về năm Âm lịch tương ứng vói năm Dương lịch, hoặc thuộc về
         * năm trước đó.
         */
        $totalPhase11th = $this->get11thNewMoon()->getTotalCycles();
        $totalPhaseCurrent = $this->getNewMoon()->getTotalCycles();

        if (11 - ($totalPhase11th - $totalPhaseCurrent) <= 0) {
            $this->newMoon11th = new Lunar11thNewMoonPhase(
                $this->input->getYear() - 1,
                $this->getOffset()
            );
        }
    }
}