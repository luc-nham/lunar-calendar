<?php namespace VanTran\LunarCalendar\Tests\FileHandlers;

/**
 * Trình quản lý tệp dữ liệu được cung cấp cho các bài test
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\PhpNhamCalendar\Tests\FileHandlers
 */
class ProvidingFileReader extends BaseFileReader
{
    protected $subdir = [
        'data-providers'
    ];

    public function __construct(protected string $fileName, protected string $fileExtension = 'json')
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getFileName(): string 
    { 
        return $this->fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension(): string 
    { 
        return $this->fileExtension;
    }
}