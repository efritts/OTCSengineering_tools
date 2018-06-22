<?php
require_once $ptr.'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;

function addSqInchCell($table){
    global $vAlignCell;
    
    $trIN2=$table->addCell(null,$vAlignCell)->addTextRun('psCenterTight');
    $trIN2->addText('in','fsSize9');
    $trIN2->addText('2','fsNormalSup');
    
    return $table;
}


$tableHSeffects = $sectionMainContent->addTable('tsPlain');
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(Converter::inchtotwip(2.55),$csVbottomGrey)->addText("Definition", 'fsNormal', 'psCenterTable3');
$tableHSeffects->addCell(Converter::inchtotwip(.69),$csVbottomGrey)->addText("Term", 'fsNormal', 'psCenterTable3');
$tableHSeffects->addCell(Converter::inchtotwip(2.58),$csVbottomGrey)->addText("Equation", 'fsNormal', 'psCenterTable3');
$tableHSeffects->addCell(Converter::inchtotwip(.71),$csVbottomGrey)->addText("Result", 'fsNormal', 'psCenterTable3');
$tableHSeffects->addCell(Converter::inchtotwip(.46),$csVbottomGrey)->addText("Units", 'fsNormal', 'psCenterTable3');
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null,$csSpanRow)->addText("BOP Calculations", 'fsNormalBold', 'psLeftTight');

//Closing Area
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null, $vAlignCell)->addText("Closing Area",'fsSize9', 'psLeftTight');
addTermCell($tableHSeffects, 'A', 'C');
$tableHSeffects->addCell(null, $csGrey);
$tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->BOP->closingArea, 'fsSize9', 'psCenterTight');
addSqInchCell($tableHSeffects);

//Closing Ratio
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null, $vAlignCell)->addText("Closing Ratio",'fsSize9', 'psLeftTight');
addTermCell($tableHSeffects, 'C', 'R');
$tableHSeffects->addCell(null, $csGrey);
$tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->BOP->closingRatio, 'fsSize9', 'psCenterTight');
$tableHSeffects->addCell(null, $csGrey);

//BOP Calculations for Subsea BOP
if($reportData->Rig->location == 'subsea'){
    //Tailrod Area
    $tableHSeffects->addRow($rsHeight);
    $tableHSeffects->addCell(null, $vAlignCell)->addText("Tail Rod Area",'fsSize9', 'psLeftTight');
    addTermCell($tableHSeffects, 'A', 'T');
    $tableHSeffects->addCell(null, $csGrey);
    $tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->BOP->trArea, 'fsSize9', 'psCenterTight');
    addSqInchCell($tableHSeffects);
    
    //Ramshaft Area
    $tableHSeffects->addRow($rsHeight);
    $tableHSeffects->addCell(null, $vAlignCell)->addText("Ramshaft Area",'fsSize9', 'psLeftTight');
    addTermCell($tableHSeffects, 'A', 'R');
    $equationRamShaft = $tableHSeffects->addCell(null, $vAlignCell)->addTextRun('psLeftTight');
    $equationRamShaft->addText('A');
    $equationRamShaft->addText('C', 'fsNormalSub');
    $equationRamShaft->addText(' / C');
    $equationRamShaft->addText('R', 'fsNormalSub');
    $tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->BOP->trArea, 'fsSize9', 'psCenterTight');
    addSqInchCell($tableHSeffects);
    //Opening Area
    $tableHSeffects->addRow($rsHeight);
    $tableHSeffects->addCell(null, $vAlignCell)->addText("Opening Area",'fsSize9', 'psLeftTight');
    addTermCell($tableHSeffects, 'A', 'O');
    $equationOpeningArea = $tableHSeffects->addCell(null, $vAlignCell)->addTextRun('psLeftTight');
    $equationOpeningArea->addText('(A');
    $equationOpeningArea->addText('C', 'fsNormalSub');
    $equationOpeningArea->addText(' + A');
    $equationOpeningArea->addText('T', 'fsNormalSub');
    $equationOpeningArea->addText(') - A');
    $equationOpeningArea->addText('R', 'fsNormalSub');
    $tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->BOP->openingArea, 'fsSize9', 'psCenterTight');
    addSqInchCell($tableHSeffects);
}

