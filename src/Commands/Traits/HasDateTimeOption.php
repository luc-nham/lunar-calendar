<?php namespace VanTran\LunarCalendar\Commands\Traits;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;

/**
 * Trait cho phép truy cập vào các lớp tạo lệnh có tham số hoặc tùy chọn thời gian Dương lịch đầu vào, chuyển nó thành
 * đối tượng DateTimeImmutable để có thể sử dụng thuận tiện.
 */
trait HasDateTimeOption
{
    private $inputDateTime;

    /**
     * Trả về thời gian nếu đầu vào có tùy chọn hoặc tham số 'date'
     * 
     * @return DateTimeImmutable 
     * @throws Throwable 
     */
    protected function getInputDateTime(): DateTimeImmutable
    {
        if (!$this->inputDateTime) {
            try {
                $date = $this->input->getOption('date');
            } catch (\Throwable $th) {
                try {
                    $date = $this->input->getArgument('date');
                } catch (\Throwable $th) {
                    throw $th;
                }
            }

            try {
                $timezone = $this->input->getOption('timezone');
            } catch (\Throwable $th) {
                try {
                    $timezone = $this->input->getOption('timezone');
                } catch (\Throwable $th) {
                    throw $th;
                }
            }

            $this->inputDateTime = new DateTimeImmutable(
                $date,
                new DateTimeZone($timezone)
            );
        }

        return $this->inputDateTime;
    }

    protected function getInputTimeZone(): false|DateTimeZone
    {
        return $this->getInputDateTime()->getTimezone();
    }
}