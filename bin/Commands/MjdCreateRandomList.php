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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;

#[AsCommand(
    name: 'mjd:create-random-list',
    description: 'Tạo danh sách ngẫu nhiên các mốc ngày Julian sửa đổi (MJD)',
    hidden: false,
    aliases: []
)]

class MjdCreateRandomList extends Command
{
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
        $table->setHeaderTitle('Danh sách điểm mốc MJD');
        $table->setHeaders([
            '#',
            'Ngày',
            'Giờ',
            'Múi giờ',
            'MJD hiện tại',
            'MJD nửa đêm',
            'Bù UTC',
        ]);

        $data = $this->getDataList($input);
        $dataSort = [];

        $total = count($data);

        foreach ($data as $index => $item) {
            array_unshift($item, $index + 1);
            $dataSort[] = $item;

            if ($index != $total - 1) {
                $dataSort[] = new TableSeparator();
            }
        }

        $table->setRows($dataSort);
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
        $data = $this->getDataList($input);
        $content = 'return ' . VarExporter::export($data) . ';';

        $output->writeln('<info>' . $content . '</info>');
    }

    /**
     * {@inheritdoc}
     * @return void 
     */
    protected function configure()
    {
        $this->setHelp($this->getDescription());

        // Options
        $this->addOption(
            'timezone',
            null,
            InputOption::VALUE_OPTIONAL,
            'Múi giờ địa phương tùy chỉnh, mặc định GMT+7',
            '+0700'
        );

        $this->addOption(
            'quantity',
            null,
            InputOption::VALUE_OPTIONAL,
            'Số lượng kết quả đầu ra mong muốn',
            10
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
     * Trả về danh sách mốc MJD
     * 
     * @param InputInterface $input 
     * @return array 
     * @throws InvalidArgumentException 
     */
    protected function getDataList(InputInterface $input): array
    {
        $timezone = new DateTimeZone($input->getOption('timezone'));
        
        $date = new DateTime('now', $timezone);

        $data = [];

        for ($i = 1; $i <= $input->getOption('quantity'); $i ++) 
        {
            $ts = rand(-2208988800, 2114380800); // Từ 1900 - 2037
            $date->setTimestamp($ts);
            $mjd = new DateTimeToMjd($date);

            array_push($data, [
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i:s'),
                'timezone' => $timezone->getName(),
                'jd' => $mjd->getJd(),
                'midnight_jd' => $mjd->getMidnightJd(),
                'utc_offset' => $mjd->getOffset(),
            ]);
        }

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
                'Múi giờ địa phương',
                $input->getOption('timezone')
            ],
            new TableSeparator(),
            [
                'Số lượng đầu ra',
                $input->getOption('quantity') . ' kết quả'
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