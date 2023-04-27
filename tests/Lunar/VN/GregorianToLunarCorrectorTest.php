<?php namespace VanTran\LunarCalendar\Tests\Lunar\VN;

use DateTime;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\ExpectationFailedException;
use VanTran\LunarCalendar\Lunar\GregorianToLunarCorrector;
use VanTran\LunarCalendar\Lunar\LunarDateTimeInput;

class GregorianToLunarCorrectorTest extends BaseTest
{
    /**
     * Kiểm tra tính toán điểm Sóc tháng 11 Âm lịch
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testNewMoon11th(): void
    {
        $cases = [
            // Trường hợp thời gian nhỏ hơn điểm Sóc tháng 11, nhưng số năm Âm, Dương lịch bằng nhau
            [
                'day' => '26',
                'month' => '4',
                'year' => '2023',
                'jd' => 2460291.7083333335
            ],

            // Trường hợp thời gian lớn hơn điểm Sóc tháng 11, nhưng số năm Âm, Dương lịch bằng nhau
            [
                'day' => '30',
                'month' => '12',
                'year' => '2023',
                'jd' => 2460291.7083333335 
            ],

            // Trường hợp thời gian lớn hơn điểm Sóc tháng 11, và năm mới dương lịch đến trước năm Âm lịch
            [
                'day' => '8',
                'month' => '2',
                'year' => '2024',
                'jd' => 2460291.7083333335
            ],
            [
                'day' => '1',
                'month' => '1',
                'year' => '2024',
                'jd' => 2460291.7083333335
            ],
            [
                'day' => '16',
                'month' => '2',
                'year' => '2026',
                'jd' => 2461029.7083333335
            ],
            [
                'day' => '16',
                'month' => '1',
                'year' => '2026',
                'jd' => 2461029.7083333335
            ],
            [
                'day' => '18',
                'month' => '2',
                'year' => '2034',
                'jd' => 2463923.7083333335
            ],
            [
                'day' => '4',
                'month' => '2',
                'year' => '2027',
                'jd' => 2461383.7083333335
            ],
            [
                'day' => '5',
                'month' => '1',
                'year' => '2027',
                'jd' => 2461383.7083333335
            ],
        ];

        $config = [
            'offset' => 25200,
            'timezone' => $this->getTimeZone()
        ];

        foreach ($cases as $case) {
            $case = array_merge($case, $config);
            $input = new LunarDateTimeInput($case);
            $corrector = new GregorianToLunarCorrector($input);
            $nm11th = $corrector->get11thNewMoon();

            $this->assertEquals($case['jd'], $nm11th->getMidnightJd(), sprintf(
                "day %d | month %d | year %d",
                $input->getDay(),
                $input->getMonth(),
                $input->getYear()
            ));
        }
    }
}