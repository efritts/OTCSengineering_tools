<?php
require_once '../vendor/autoload.php';
require 'sections/SVPfunctions.php';
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Paper;

//The function will receive a JSON as an argument
//$JSONdata = file_get_contents('testing/setup1.json');  for testing create a local *.json file
$JSONdata = $_POST['json_string'];
  
$reportData = json_decode($JSONdata);
if($reportData->docRev < 10){$rNumber = "0".$reportData->docRev;}
else{$rNumber = $reportData->docRev;}
$revisionNumber="Rev ".$rNumber;

//Some common variables
$sealPressureStr = goodNumberToString($reportData->Well->minSealPressure); //min pressure to seal

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getCompatibility()->setOoxmlVersion(15);

$properties = $phpWord->getDocInfo();
$properties->setCreator($reportData->author);
$properties->setCompany($reportData->company);
$properties->setTitle($reportData->reportType);
$properties->setDescription($reportData->docDescription);
$properties->setLastModifiedBy($reportData->lastModifier);
$properties->setCreated(mktime(0, 0, 0, 3, 12, 2014));

require_once 'sections/wordStyles.php';

/*
 * BEGIN DOC CREATION
 */

$sectionTitlePg = $phpWord->addSection($styleSectionTitle);

//Add the coverpage image
$width = 525;
$height = 1302/1605*$width;
$sectionTitlePg->addImage(
    'img/drivenByRealExp.jpg',
    array(
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'marginTop'          => 0.75,
        'wrappingStyle'      => 'square',
        'width' => $width,//7.3" or 1605 pixels
        'height' => $height//5.92" or 1302 pixels
    )
);

//Add the coverpage Title, Rig, Client, Doc#, Rev, Release Date
$sectionTitlePg->addText($reportData->reportType, 'fsTitle', $styleParaTitle);
$sectionTitlePg->addText($reportData->reportSubType, 'fsSubTitle', $styleParaSubTitle);
$sectionTitlePg->addTextBreak(1);
$sectionTitlePg->addText($reportData->clientFullLegal, 'fsTitle', $styleParaTitle);
$sectionTitlePg->addText($reportData->Rig->name, 'fsSubTitle', $styleParaSubTitle);
$sectionTitlePg->addText($reportData->Well->name, 'fsSubTitle', $styleParaSubTitle);
$sectionTitlePg->addTextBreak(1);
$sectionTitlePg->addText($reportData->docNumber, 'fsSubTitle', $styleParaSubTitle);
$sectionTitlePg->addText($revisionNumber, 'fsSubTitle', $styleParaSubTitle);
$sectionTitlePg->addText('March 22, 2018', 'fsSubTitle', $styleParaSubTitle);

//add the Coverpage OTCSolution image
$sectionTitlePg->addImage(
    'img/OTCsolutions_cover.jpg',
    array(
        'height' => Converter::inchToPixel(.977),
        'wrappingStyle'      => 'square',
        'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_BOTTOM,
        'posHorizontal' =>\PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_LEFT,
        'marginLeft'       => .5,
        'marginBottom'        => 0
    )
);

//
//REVISION PAGE
$sectionRevPage=$phpWord->addSection($styleSectionDefault);
//HEADER
$header=$sectionRevPage->addHeader();
generateHeader($header, $reportData, $revisionNumber);

//FOOTER
$footer=$sectionRevPage->addFooter();
$footerTable = $footer->addTable('tsFooter');
$footerTable->addRow();
$footerTable->addCell(Converter::inchToTwip(3.03))->addText("www.otc-solutions.com", 'fsSize8', 'psLeftTight');
$footerTable->addCell(Converter::inchToTwip(4.15))->addText("PROPRIETARY/CONFIDENTIAL", 'fsSize8', 'psLeftTight');

//$footerTable->addCell(Converter::inchToTwip(.33))->addPreserveText("{PAGE}", "fsSize8", 'psRightTight');

