<?php namespace VanTran\LunarCalendar\Converters;

use Exception;

/**
 * Bộ chuyển đổi ngày tháng lịch Gregorian thành số ngày Julian
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class GregorianToJDNConverter extends BaseJDN
{
    /**
     * Tạo đối tượng mới. Khi $offset được truyền vào khác 0, lớp sẽ hiểu rằng ngày tháng tương ứng với giờ địa phương,
     * đầu ra sẽ là số ngày JDN tính theo UTC tương ứng với thời điểm chuyển đổi. Chẳng hạn, vào lúc 7 giờ sáng ngày
     * 01 tháng 01 năm 1970 theo giờ Việt Nam (GMT+7), chênh lệch với UTC ($offset) là 25200 giây, và thời gian UTC lúc
     * đó là 00:00 ngày 01 tháng 01 năm 1970, thì số ngày Julian tính toán được sẽ là 2440587.5 
     * 
     * @param int $year Năm từ -4714
     * @param int $month Tháng từ 1 - 12
     * @param int $day Ngày từ 1 đến 31
     * @param int $hour Giờ từ 0 - 23, mặc định 0
     * @param int $minute Phút từ 0 đến 59, mặc định 0
     * @param int $second Giây từ 0 đến 59, mặc định 0
     * @param int $offset Chênh lệch giờ địa phương từ -43200 đến 50400, mặc định 0 (UTC)
     * @return void 
     * @throws Exception 
     */
    public function __construct(
        private int $year = 1970, 
        private int $month = 1, 
        private int $day = 1, 
        private int $hour = 0, 
        private int $minute = 0,
        private int $second = 0,
        protected int $offset = 0
    )
    {
        parent::__construct(0, $offset);
    }

    /**
     * Xác thực ngày tháng hợp lệ
     * @return void 
     * @throws Exception 
     */
    private function validateDate(): void
    {
        if ($this->year === 0 || $this->year < -4714) {
            throw new Exception("Invalid year number.");
        }
        else {
            if ($this->month <= 0 || $this->month > 12) {
                throw new Exception("Invalid month number.");
            }
            else {
                if ($this->day <= 0 || $this->day > 21) {
                    throw new Exception("Invalid day number.");
                }
            }
        }

        if ($this->year === -4714) {
            if (
                ($this->month == 11 && $this->day < 25) ||
                $this->month < 11
            ) {
                throw new Exception("Error. the date time is out of supported range.");
            }
        }
    }

    /**
     * Xác thực giờ phút giây hợp lệ
     * @return void 
     * @throws Exception 
     */
    private function validateTime(): void
    {
        if ($this->hour < 0 || $this->hour > 23) {
            throw new Exception("Invalid hour number.");
        }
        else {
            if ($this->minute < 0 || $this->minute > 59) {
                throw new Exception("Invalid minute number.");
            }
            else {
                if ($this->second < 0 || $this->second > 59) {
                    throw new Exception("Invalid second number.");
                }
            }
        }
    }

    /**
     * Xác thực chênh lệch giờ địa phương hợp lệ
     * @return void 
     * @throws Exception 
     */
    private function validateOffset(): void
    {
        if ($this->offset < -43200 || $this->offset > 50400) {
            throw new Exception("Invalid offset value.");
        }
    }

    /**
     * Xác thực đầu vào hợp lệ
     * 
     * @return void 
     * @throws Exception 
     */
    private function validate(): void
    {
        try {
            $this->validateDate();
            $this->validateTime();
            $this->validateOffset();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Chuyển đổi ngày tháng đầu vào thành JDN
     * @return void 
     */
    protected function convert(): void
    {
        $this->validate();

        // Other method
        // $year = $this->year;
        // $month = $this->month - 3;

        // if ($this->month <= 2) {
        //     $month = $this->month + 9;
        //     $year --;
        // }

        // $c = $year / 100;
        // $d = $year - 100 * $c;
        // $j = (146097 * $c) / 4 + (1461 * $d) / 4 + (153 * $month + 2) / 5 + $this->day + 1721119;
        // $j = floor($j);

        $a = floor((14 - $this->month) / 12);
        $y = $this->year + 4800 - $a;
        $m = $this->month + 12 * $a - 3;
        $j = $this->day + floor((153 * $m + 2) / 5)
            + 365 * $y
            + floor($y / 4)
            - floor($y / 100)
            + floor($y / 400)
            - 32045;

        $h = ($this->hour - 12) % 24;
        $fragtion = ($h * 3600 + $this->minute * 60 + $this->second) / 86400;
        $fragtion = round($fragtion, 7);
        
        $j += $fragtion;

        if ($this->getOffset() !== 0) {
            $j -= $this->getOffset() / 86400;
        }

        $this->setJd($j);
    }

    /**
     * Thay đổi ngày tháng đầu vào
     * 
     * @param int $year 
     * @param int $month 
     * @param int $day 
     * @return void 
     * @throws Exception 
     */
    public function setDate(int $year = 0, int $month = 0, int $day = 0): void
    {
        if ($year) {
            $this->year = $year;
        }

        if ($month) {
            $this->month = $month;
        }

        if ($day) {
            $this->day = $day;
        }

        $this->validateDate();
        $this->setJd(0);
    }

    /**
     * Thay đổi giờ phút giây đầu vào
     * 
     * @param int $hour 
     * @param int $minute 
     * @param int $second 
     * @return void 
     * @throws Exception 
     */
    public function setTime(int $hour = 0, int $minute = 0, int $second = 0): void
    {
        if ($hour) {
            $this->hour = $hour;
        }

        if ($this->minute) {
            $this->minute = $minute;
        }

        if ($second) {
            $this->second = $second;
        }

        $this->validateTime();
        $this->setJd(0);
    }

    # @inheritdoc
    public function getJd(): float
    {
        if ($this->jd <= 0) {
            $this->convert();
        }

        return parent::getJd();
    }

    public function setOffset(int $offset): void
    {
        if ($offset !== $this->getOffset()) {
            $this->setJd(0);
            parent::setOffset($offset);
        }
    }
}