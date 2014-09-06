<?php


namespace Nikoms\FailLover\FileSystem\Csv;


use Nikoms\FailLover\TestCaseResult\ReaderInterface;
use Nikoms\FailLover\TestCaseResult\TestCase;

class CsvReader implements ReaderInterface
{

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var array
     */
    private $list;


    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return \Nikoms\FailLover\TestCaseResult\TestCase[]
     */
    public function getList()
    {
        if ($this->list !== null) {
            return $this->list;
        }

        if (!file_exists($this->fileName)) {
            return array();
        }

        $this->list = array();
        if (($handle = fopen($this->fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $this->list[] = new TestCase(
                    $data[Columns::CLASS_NAME],
                    $data[Columns::METHOD_NAME],
                    $data[Columns::DATA_NAME],
                    $data[Columns::DATA]
                );
            }
            fclose($handle);
        }

        return $this->list;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        $list = $this->getList();
        return empty($list);
    }


}