//REVISION HISTORY
$sectionRevPage->addTextBreak(1, 'fsNormal');
$sectionRevPage->addText('REVISION HISTORY', 'fsH1', $styleParaRevs);
$tableRevHistory = $sectionRevPage->addTable('tsPlain');
$tableRevHistory->addRow($rsHeight);
$tableRevHistory->addCell(Converter::inchToTwip(.56), $vAlignCell)->addText("REV", 'fsSmallBold', 'psCenterTight');
$tableRevHistory->addCell(Converter::inchToTwip(.94), $vAlignCell)->addText("Date of Issue", 'fsSmallBold', 'psCenterTight');
$tableRevHistory->addCell(Converter::inchToTwip(2), $vAlignCell)->addText("Reason for Issue", 'fsSmallBold', 'psLeftTight');
$tableRevHistory->addCell(Converter::inchToTwip(1.17), $vAlignCell)->addText("Prepared", 'fsSmallBold', 'psCenterTight');
$tableRevHistory->addCell(Converter::inchToTwip(1.17), $vAlignCell)->addText("Checked", 'fsSmallBold', 'psCenterTight');
$tableRevHistory->addCell(Converter::inchToTwip(1.17), $vAlignCell)->addText("Approved", 'fsSmallBold', 'psCenterTight');
foreach($reportData->revisions as $revNokey => $value){
        $tableRevHistory->addRow($rsHeight);
        $tableRevHistory->addCell(null, $vAlignCell)->addText($revNokey, 'fsSize9', 'psCenterTight');
        $tableRevHistory->addCell(null, $vAlignCell)->addText($reportData->revisions->$revNokey->date, 'fsSize9', 'psCenterTight');
        $tableRevHistory->addCell(null, $vAlignCell)->addText($reportData->revisions->$revNokey->descShort, 'fsSize9', 'psLeftTight');
        $tableRevHistory->addCell(null, $vAlignCell)->addText($reportData->revisions->$revNokey->preparedBy, 'fsSize9', 'psCenterTight');
        $tableRevHistory->addCell(null, $vAlignCell)->addText($reportData->revisions->$revNokey->checkedBy, 'fsSize9', 'psCenterTight');
        $tableRevHistory->addCell(null, $vAlignCell)->addText($reportData->revisions->$revNokey->approvedBy, 'fsSize9', 'psCenterTight');
}
$tableRevHistory->addRow();
$tableRevHistory->addCell(Converter::inchToTwip(4.67),['gridSpan' => 4, 'valign' => 'center'])->addText("Initials:", 'fsSize9', 'psRightTight');
$tableRevHistory->addCell();
$tableRevHistory->addCell();

$sectionRevPage->addTextBreak(2, 'fsNormal');

//CHANGE DESCRIPTION
$sectionRevPage->addText('CHANGE DESCRIPTION', 'fsH1', $styleParaRevs);
$tableChangeDesc = $sectionRevPage->addTable('tsPlain');
$tableChangeDesc->addRow($rsHeight);
$tableChangeDesc->addCell(Converter::inchToTwip(.56), $vAlignCell)->addText("REV", 'fsSmallBold', 'psCenterTight');
$tableChangeDesc->addCell(Converter::inchToTwip(6.44), $vAlignCell)->addText("Change Description", 'fsSmallBold', 'psLeftTight');
foreach($reportData->revisions as $revNokey => $value){
        $tableChangeDesc->addRow($rsHeight);
        $tableChangeDesc->addCell(null,$vAlignCell)->addText($revNokey, 'fsSize9', 'psCenterTight');
        $tableChangeDesc->addCell(null,$vAlignCell)->addText($reportData->revisions->$revNokey->descLong, 'fsSize9', 'psLeftTight');
}

$sectionRevPage->addPageBreak();
$sectionRevPage->addText('TABLE OF CONTENTS', 'fsH1', $styleParaRevs);

//Main Section
$sectionMainContent=$phpWord->addSection($styleSectionDefault);
//HEADER
$headerMaincontent=$sectionMainContent->addHeader();
generateHeader($headerMaincontent, $reportData, $revisionNumber);


//FOOTER
$footerMain=$sectionMainContent->addFooter();
$footerMainTable = $footerMain->addTable('tsFooter');
$footerMainTable->addRow();
$footerMainTable->addCell(Converter::inchToTwip(3.03))->addText("www.otc-solutions.com", 'fsSize8', 'psLeftTight');
$footerMainTable->addCell(Converter::inchToTwip(4.15))->addText("PROPRIETARY/CONFIDENTIAL", 'fsSize8', 'psLeftTight');
$footerMainTable->addCell(Converter::inchToTwip(.33))->addPreserveText("{PAGE}", "fsSize8", 'psRightTight');

//Shear Calculation Letter
require_once 'sections/1-shearLetter.php';
$sectionMainContent->addPageBreak();

//Well Data & Equipment Information
$sectionMainContent->addTitle('Well Data and Equipment Information', 1);
if($reportData->Rig->location == "surface"){require_once 'sections/2-wdeiSurface.php';}
else{require_once 'sections/2-wdeiSubsea.php';}
$sectionMainContent->addPageBreak();

//Pipe Data Summary
require_once 'sections/3-pipeDataSummary.php';
$sectionMainContent->addPageBreak();

//Hydrostatic Effects
$sectionMainContent->addTitle('Hydrostatic Effects', 1);
require_once 'sections/4-hydrostaticEffects.php';
$sectionMainContent->addPageBreak();

//Pipe Detail Calculations
require_once 'sections/5-pipeDetailCalcs.php';

//References
require_once 'sections/6-references.php';
$sectionMainContent->addPageBreak();

//Appendix A
$sectionMainContent->addTitle('Appendix A - Shear Test Report', 1);

//Create the Table of Contents after all the pages have been created.
$sectionRevPage->addTOC();
// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('output/helloWorld.docx');
?>