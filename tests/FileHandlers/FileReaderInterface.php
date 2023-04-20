<?php namespace VanTran\LunarCalendar\Tests\FileHandlers;

interface FileReaderInterface
{
    /**
     * Trả về đường dẫn thư mục gốc lưu trữ tệp (không bao gồm các thư mục con bổ sung)
     * @return string 
     */
    public function getBaseDirPath(): string;

    /**
     * Trả về tên tập tin không bao gồm định dạng
     * @return string 
     */
    public function getFileName(): string;

    /**
     * Trả về định dạng tập tin
     * @return string 
     */
    public function getFileExtension(): string;

    /**
     * Trả về đường dẫn đầy đủ trỏ đến tệp
     * @return string 
     */
    public function getFilePath(): string;

    /**
     * Trả về đường dẫn thư mục lưu trữ tệp
     * @return string 
     */
    public function getDirPath(): string;

    /**
     * Xác định tập tin có tồn tại hay không
     * @return bool 
     */
    public function isFileExists(): bool;

    /**
     * Trả về nội dung tệp
     * @return mixed 
     */
    public function getFileContent(): mixed;
}