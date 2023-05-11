<?php namespace VanTran\LunarCalendar\Commands;

use DateTimeZone;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

#[AsCommand(
    name: 'create:lunar-datetime',
    description: 'Tạo thời gian Âm lịch',
    hidden: false,
    aliases: [
        'make-lunar'
    ]
)]

class CreateLunarDateTime extends AbstractLunarCommandListOutput
{
    /**
     * @inheritdoc
     */
    protected function getInfoTableHeader(): array 
    { 
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getInformationData(): array 
    { 
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getDataTableHeader(): array 
    { 
        return [
            'Thông tin',
            'Giá trị'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getListData(): array 
    { 
        $timezone = new DateTimeZone($this->input->getOption('timezone'));
        $format = $this->input->getOption('date-format');
        $lunar = new LunarDateTime(
            $this->input->getOption('date'),
            $timezone,
            $this->input->getOption('input-type')
        );
        $gregorian = $lunar->toDateTime();
        $se = new LunarSexagenary($lunar);

        return [
            [
                'Âm lịch',
                $lunar->format($format)
            ],
            [
                'Dương lịch',
                $gregorian->format($format)
            ],
            [
                'Unix',
                $gregorian->getTimestamp()
            ],
            [
                'Số ngày Julian',
                $lunar->getJd()
            ],
            [
                'Can Chi',
                $se->format('Ngày D+, tháng M+, năm Y+, giờ H+')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getOuputType(): int 
    { 
        //return $this->input->getOption('output-type');
        return 1;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        //$this->setHelp($this->getDescription());

        $this->addOption(
            'date',
            null,
            InputOption::VALUE_OPTIONAL,
            'Thời gian đầu vào, Âm hoặc Dương lịch',
            'now'
        );

        $this->addOption(
            'input-type',
            null,
            InputOption::VALUE_OPTIONAL,
            'Loại đầu vào (1) Âm lịch, (2) Dương lịch.',
            1
        );

        $this->addOption(
            'timezone',
            null,
            InputOption::VALUE_OPTIONAL,
            'Múi giờ địa phương, mặc định sử dụng GMT+7 cho Âm lịch Việt Nam.',
            '+0700'
        );

        // $this->addOption(
        //     'output-type',
        //     null,
        //     InputOption::VALUE_OPTIONAL,
        //     'Tùy chọn xuất ra bảng dữ liệu (mặc định) hoặc cấu trúc mảng PHP.',
        //     1
        // );

        $this->addOption(
            'date-format',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn định dạng thời gian đầu ra',
            'd/m/Y H:i:s P'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDataTableHeaderTitle(): string
    {
        return '';
    }
}