$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null,$csSpanRow)->addText("Wellbore Pressure", 'fsNormalBold', 'psLeftTight');
$tableHSeffects->addRow($rsHeight);
$wellPressureName = $reportData->Rig->location === 'subsea' ? "Maximum Anticipated Wellhead Pressure @ BOP" : "Maximum Anticipated Surface Pressure @ BOP";
$wellPressureShortName = $reportData->Rig->location === 'subsea' ? "MAWHP" : "MASP";
$tableHSeffects->addCell(null,$vAlignCell)->addText($wellPressureName, 'fsSize9', 'psLeftTight');
$tableHSeffects->addCell(null,$vAlignCell)->addText($wellPressureShortName, 'fsSize9', 'psCenterTight');
$tableHSeffects->addCell(null, $csGrey);
$tableHSeffects->addCell(null, $vAlignCell)->addText(goodNumberToString($reportData->Well->pressure), 'fsSize9', 'psCenterTight');
$tableHSeffects->addCell(null, $vAlignCell)->addText("psi", 'fsSize9', 'psCenterTight');

//BOP Calculations for Subsea BOP
if($reportData->Rig->location == 'subsea'){
	//Mud weight
	
	//Height of Riser
	
	//Water Depth
	
	//Mud Pressure @ depth
	
	//Dominate wellbore pressure
	
	//Pi, Increased Shear Pressure
}else{
    $tableHSeffects->addRow($rsHeight);
    //Pi, Increased Shear Pressure due to MASP
    $tableHSeffects->addCell(null, $vAlignCell)->addText("Increased Shear Pressure due to Pressure in Wellbore",'fsSize9', 'psLeftTight');
    addTermCell($tableHSeffects, 'P', 'i');
    $equationPi = $tableHSeffects->addCell(null, $vAlignCell)->addTextRun('psLeftTight');
    $equationPi->addText('MASP / C');
    $equationPi->addText('R', 'fsNormalSub');
    $tableHSeffects->addCell(null,$vAlignCell)->addText($reportData->Well->closingPressureAdjustment, 'fsSize9', 'psCenterTight');
    $tableHSeffects->addCell(null, $vAlignCell)->addText("psi", 'fsSize9', 'psCenterTight');
}

//BOP Calculations for Subsea BOP
if($reportData->Rig->location === 'subsea'){
	//Control Fluid Pressure Gradient
	//Height of HPU
	//Hieght of BOP
	// Seawater pressure gradient
	//Pressure due to Hydrostatic Head of Control Fluid
	//Pressure due to Hydrostatic Head of Seawater
	//Opening Force due to Seawater Head on Operator
	//Closing Force Due to Control Fluid Head on Operator
	//Closing Force due to Seawater head on Tailrod
	//Change in closing Pressure due to Hydrostatics
}
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null,$csSpanRow)->addText("Minimum Sealing Pressure", 'fsNormalBold', 'psLeftTight');
//Min. Sealing Pressure
$tableHSeffects->addRow($rsHeight);
$tableHSeffects->addCell(null, $vAlignCell)->addText("Minimum Sealing Pressure",'fsSize9', 'psLeftTight');
addTermCell($tableHSeffects, 'P', 'SEAL');
$equationMSP = $tableHSeffects->addCell(null, $vAlignCell)->addTextRun('psLeftTight');
$equationMSP->addText('MOPFLPS + P');
$equationMSP->addText('i', 'fsNormalSub');
$tableHSeffects->addCell(null,$vAlignCell)->addText($sealPressureStr, 'fsSize9', 'psCenterTight');
$tableHSeffects->addCell(null, $vAlignCell)->addText("psi", 'fsSize9', 'psCenterTight');