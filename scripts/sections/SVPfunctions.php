<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;

function generateHeader($header, $reportData, $revisionNumber){
    $headerTable = $header->addTable('tsFooter');
    $headerTable->addRow();
    $headerTable->addCell(null)->addImage(
        'img/OTCsolutions_header.jpg',
        [
        'height' => Converter::inchToPixel(.5625),
        'wrappingStyle'=> 'square',
        'positioning' => 'relative',
        'posHorizontal' =>\PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_LEFT
        ]
    );
    $headerRtCell = $headerTable->addCell(Converter::inchToTwip(4.34));
    $headerRtCell->addText($reportData->reportType, 'fsHeader', 'psRightTight');
    $headerRtCell->addText($reportData->docNumber, 'fsHeader', 'psRightTight');
    $headerRtCell->addText($revisionNumber, 'fsHeader', 'psRightTight');
    return $header;
}

function goodNumberToString($number, $decimals = 0, $trailingZeros = FALSE){
    //Converts Number to string, with leading zero before "." and "," seperator for 1,000s
    //Adds leading zero's to number < 1
    // .125,2 -> "0.13"  / ".5",2,TRUE -> "0.50" / 1256 -> "1,256" / "1256,2" -> "1,256"
    if(strpos($number, ',')!== FALSE){$number = str_replace(",","",$number);}
    $num = floatval($number);
    $smallNum = ($num < 1 && $num > -1)? TRUE : FALSE;
    $string_num = number_format($num, $decimals);
    if(strpos($string_num, '.') && !$trailingZeros){
        $string_num = rtrim($string_num, "0.");
    }
    if($smallNum && strpos($string_num, ".") == 0){ //&& first char is ".")
     //add a zero to the left of the "."
        $string_num = "0".$string_num;
    }
    return $string_num;
}

function addTermCell($table, $term = '', $sub = ''){
    global $vAlignCell;
    
    $trTerm=$table->addCell(null, $vAlignCell)->addTextRun('psCenterTight');
    $trTerm->addText($term,'fsSize9');
    $trTerm->addText($sub, 'fsNormalSub');
    return $table;
}
