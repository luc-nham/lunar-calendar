<?php namespace VanTran\LunarCalendar\Tests\FileHandlers;

abstract class BaseFileReader implements FileReaderInterface
{
    /**
     * Danh sách phân cấp theo thứ tự các thư mục con lưu trữ dữ liệu
     * @var string[]
     */
    protected $subdir = [];

    /**
     * Thêm thư mục con
     * 
     * @param string|array $dir
     * @return void 
     */
    public function addSubdir(string|array $dir): void
    {
        if (is_string($dir) && $dir !== '') {
            array_push($this->subdir, $dir);
        }
    }

    /**
     * Trả về mảng thứ tự các thư mục con lưu trữ dữ liệu
     * @return array 
     */
    public function getSubdir(): array
    {
        return $this->subdir;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilePath(): string
    {
        return $this->getDirPath() . '/' . $this->getFileName() . '.' . $this->getFileExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function isFileExists(): bool
    {
        return file_exists($this->getFilePath());
    }

    /**
     * {@inheritdoc}
     */
    public function getFileContent(): mixed
    {
        return file_get_contents($this->getFilePath());
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDirPath(): string
    {
        return dirname(__DIR__, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function getDirPath(): string
    {
        $subdir = $this->getSubdir();
        $dirPath = $this->getBaseDirPath();

        if (!empty($subdir)) {
            $dirPath .= '/' . implode('/', $subdir);
        }

        return $dirPath;
    }
}
