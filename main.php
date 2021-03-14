<?php

/**
 * This php file is designed for using Class MaskInfoClass
 * php version 7.4.10
 * 
 * @category PHP@7.4.10
 * @package  None
 * @author   DNIB[Tamako] <qazs0205@gamil.com>
 * @license  DNIB[Tamako] github.com/DNIB
 * @link     github.com/DNIB/104-PHP-HW0310-MaskFinder
 */

require_once 'vendor/autoload.php';
require_once 'MaskInfoClass.php';

/**
 * This function is used to initial function
 * 
 * @param Class $body Object to used
 * 
 * @return bool
 */
function searchAndCheck($body)
{
    $isContinue = true;
    while ($isContinue) {
        $body->search();

        $isContinue = checkContinue();

        if (!$isContinue) {
            deleteCsv();
        }
    }
    return true;
}

/**
 * This function is used to check if program continue or not
 * 
 * @return bool
 */
function checkContinue()
{
    $s = readline("繼續搜尋？ (Y/n) ");
    $s = strtolower($s);

    if (($s === "y") or ($s === "n")) {
        return ($s === "y") ? true : false;
    } else {
        echo "---輸入錯誤，請重新輸入---\n";
        return checkContinue();
    }
}

/**
 * This function is used to delete file downloaded
 * 
 * @return bool
 */
function deleteCsv()
{
    echo "結束程式\n";
    $isDeleteSuccess = unlink("maskdata.csv");
    
    if ($isDeleteSuccess) {
        echo "---Message: maskdata.csv Delete Success---\n";
    } else {
        echo "---Message: maskdata.csv Delete Fail---\n";
    }
}

use cyan\data as cd;

$body = new cd\MaskInfoClass();
$isInitSuccess = $body->init();

if ($isInitSuccess) {
    searchAndCheck($body);
} else {
    echo "---Init Failed---\n";
}

return 0;

