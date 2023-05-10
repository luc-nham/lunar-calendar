<?php namespace VanTran\LunarCalendar\Commands;

use DateTimeZone;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use VanTran\LunarCalendar\LunarDateTime;

#[AsCommand(
    name: 'create:lunar-wsnmp-list',
    description: 'Tạo danh sách các điểm Trăng mới (Sóc) tháng 11 của các năm Âm lịch',
    hidden: false,
    aliases: [
        'make-wsnmp-list'
    ]
)]

class CreateLunarWsNmpList extends AbstractLunarCommandListOutput
{
    /**
     * @inheritdoc
     */
    protected function getInfoTableHeader(): array 
    { 
        return [
            'Thiết lập đầu vào',
            'Giá trị'
        ];
    }

    /**
     * @inheritdoc
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
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDataTableHeader(): array 
    { 
        return [
            'Thời gian Âm lịch',
            'Thời gian Dương lịch',
            'JDN (nửa đêm)',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getListData(): array 
    { 
        $year = $this->input->getOption('start-year');
        $endYear = $this->input->getOption('end-year');
        $timezone = $this->input->getOption('timezone');
        $format = $this->input->getOption('date-format');
        $items = [];

        do {
            $lunar = new LunarDateTime("01/11/$year $timezone");
            $date = $lunar->toDateTime();

            array_push($items, [
                'lunar' => $lunar->format($format),
                'gregorian' => $date->format($format),
                'jd' => $lunar->getMidnightJd(),
            ]);

            $year ++;
        } 
        while ($year <= $endYear);

        return $items;
    }

    /**
     * @inheritdoc
     */
    protected function getOuputType(): int 
    { 
        return $this->input->getOption('output-type');
    }

    /**
     * @inheritdoc
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
    }
}