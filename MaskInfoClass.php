<?php

namespace cyan\data;

require_once('vendor/autoload.php');

class MaskInfoClass
{
    private $data;
    private $rFile;
    private $dic;
    private $climate;

    public function init ()
    {        
        $FILENAME = "maskdata.csv";

        $this->climate = new \League\CLImate\CLImate;

        $this->downloadFile();

        $this->rFile = fopen($FILENAME, 'r');
        if($this->rFile == false)
        {
            echo "\n\n---File of Mask Infomation Does not Exist.---\n\n";
            echo "Trying Downloading File...\n";

            $msg = $this->downloadFile();
            
            if($msg == false)
            {
                echo "\nError: Download Fail\n";
                echo "Program Exit";
                return false;
            }
            else
            {
                echo "\nDounload Success\n";
                $this->rFile = fopen($FILENAME, 'r');
            }
        } else 
        {
            echo "Read File Success.\n";
        }

        $dic = array(
            'Code',
            'Name',
            'Addr',
            'Numb',
            'Adul',
            'Chil',
            'Time'
        );
        
        $this->data = array();
        while (($dataOfLine = fgetcsv($this->rFile, 1000)) != false)
        {
            $info = array();
            for($i=0; $i<7; $i++)
            {
                $info[$dic[$i]] = $dataOfLine[$i];
            }
            //print_r($info);
            $this->data[] = $info;
        }
        //print_r($this->data);
    }

    public function downloadFile()
    {
        $URL = 'https://data.nhi.gov.tw/Datasets/Download.ashx?rid=A21030000I-D50001-001&l=https://data.nhi.gov.tw/resource/mask/maskdata.csv'; 

        $file_name = basename($URL); 
        
        if(file_put_contents( $file_name,file_get_contents($URL))) { 
            echo "File downloaded successfully\n"; 
            return true;
        } 
        else { 
            echo "File downloading failed.\n"; 
            return false;
        } 
    }

    public function search()
    {
        $keyword = readline("請輸入搜尋縣市： ");
        $keyLen = strlen($keyword);

        $searchResult = array();
        foreach($this->data as $info)
        {
            $addr = $info['Addr'];
            $addrKey = substr($addr, 0, $keyLen);
            $outputString = "";

            if ($addrKey === $keyword)
            {
                $searchResult[] = array(
                    "醫事機構名稱" => $info['Name'],
                    "醫事機構地址" => $info['Addr'],
                    "成人口罩剩餘數" => $info['Adul'],
                    "數量更新時間" => $info['Time']
                );
            }
        }
        
        if (count($searchResult) == 0) {
            $this->climate->out("搜尋結果為空");
            return 0;
        }

        $KEY = "成人口罩剩餘數";
        $itemToSort = array_column($searchResult, $KEY);

        array_multisort($itemToSort, SORT_DESC, $searchResult);

        $this->climate->table($searchResult);

        return 0;
    }
}