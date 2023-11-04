<?php 

namespace VanTran\LunarCalendar\Tests\Issues;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\LunarDateTime;

/**
 * Lớp kiểm tra sửa lỗi vấn đề số 38
 * 
 * @link https://github.com/vantran445/lunar-calendar/issues/38
 * @package VanTran\LunarCalendar\Tests\Issues
 */
class Issue38Test extends TestCase
{
    /**
     * @var DateTimeZone
     */
    private $timezone;

    public function setup(): void
    {
        $this->timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
    }

    /**
     * @return void 
     */
    public function testFixed38(): void
    {
        $lunar = new LunarDateTime('2022-01-02', $this->timezone, 2);
        $this->assertEquals('30/11/2021', $lunar->format('d/m/Y'));

        $lunar = new LunarDateTime('2024-01-08', $this->timezone, 2);
        $this->assertEquals('27/11/2023', $lunar->format('d/m/Y'));
    }
}