<?php namespace VanTran\LunarCalendar\Correctors;

use Exception;
use VanTran\LunarCalendar\Converters\BaseJDN;
use VanTran\LunarCalendar\Converters\BaseNewMoonPhaseConverter;
use VanTran\LunarCalendar\Converters\LunarLeapMonthConverter;
use VanTran\LunarCalendar\Converters\WinterSolsticeNewMoonConverter;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeComponentInterface;
use VanTran\LunarCalendar\Interfaces\MoonPhaseInterface;
use VanTran\LunarCalendar\Interfaces\WinterSolsticeNewMoonInterface;
use VanTran\LunarCalendar\Interfaces\LunarLeapMonthInterface;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeStorageInterface;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeStorageMutableInterface;

class LunarDateTimeCorrector extends BaseJDN implements LunarDateTimeComponentInterface
{
    /**
     * @var MoonPhaseInterface
     */
    protected $newMoon;

    /**
     * @var MoonPhaseInterface 
     */
    protected $wsNewMoon;

    /**
     * @var null|LunarLeapMonthInterface
     */
    protected $leapMonth;

    /**
     * @var int Số ngày trong tháng Âm lịch
     */
    protected $dayOfMonth;

    /**
     * @var int Số ngày trong năm Âm lịch
     */
    protected $dayOfYear;

    /**
     * Tạo đối tượng mới
     * 
     * @param LunarDateTimeStorageMutableInterface $storage Các mốc Âm lịch đầu vào
     * @return void 
     * @throws Exception 
     */
    public function __construct(protected LunarDateTimeStorageMutableInterface $storage)
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
        $this->initWsNewMoon();

        // Khởi tạo và xác thực dữ liệu tháng nhuận
        $this->initLeapMonth();

        // Khởi tạo điểm sóc đầu vào
        $this->initNewMoon();

        // Khởi tạo số ngày của tháng
        $this->initDayOfMonth();

