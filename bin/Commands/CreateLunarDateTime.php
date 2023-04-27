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
use VanTran\LunarCalendar\Lunar\LunarDateTimeFormatter;
use VanTran\LunarCalendar\Lunar\LunarParser;
use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendarCli\Traits\JulianToDateTime;

#[AsCommand(
    name: 'create:datetime-infomation',
    description: 'Tạo một mốc ngày Âm lịch và các thông tin liên quan của nó',
    hidden: false,
    aliases: []
)]

class CreateLunarDateTime extends Command
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
        $formatter = $this->getHelper('formatter');

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

        $output->writeln('');

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
        $table->setHeaderTitle('Thông tin thời gian');
        $table->setHeaders([
            'Âm lịch',
            'Dương lịch',
            'Múi giờ',
            'Bù UTC',
            'Unix',
            'MJD',
        ]);

        $phases = [$this->getList($input)];
        $phasesTable = [];

        $total = count($phases);

        foreach ($phases as $index => $phase) {
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
        $this->setHelp($this->getDescription());

        // Input argumments
        $this->addArgument(
            'datetime', 
            InputArgument::OPTIONAL, 
            'Thời gian Âm lịch',
            'now'
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
            'd/m/Y H:i:s'
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
        $lunar = new LunarDateTime($input->getArgument('datetime'), $timezone);
        $date = $lunar->toDateTime();

        return [
            'lunar' => $lunar->format($input->getOption('date-format')),
            'gregory' => $date->format($input->getOption('date-format')),
            'timezone' => $timezone->getName(),
            'offset' => $lunar->getOffset(),
            'timestamp' => $date->getTimestamp(),
            'jd' => $lunar->getJd()
        ];
    }
}