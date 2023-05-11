<?php namespace VanTran\LunarCalendar\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use VanTran\LunarCalendar\Commands\Traits\HasDateTimeOption;
use VanTran\LunarCalendar\Converters\BaseNewMoonPhaseConverter;
use VanTran\LunarCalendar\Converters\DateTimeToJdnConverter;
use VanTran\LunarCalendar\Converters\JdnToDateTimeConverter;

#[AsCommand(
    name: 'create:new-moon-phase-list',
    description: 'Tạo danh sách các điểm Trăng mới (Sóc) từ một mốc thời gian dương lịch',
    hidden: false,
    aliases: [
        'make-nmp-list'
    ]
)]

class CreateNewMoonPhaseList extends AbstractLunarCommandListOutput
{
    use HasDateTimeOption;

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
                $this->getDescription(),
            ],
            [
                'Thời gian đầu vào',
                $this->getInputDateTime()->format('Y-m-d H:i:s')
            ],
            [
                'Múi giờ địa phương',
                $this->getInputTimeZone()->getName(),
            ],
            [
                'Kết quả đầu ra',
                $this->input->getOption('quantity')
            ],
            [
                'Sắp xếp kết quả',
                ($this->input->getOption('next')) ? '(1) Tìm điểm Sóc kế tiếp' : '(2) Tìm điểm Sóc trước đó'
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
            'Số ngày Julian',
            'Tổng chu kỳ Trăng',
            'Thời gian bắt đầu'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getListData(): array 
    { 
        $jdn = new DateTimeToJdnConverter($this->getInputDateTime());
        $newMoon = new BaseNewMoonPhaseConverter(
            $jdn->getJd(), 
            $jdn->getOffset()
        );

        $items = [];

        for ($i = 1; $i <= $this->input->getOption('quantity'); $i ++) 
        {
            $ouputDate = (new JdnToDateTimeConverter($newMoon))->getDateTime();

            array_push($items, [
                'jd' => $newMoon->getJd(),
                'cycles' => $newMoon->getTotalCycles(),
                'date' => $ouputDate->format('Y-m-d H:i:s P'),
            ]);

            $newMoon = ($this->input->getOption('next'))
                ? $newMoon->add(1)
                : $newMoon->subtract(1);
        }

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
        $this->setHelp("Cho phép bạn tạo ra một danh sách các điểm của Pha trăng mới (điểm Sóc) sắp xếp theo thứ tự.");

        // Input argumments
        $this->addOption(
            'date', 
            null,
            InputOption::VALUE_OPTIONAL,
            'Mốc thời gian bắt đầu',
            ''
        );

        $this->addOption(
            'timezone',
            null,
            InputOption::VALUE_OPTIONAL,
            'Múi giờ địa phương (định danh), mặc định sử dụng GMT+7 cho Âm lịch Việt Nam',
            '+0700'
        );
        

        // Options
        $this->addOption(
            'quantity',
            null,
            InputOption::VALUE_OPTIONAL,
            'Số lượng kết quả đầu ra mong muốn',
            10
        );

        $this->addOption(
            'next',
            null,
            InputOption::VALUE_OPTIONAL,
            'Kết quả tiếp theo được lấy ở mốc thời gian trong tương lai (điểm kế tiếp)',
            1
        );

        $this->addOption(
            'output-type',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn xuất ra bảng dữ liệu (mặc định) hoặc cấu trúc mảng PHP.',
            1
        );
    }
}