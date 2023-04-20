<?php namespace VanTran\LunarCalendar\Tests\FileHandlers;

use Exception;
use Iterator;
use VanTran\LunarCalendar\Tests\FileHandlers\ProvidingFileReader;

class JsonFileIterator implements Iterator
{
    protected $data = [];
    protected $key = 0;

    public function __construct(protected ProvidingFileReader $fileReader)
    {
        if ($this->fileReader->isFileExists()) {
            $this->data = json_decode($this->fileReader->getFileContent(), true);
        } else {
            throw new Exception("Error. The data providing file was not exists.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed 
    { 
        return $this->data[$this->key()];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void 
    { 
        $this->key ++;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): mixed 
    { 
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool 
    { 
        return isset($this->data[$this->key()]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void 
    { 
        $this->key = 0;
    }
    
}