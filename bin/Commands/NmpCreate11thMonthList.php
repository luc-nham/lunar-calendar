<?php namespace VanTran\LunarCalendarCli\Commands;

use ArithmeticError;
use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use DateTimeZone;
use DivisionByZeroError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use VanTran\LunarCalendar\Lunar\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Lunar\LunarParser;
use VanTran\LunarCalendarCli\Traits\JulianToDateTime;

#[AsCommand(
    name: 'nmp:create-11th-month-list',
    description: 'Tạo danh sách các điểm Trăng mới (Sóc) tháng 11 Âm lịch.',
    hidden: false,
    aliases: []
)]

class NmpCreate11thMonthList extends Command
{
    use JulianToDateTime;

    /**
     * {@inheritdoc}
     * 
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return int 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->renderInfoTable($input, $output);
        $output->writeln('');
        
        $formatter = $this->getHelper('formatter');

        $startYear = $input->getArgument('start-year');
        $endYear = $input->getArgument('start-year');

        if (
            $startYear < 1900 ||
            $endYear < 1900 ||
            $endYear > 2100 ||
            $endYear < $startYear
        ) {
            $message = [
                'Lỗi. Hỗ trợ năm Âm lịch từ 1900 đến 2100; năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu',
            ];

            $output->writeln($formatter->formatBlock($message, 'error', true));
            $output->writeln('');
            
            return Command::INVALID;
        }

        switch ($input->getOption('output-type')) {
            case 1:
                $this->renderDataTable($input, $output);
                break;

            case 2: 
                $this->renderPhpArray($input, $output);
                break;

            default:
                $message = [
                    'Lỗi. Tùy chọn định dạng xuất ra không hợp lệ.',
                ];
                $output->writeln($formatter->formatBlock($message, 'error', true));
                $output->writeln('');

                return Command::INVALID;
        }

        return Command::SUCCESS;
    }

    /**
     * Kết xuất kết quả đầu ra dưới dạng bảng
     * 
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws DivisionByZeroError 
     * @throws ArithmeticError 
     */
    protected function renderDataTable(InputInterface $input, OutputInterface $output): void
    {
        $table = new Table($output);
        $table->setHeaderTitle('Danh sách điểm Sóc tháng 11 Âm lịch');
        $table->setHeaders([
            '#',
            'Năm Âm lịch',
            'Dương lịch (Giờ địa phương)',
            'MJD (UTC)',
        ]);

        $phases = $this->getList($input);
        $phasesTable = [];

        $total = count($phases);

        foreach ($phases as $index => $phase) {
            array_unshift($phase, $index + 1);
            $phasesTable[] = $phase;

            if ($index != $total - 1) {
                $phasesTable[] = new TableSeparator();
            }
        }

        $table->setRows($phasesTable);
        $table->render();
    }

    /**
     * Kết xuất đầu ra dưới dạng mảng PHP
     * 
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws ExportException 
     */
    protected function renderPhpArray(InputInterface $input, OutputInterface $output): void
    {
        $data = $this->getList($input);
        $content = 'return ' . VarExporter::export($data) . ';';
        
        $output->writeln('<comment>' .$content . '</comment>');
        $output->writeln('');
    }

    /**
     * {@inheritdoc}
     * @return void 
     */
    protected function configure()
    {
        $content = <<<HELP
        Lệnh này cho phép bạn tạo ra một danh sách các điểm Sóc của tháng 11 tương ứng với năm Âm lịch.
        HELP;

        $this->setHelp($content);

        // Input argumments
        $this->addArgument(
            'start-year', 
            InputArgument::REQUIRED, 
            'Năm Âm lịch bắt đầu',
        );

        $this->addArgument(
            'end-year', 
            InputArgument::REQUIRED, 
            'Năm Âm lịch kết thúc',
        );

        $this->addArgument(
            'timezone',
            InputArgument::OPTIONAL,
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
            'd-m-Y H:i:s P'
        );

        $this->addOption(
            'midnight',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn các giá trị thời gian đầu ra ở điểm nửa đêm',
            true
        );
    }

    /**
     * Trả về danh sách kết quả
     * 
     * @param InputInterface $input 
     * @return array 
     * @throws InvalidArgumentException 
     */
    protected function getList(InputInterface $input): array
    {
        $timezone = new DateTimeZone($input->getArgument('timezone'));
        $year = $input->getArgument('start-year');
        $endYear = $input->getArgument('end-year');
        $data = [];

        do {
            $day = rand(1, 29);
            $month = rand(1, 12);

            $parser = new LunarParser("$day/$month/$year", $timezone);
            $corrector = new LunarDateTimeCorrector($parser);
            $nm = $corrector->get11thNewMoon();

            $mjd = ($input->getOption('midnight'))
                ? $nm->getMidnightJd()
                : $nm->getJd();

            $outputDate = $this->jdToDateTime($mjd, $timezone);

            array_push($data, [
                'lunar_year' => $year,
                'date'  => $outputDate->format($input->getOption('date-format')),
                'jd' => $mjd
            ]);

            $year ++;
        } while ($year <= $endYear);

        return $data;
    }

    /**
     * Kết xuất bảng thông tin
     * 
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws DivisionByZeroError 
     * @throws ArithmeticError 
     */
    protected function renderInfoTable(InputInterface $input ,OutputInterface $output): void
    {
        $table = new Table($output);
        $table->setHeaderTitle('Cấu hình lệnh');
        $table->setHeaders([
            'Thông tin',
            'Giá trị'
        ]);

        $info = [
            [
                'Mô tả lệnh',
                $this->getDescription()
            ],
            new TableSeparator(),
            [
                'Năm Âm lịch bắt đầu',
                $input->getArgument('start-year')
            ],
            new TableSeparator(),
            [
                'Năm Âm lịch kết thúc',
                $input->getArgument('end-year')
            ],
            new TableSeparator(),
            [
                'Múi giờ địa phương',
                $input->getArgument('timezone')
            ],
            new TableSeparator(),
            [
                'Định dạng thời gian',
                $input->getOption('date-format')
            ],
            new TableSeparator(),
            [
                'Định dạng đầu ra',
                (function($input) {
                    switch ($input->getOption('output-type')) {
                        case 1:
                            $type = 'Bảng dữ liệu';
                            break;

                        case 2:
                            $type = 'Mảng PHP';
                             break;

                        default:
                            $type = 'Không được hỗ trợ';
                    }

                    return $type;
                })($input)
            ],
            new TableSeparator(),
            [
                'Phân loại thời gian',
                ($input->getOption('midnight'))
                    ? 'Lúc nửa đêm (00:00) của ngày chứa điểm Sóc'
                    : 'Điểm chính xác bắt đầu pha Trăng mới'
            ],
        ];

        $table->setRows($info);
        $table->render();
    }
}