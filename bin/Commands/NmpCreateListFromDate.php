<?php namespace VanTran\LunarCalendarCli\Commands;

use ArithmeticError;
use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use DateTime;
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
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;
use VanTran\LunarCalendar\MoonPhases\MjdToNewMoonPhase;
use VanTran\LunarCalendarCli\Traits\JulianToDateTime;

#[AsCommand(
    name: 'nmp:create-list-from-date',
    description: 'Tạo danh sách các điểm Trăng mới (Sóc) từ một mốc thời gian dương lịch',
    hidden: false,
    aliases: []
)]

class NmpCreateListFromDate extends Command
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

        if ($input->getOption('quantity') <= 0) {
            $output->writeln('Tùy chọn số lượng kết quả đầu ra tối thiểu là 1.');
            
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
                $output->writeln("Tùy chọn định dạng xuất ra không hợp lệ.");
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
        $table->setHeaderTitle('Danh sách điểm Sóc');
        $table->setHeaders([
            '#',
            'Ngày',
            'Giờ',
            'Múi giờ',
            'MJD (UTC)',
            'Bù UTC',
            'Chu kỳ Trăng'
        ]);

        $phases = $this->getNewMoonPhase($input);
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
        $data = $this->getNewMoonPhase($input);
        $content = 'return ' . VarExporter::export($data) . ';';

        $output->writeln('<info>' . $content . '</info>');
    }

    /**
     * {@inheritdoc}
     * @return void 
     */
    protected function configure()
    {
        $content = <<<HELP
        Lệnh này cho phép bạn tạo ra một danh sách các điểm của Pha trăng mới (điểm Sóc) từ một mốc thời gian dương
        lịch đầu vào.
        HELP;

        $this->setHelp($content);

        // Input argumments
        $this->addArgument(
            'date', 
            InputArgument::OPTIONAL, 
            'Mốc thời gian bắt đầu',
            ''
        );

        $this->addArgument(
            'timezone',
            InputArgument::OPTIONAL,
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
            true
        );

        $this->addOption(
            'output-type',
            null,
            InputOption::VALUE_OPTIONAL,
            'Tùy chọn xuất ra bảng dữ liệu (mặc định) hoặc cấu trúc mảng PHP.',
            1
        );
    }

    /**
     * Trả về danh sách điểm Sóc
     * 
     * @param InputInterface $input 
     * @return array 
     * @throws InvalidArgumentException 
     */
    protected function getNewMoonPhase(InputInterface $input): array
    {
        $timezone = new DateTimeZone($input->getArgument('timezone'));
        $date = new DateTime($input->getArgument('date'), $timezone);
        $mjd = new DateTimeToMjd($date);
        $newMoon = new MjdToNewMoonPhase($mjd);

        $phases = [];

        for ($i = 1; $i <= $input->getOption('quantity'); $i ++) 
        {
            $outputDate = $this->jdToDateTime($newMoon->getJd(), $timezone);

            array_push($phases, [
                'date' => $outputDate->format('Y-m-d'),
                'time' => $outputDate->format('H:i:s'),
                'timezone' => $input->getArgument('timezone'),
                'jd' => $newMoon->getJd(),
                'utc_offset' => $newMoon->getOffset(),
                'moon_cycles' => $newMoon->getTotalCycles(),
            ]);

            $newMoon = ($input->getOption('next'))
                ? $newMoon->add(1)
                : $newMoon->subtract(1);
        }

        return $phases;
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
                'Lệnh tạo danh sách các điểm của Pha trăng mới từ một mốc dương lịch cho trước.'
            ],
            new TableSeparator(),
            [
                'Mốc dương lịch đầu vào',
                (function (InputInterface $input) {
                    if ($input->getArgument('date')) {
                        return $input->getArgument('date');
                    } else {
                        $date = new DateTime('now', new DateTimeZone($input->getArgument('timezone')));
                        return $date->format('Y-m-d H:i:s P') . ' | (hiện tại)';
                    }
                })($input)
            ],
            new TableSeparator(),
            [
                'Số lượng đầu ra',
                $input->getOption('quantity') . ' kết quả'
            ],
            new TableSeparator(),
            [
                'Kiểu tính toán',
                ($input->getOption('next')) ? 'Tìm điểm kế tiếp' : 'Tìm điểm trước đó'
            ],
            new TableSeparator(),
            [
                'Định dạng đầu ra',
                ($input->getOption('output-type') == 1) ? 'Bảng dữ liệu' : 'Mảng PHP'
            ]
        ];

        $table->setRows($info);
        $table->render();
    }
}