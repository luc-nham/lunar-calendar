<?php namespace VanTran\LunarCalendar\Commands;

use DateTimeZone;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use VanTran\LunarCalendar\Correctors\LunarDateTimeCorrector;
use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\Parsers\LunarDateTimeParser;

#[AsCommand(
    name: 'create:lunar-leap-month-list',
    description: 'Tạo danh sách tháng nhuận của các năm Âm lịch',
    hidden: false,
    aliases: [
        'make-leap-list'
    ]
)]

class CreateLunarLeapMonthList extends AbstractLunarCommandListOutput
{
    /**
     *{@inheritdoc}
     */
    protected function getInfoTableHeader(): array 
    { 
        return [
            'Thiết lập đầu vào',
            'Giá trị'
        ];
    }

    /**
     *{@inheritdoc}
     */
    protected function getInformationData(): array 
    { 
        return [
            [
                'Mô tả',
                'Danh sách điểm Sóc tháng 11 của các năm Âm lịch với Dương lịch tương ứng lúc nửa đêm.'
            ],
            [
                'Năm Âm lịch bắt đầu',
                $this->input->getOption('start-year')
            ],
            [
                'Năm Âm lịch kết thúc',
                $this->input->getOption('end-year')
            ],
            [
                'Múi giờ địa phương',
                $this->input->getOption('timezone')
            ],
            [
                'Định dạng thời gian',
                $this->input->getOption('date-format')
            ],
            [
                'Định dạng đầu ra',
                ($this->input->getOption('output-type') == 1) ? '(1) Bảng dữ liệu' : '(2) Mảng PHP'
            ],
            [
                'Lọc kết quả',
                ($this->input->getOption('leap-only') == 1) ? '(1) Chỉ các năm có tháng nhuận' : '(2) Hiển thị tất cả'
            ]
        ];
    }

    /**
     *{@inheritdoc}
     */
    protected function getDataTableHeader(): array 
    { 
        return [
            'Năm Âm lịch',
            'Tháng nhuận',
            'JD điểm Sóc (nửa đêm)'
        ];
    }

    /**
     *{@inheritdoc}
     */
    protected function getListData(): array 
    { 
        $year = $this->input->getOption('start-year');
        $endYear = $this->input->getOption('end-year');
        $timezone = $this->input->getOption('timezone');
        $format = $this->input->getOption('date-format');
        $items = [];

        do {
            $parser = new LunarDateTimeParser("30/05/$year $timezone");
            $corrector = new LunarDateTimeCorrector($parser);
            $leap = $corrector->getLeapMonth();

            if ($leap->isLeap()) {
                array_push($items, [
                    'year' => $year,
                    'month' => $leap->getMonth(),
                    'jd' => $leap->getMidnightJd(),
                ]);
            }
            else {
                if (!$this->input->getOption('leap-only')) {
                    array_push($data, [
                        'year' => $year,
                        'month' => null,
                        'jd' => null,
                    ]);
                }
            }

            $year ++;
        } 
        while ($year <= $endYear);

        return $items;
    }

    /**
     *{@inheritdoc}
     */
    protected function getOuputType(): int 
    { 
        return $this->input->getOption('output-type');
    }

    /**
     *{@inheritdoc}
     */
    protected function configure()
    {
        $this->setHelp($this->getDescription());

        $this->addOption(
            'start-year', 
            null,
            InputOption::VALUE_OPTIONAL,
            'Năm Âm lịch bắt đầu',
            1901
        );

        $this->addOption(
            'end-year', 
            null,
            InputOption::VALUE_OPTIONAL,
            'Năm Âm lịch kết thúc',
            1910
        );

        $this->addOption(
            'timezone',
            null,
            InputOption::VALUE_OPTIONAL,
            'Múi giờ địa phương (định danh), mặc định sử dụng GMT+7 cho Âm lịch Việt Nam',
            '+0700'
        );

        $this->addOption(
            'output-type',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn xuất ra bảng dữ liệu (mặc định) hoặc cấu trúc mảng PHP.',
            1
        );

        $this->addOption(
            'date-format',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn định dạng thời gian đầu ra',
            'd-m-Y P'
        );

        $this->addOption(
            'leap-only',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn chỉ xuất ra danh sách các năm có tháng nhuận (1 - mặc định) hoặc bao gồm cả các năm không nhuận (0)',
            1
        );
    }
}