        // Khởi tạo mốc ngày Julian tương ứng với điểm Âm lịch đầu vào
        $this->initJd();
    }
    
    /**
     * Xác thực năm âm lịch đầu vào
     * @return void 
     * @throws Exception 
     */
    protected function validateLunarYear(): void
    {
        $year = $this->storage->getYear();

        if (
            $year === 0 ||
            $year < -4713 ||
            $year > 2500
        ) {
            throw new Exception("Error. The lunar year is out of supported range.");
        }
    }

    /**
     * Khởi tạo điểm Sóc tháng 11 của năm Âm lịch cần tìm
     * @return void 
     */
    protected function initWsNewMoon(): void
    {
        $this->wsNewMoon = new WinterSolsticeNewMoonConverter(
            $this->storage->getYear(), 
            $this->storage->getOffset()
        );
    }

    /**
     * Khởi tạo dữ liệu tháng nhuận âm lịch
     * @return void 
     */
    protected function initLeapMonth(): void
    {
        $this->leapMonth = new LunarLeapMonthConverter($this->getWsNewMoon());

        /**
         * Trường hợp tháng đầu vào được đánh dấu là nhuận, cần kiểm tra và làm chính xác lại dữ liệu nếu năm Âm lịch
         * không có tháng nhuận, hoặc tháng đầu vào không tương ứng với số tháng nhuận.
         */
        if ($this->storage->isLeapMonth()) {
            if (
                !$this->leapMonth->isLeap() ||
                $this->storage->getMonth() != $this->leapMonth->getMonth()
            ) {
                $this->storage->setIsLeapMonth(false);
            }
        }
    }

    /**
     * Khởi tạo dữ liệu điểm Sóc đầu vào
     * 
     * @return void 
     */
    protected function initNewMoon(): void
    {
        $month = $this->storage->getMonth();
        $isLeap = $this->storage->isLeapMonth();
        $leap = $this->getLeapMonth();

        if ($month == 11) {
            $this->newMoon = ($isLeap)
                ? $this->getWsNewMoon()->add(1)
                : $this->getWsNewMoon();

            return;
        }

        if ($isLeap) {
            $this->newMoon = new BaseNewMoonPhaseConverter(
                $leap->getJd(), 
                $leap->getOffset()
            );

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
        
        $this->newMoon = $this->getWsNewMoon()->add($k);
    }

    /**
     * Khởi tạo tổng số ngày của tháng và khớp các số liệu khác khi đầu vào là
     * một mốc ngày Âm lịch không chính xác.
     * 
     * @return void 
     */
    protected function initDayOfMonth(): void
    {
        /**
         * Sử dụng số ngày JDN của điểm Sóc của kỳ trăng mới tiếp theo trừ đi số
         * ngày JDN của điểm Sóc hiện tại, sẽ được tổng số ngày trong tháng AL.
         */
        $nextNm = $this->getNewMoon()->add(1);
        $this->dayOfMonth = $nextNm->getMidnightJd() - $this->getNewMoon()->getMidnightJd();

        /**
         * Trường hợp số ngày đầu vào lớn hơn tổng số ngày trong tháng (ví dụ,
         * đầu vào là ngày 30/09/2023 AL, trong khi tháng 9 này chỉ có 29 ngày),
         * tức phải là ngày 01/10/2023, cần phải cập nhật lại số liệu.
         */
        if ($this->storage->getDay() > $this->dayOfMonth) {
            $day = $this->storage->getDay() - $this->dayOfMonth;
            $month = $this->storage->getMonth() + 1;

            $this->storage->setDay($day);
            $this->storage->setMonth(
                ($month > 12) ? 1 : $month
            );
            
            /**
             * Trường hợp tháng tiếp theo trở thành tháng Giêng của năm kế tiếp,
             * ví dụ đầu vào là 30/12/2021 (sai - dư), toàn bộ dữ liệu cần được
             * tính toán lại.
             */
            if ($month == 13) {
                $this->storage->setYear(
                    $this->storage->getYear() + 1
                );
                
                $this->init();
                return;
            }

            /**
             * Trường hợp tháng trở thành tháng nhuận (ví dụ, đầu vào Âm lịch là
             * ngày 30/06/2017 - tương ứng 01/06+/2017), cần đặt lại các giá trị
             * của số tháng và đánh dấu nó là tháng nhuận.
             */
            if (
                $this->getLeapMonth()->isLeap() &&
                $month - $this->getLeapMonth()->getMonth() === 1
            ) {
                $this->storage->setMonth($month - 1);
                $this->storage->setIsLeapMonth(true);
            }

            /**
             * Điểm sóc thực tế sẽ là điểm sóc của chu kỳ kế tiếp. Tổng số ngày
             * trong tháng cũng phải được cập nhật lại.
             */
            $this->newMoon = $nextNm;
            $this->dayOfMonth = $nextNm->add(1)->getMidnightJd() - $nextNm->getMidnightJd();
        }
    }

    /**
     * Tính toán và khởi tạo số ngày Julian tương ứng với mốc Âm lịch đầu vào
     * @return void 
     */
    protected function initJd(): void
    {
        if ($this->storage->getDay() == 1) {
            $jd = $this->getNewMoon()->getMidnightJd();
        } else {
            $jd = $this->getNewMoon()->getMidnightJd() + $this->storage->getDay() - 1;
        }

        $jd += ($this->storage->getHour() * 3600 
            + $this->storage->getMinute() * 60 + $this->storage->getSecond())
            / 86400;

        $this->setJd($jd + 0.0000001); // Hiệu chỉnh sai số
    }

    /**
     *{@inheritdoc}
     */
    public function getNewMoon(): MoonPhaseInterface 
    { 
        return $this->newMoon;
    }

    /**
     *{@inheritdoc}
     */
    public function getWsNewMoon(): WinterSolsticeNewMoonInterface 
    { 
        return $this->wsNewMoon;
    }

    /**
     *{@inheritdoc}
     */
    public function getLeapMonth(): LunarLeapMonthInterface 
    { 
        return $this->leapMonth;
    }

    /**
     *{@inheritdoc}
     */
    public function getDateTimeStorage(): LunarDateTimeStorageInterface 
    { 
        return $this->storage;
    }

    /**
     *{@inheritdoc}
     */
    public function getDayOfMonth(): int 
    { 
        return $this->dayOfMonth;
    }

    /**
     *{@inheritdoc}
     */
    public function getDayOfYear(): int 
    { 
        if (!$this->dayOfYear) {
            $add = 2;
            $subtract = 10;
            $nm11th = $this->getWsNewMoon();

            if ($this->getLeapMonth()->isLeap()) {
               if ($this->getLeapMonth()->getMonth() == 11) {
                $add ++;
               } else {
                $subtract ++;
               }
            }

            $nextYear1thNm = $nm11th->add($add);
            $crrYear1thNm = $nm11th->subtract($subtract);

            $this->dayOfYear = $nextYear1thNm->getMidnightJd() - $crrYear1thNm->getMidnightJd();
        }

        return $this->dayOfYear;
    }

    /**
     *{@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->storage->getOffset();
    }
}