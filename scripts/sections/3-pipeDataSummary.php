<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;

function addPipeToSummaryTable($table, $pipeValues =[],$grey = FALSE, $test = FALSE){
	global $rsHeight;
        $maxCalcShear = goodNumberToString($pipeValues[6]);
	// $pipeValues[0] - Pipe number (0 for test)
	// $pipeValues[8] - actual shear pressure;
	// $pipeValues[9] - adjusted actual shear pressure; 
	if($grey){$cellConfig = ['valign' => 'center', 'bgColor' => 'D9D9D9'];}
	else{$cellConfig = ['valign' => 'center', 'bgColor' => 'ffffff'];}	
	if($test){
		$cellConfig = array_merge($cellConfig,['borderBottomSize' => 10] );
		$pipeNo = "TEST";
		$acutalShearPressure = goodNumberToString($pipeValues[8]);
		$adjActualShearPressure = goodNumberToString($pipeValues[9]);
		$cellConfigTest = $cellConfig;
	}else{
		$pipeNo = $pipeValues[0];
		$acutalShearPressure = null;
		$adjActualShearPressure = null;
		$cellConfigTest = ['valign' => 'center', 'bgColor' => '808080'];
	}
	$table->addRow($rsHeight); 
	$table->addCell(null,$cellConfig)->addText($pipeNo, 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText($pipeValues[1], 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText(goodNumberToString($pipeValues[2],3), 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText(goodNumberToString($pipeValues[3],3,TRUE), 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText(goodNumberToString($pipeValues[4]), 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText(goodNumberToString($pipeValues[5],2), 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText($maxCalcShear, 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfigTest)->addText($acutalShearPressure, 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfigTest)->addText($adjActualShearPressure, 'fsNormal', 'psCenterTable3');
	$table->addCell(null,$cellConfig)->addText($pipeValues[7], 'fsNormal', 'psCenterTable3');
	return $table;
}

function sortByPipeNo($a,$b){
	if ($a->pipeNo == $b->pipeNo) {
        return 0;
    }
    return ($a->pipeNo < $b->pipeNo) ? -1 : 1;
}
$sectionMainContent->addTitle('Pipe Data Summary', 1);

$tablePipeDataSummary = $sectionMainContent->addTable('tsPlain');
$tablePipeDataSummary->addRow($rsHeight);  //I guess I can't configure a row.
$tablePipeDataSummary->addCell(Converter::inchtotwip(.58),$csVbottomGrey)->addText("No.", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.65),$csVbottomGrey)->addText("Grade", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.54),$csVbottomGrey)->addText("OD (in)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.83),$csVbottomGrey)->addText("Wall Thickness (in)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.77),$csVbottomGrey)->addText("Strength (psi)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.72),$csVbottomGrey)->addText("Nominal Weight (ppf)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.89),$csVbottomGrey)->addText("Max. Calculated Shear/Seal Pressure (psi)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.75),$csVbottomGrey)->addText("Actual Shear Pressure (psi)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.9),$csVbottomGrey)->addText("Adjusted Actual Shear Pressure (psi)", 'fsNormal', 'psCenterTable3');
$tablePipeDataSummary->addCell(Converter::inchtotwip(.82),$csVbottomGrey)->addText("Verification Status", 'fsNormal', 'psCenterTable3');

//Locate the test pipe

//Sort pipe strongest to weakest
$pipesObj = $reportData->tubulars;
$pipesArray = get_object_vars($pipesObj);
usort($pipesArray, "sortByPipeNo");
$i=0;
foreach($pipesArray as $pipeData){
	$greyRow = ($i % 2) == 1 ? TRUE : FALSE;
	$acutalShearPressure = isset($pipeData->actualShearPressure) ? $pipeData->actualShearPressure : "";
	$actAdjShearPressure = isset($pipeData->actAdjShearPressure) ? $pipeData->actAdjShearPressure : "";
	addPipeToSummaryTable($tablePipeDataSummary, [$pipeData->pipeNo,$pipeData->grade,$pipeData->diameter,$pipeData->wall,$pipeData->evalStrength,$pipeData->ppf,$pipeData->OperatingPressure,"VERIFIED", $acutalShearPressure, $actAdjShearPressure],$greyRow, $pipeData->testPipe);
	$i++;
}
$sectionMainContent->addTextBreak(1);
//NOTES:

if(!empty($reportData->pipeDataSummaryNotes)){
	$notes = $reportData->pipeDataSummaryNotes;
	$sectionMainContent->addText("Notes:",'fsNormal', 'psNormal');
	foreach($notes as $note){
		$sectionMainContent->addListItem($note,0,'fsNormal', 'newList');
	}
}
