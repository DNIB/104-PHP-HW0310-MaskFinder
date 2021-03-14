<?php

/**
 * This php file is designed for main.php
 * php version 7.4.10
 * 
 * @category PHP@7.4.10
 * @package  Climate
 * @author   DNIB[Tamako] <qazs0205@gamil.com>
 * @license  DNIB[Tamako] github.com/DNIB
 * @link     github.com/DNIB/104-PHP-HW0310-MaskFinder
 */

namespace cyan\data;

require_once 'vendor/autoload.php';

/**
 * This Class is used to deal with the data of mask.
 * 
 * @category Class
 * @package  Climate
 * @author   DNIB[Tamako] <qazs0205@gamil.com>
 * @license  DNIB[Tamako] github.com/DNIB
 * @link     github.com/DNIB/104-PHP-HW0310-MaskFinder
 */

class MaskInfoClass
{
    private $_data;
    private $_rFile;
    private $_climate;

    /**
     * This function is used to initial the class
     * and execute the others function
     * 
     * @return bool
     */
    public function init()
    {        
        $this->_climate = new \League\CLImate\CLImate;

        $isDounloadSuccess = $this->downloadFile();

        if ($isDounloadSuccess) {
            $isOpenFileSuccess = $this->openFile();

            if ($isOpenFileSuccess) {
                $this->readFile();
                return true;
            } else { // Open File Failed
                return false;
            }
        } else { // Download Failed
            return false;
        }
    }

    /**
     * This function is used to open file
     * 
     * @return bool
     */
    function openFile()
    {
        $FILENAME = "maskdata.csv";

        $this->_rFile = fopen($FILENAME, 'r');

        if ($this->_rFile != false) {
            echo "Open File Successed\n";
            return true;
        } else {
            echo "Error: Read File Failed\n";
            return false;
        }
    }

    /**
     * This function is used to read file
     * 
     * @return bool
     */
    function readFile()
    {
        $dic = array(
            'Code',
            'Name',
            'Addr',
            'Numb',
            'Adul',
            'Chil',
            'Time'
        );
        
        $isFirstDisard = false;
        $this->_data = array();

        while (($dataOfLine = fgetcsv($this->_rFile, 1000)) != false) {
            if ($isFirstDisard === false) {
                $info = array();
                for ($i=0; $i<7; $i++) {
                    $info[$dic[$i]] = $dataOfLine[$i];
                }
                $this->_data[] = $info;
            } else {
                continue;
            }
        }

        return true;
    }

    /**
     * This function is used to download file from Internet
     * 
     * @return bool
     */
    public function downloadFile()
    {
        $URL = 'https://data.nhi.gov.tw/resource/mask/maskdata.csv';

        $file_name = basename($URL); 
        
        if (file_put_contents($file_name, file_get_contents($URL))) { 
            echo "File downloaded successfully\n"; 
            return true;
        } else { 
            echo "File downloading failed.\n"; 
            return false;
        } 
    }

    /**
     * This function is used to allow user input string
     * and search the string from the address of read-data
     * 
     * @return bool
     */
    public function search()
    {
        $keyword = readline("請輸入搜尋縣市： ");
        $keyLen = strlen($keyword);

        $searchResult = array();
        foreach ($this->_data as $info) {
            $addr = $info['Addr'];
            $addrKey = substr($addr, 0, $keyLen);
            $outputString = "";

            if ($addrKey === $keyword) {
                $searchResult[] = array(
                    "醫事機構名稱" => $info['Name'],
                    "醫事機構地址" => $info['Addr'],
                    "成人口罩剩餘數" => $info['Adul'],
                    "數量更新時間" => $info['Time']
                );
            }
        }
        $this->printResult($searchResult);

        return true;
    }

    /**
     * This function is used to print the result of searching
     * 
     * @param array $searchResult The Result of searching stored in array
     * 
     * @return bool
     */
    public function printResult($searchResult)
    {
        if (count($searchResult) == 0) {
            $this->_climate->out("搜尋結果為空");
            return 0;
        }

        $KEY = "成人口罩剩餘數";
        $itemToSort = array_column($searchResult, $KEY);

        array_multisort($itemToSort, SORT_DESC, $searchResult);

        $this->_climate->table($searchResult);

        return true;
    }
}
