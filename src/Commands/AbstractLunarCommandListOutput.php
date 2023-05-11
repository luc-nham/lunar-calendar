<?php namespace VanTran\LunarCalendar\Commands;

use DivisionByZeroError;
use ArithmeticError;
use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lớp trừu tượng hỗ trợ các lệnh tạo, kết xuất danh sách dữ liệu ở cấp độ đơn giản
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Commands
 */
abstract class AbstractLunarCommandListOutput extends Command
{
    /**
     * @var bool Phân tách các hàng bảng đầu ra
     */
    protected $tableSeparated = true;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected $formatter;

    /**
     * Trả về mảng chứa các tiêu đề cột bảng thông tin
     * 
     * @return array 
     */
    abstract protected function getInfoTableHeader(): array;

    /**
     * Trả về mảng chứa các thông tin hỗ trợ
     * 
     * @return array 
     */
    abstract protected function getInformationData(): array;

    /**
     * Trả về mảng chứa các tiêu đề bảng dữ liệu
     * 
     * @return array 
     */
    abstract protected function getDataTableHeader(): array;

    /**
     * Trả về mảng danh sách dữ liệu
     * 
     * @return array 
     */
    abstract protected function getListData(): array;

    /**
     * Trả về phân loại kết xuất đầu ra, hỗ trợ (1) với định dạng bảng. (2) cho định dạng mảng PHP.
     * @return int 
     */
    abstract protected function getOuputType(): int;

    /**
     * Các lớp mở rộng có thể ghi đè phương thức này để thực hiện việc xác thực đầu vào
     * @return int 
     */
    protected function validate(): int
    {
        return 0;
    }

    /**
     * Thêm định dạng phân tách các hàng trong bảng
     * 
     * @param mixed $data 
     * @return void 
     */
    private function insertTableSeperator(&$data): void
    {
        $modifiedData = [];
        $total = count($data);

        foreach ($data as $index => $item) {
            $modifiedData[] = $item;

            if ($index != $total - 1) {
                $modifiedData[] = new TableSeparator();
            }
        }

        $data = $modifiedData;
    }

    /**
     * Kết xuất bảng thông tin lệnh
     * 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws DivisionByZeroError 
     * @throws ArithmeticError 
     */
    protected function renderInfoTable(): void
    {
        if (empty($data = $this->getInformationData())) {
            return;
        }

        if ($this->tableSeparated) {
            $this->insertTableSeperator($data);
        }

        $header = $this->getInfoTableHeader();
        $table = new Table($this->output);

        if (!empty($header)) {
            $table->setHeaders($header);
        }
        
        $table->setHeaderTitle("Cấu hình lệnh");
        $table->setRows($data);
        $table->render();

        $this->output->writeln('');
    }

    /**
     * Tiều đề bảng dữ liệu
     * 
     * @return string 
     */
    protected function getDataTableHeaderTitle(): string
    {
        return 'Danh sách dữ liệu';
    }

    /**
     * Kết xuất bảng dữ liệu đầu ra
     * 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws DivisionByZeroError 
     * @throws ArithmeticError 
     */
    protected function renderDataTable(): void
    {
        $data = $this->getListData();

        if (empty($data)) {
            return;
        }

        if ($this->tableSeparated) {
            $this->insertTableSeperator($data);
        }

        $header = $this->getDataTableHeader();
        $table = new Table($this->output);

        if (!empty($header)) {
            $table->setHeaders($header);
        }

        $table->setHeaderTitle($this->getDataTableHeaderTitle());
        $table->setRows($data);
        $table->render();
    }

    /**
     * Kết xuất đầu ra dưới dạng mảng PHP
     * 
     * @return void 
     * @throws ExportException 
     */
    protected function renderPhpArray(): void
    {
        $data = $this->getListData();
        $content = 'return ' . VarExporter::export($data) . ';';
        
        $this->output->writeln('<comment>' .$content . '</comment>');
        $this->output->writeln('');
    }

    /**
     * Chuẩn bị dữ liệu trước khi thực thi lệnh
     * 
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return void 
     * @throws LogicException 
     * @throws InvalidArgumentException 
     */
    private function prepareExecuting(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
        $this->formatter = $this->getHelper('formatter');
    }

    protected function printErrorMasage($message): void
    {
        $this->output->writeln(
            $this->formatter->formatBlock($message, 'error', true)
        );

        $this->output->writeln('');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->prepareExecuting($input, $output);
        $this->renderInfoTable();

        $validationResult = $this->validate();

        if ($validationResult != 0) {
            return $validationResult;
        }

        switch ($this->getOuputType()) {
            case 1:
                $this->renderDataTable();
                break;

            case 2: 
                $this->renderPhpArray();
                break;

            default:
                $this->printErrorMasage('Lỗi. Tùy chọn định dạng xuất ra không hợp lệ.');
                return Command::INVALID;
        }

        $this->output->writeln('');

        return Command::SUCCESS;
    }
}
