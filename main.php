<?php

require_once('vendor/autoload.php');
require_once('MaskInfoClass.php');

$climate = new League\CLImate\CLImate;

use cyan\data as cd;

$body = new cd\MaskInfoClass();
$body->init();

while(true)
{
    $msg = $body->search();

    if($msg === false) {
        echo "---Init Failed---\n";
        break;
    }

    while(true)
    {
        $s = readline("繼續搜尋？ (Y/n) ");
        $s = strtolower($s);

        if (($s === "y") or ($s === "n")) {
            break;
        }
        else
        {
            echo "---輸入錯誤，請重新輸入---\n";
        }
    }

    if($s === "n") {
        echo "結束程式\n";

        $msg = unlink("maskdata.csv");
        
        if ($msg === true) 
        {
            echo "---Message: maskdata.csv Delete Success---";
        }
        else 
        {
            echo "---Message: maskdata.csv Delete Fail---";
        }

        break;
    }
}
return 0;

