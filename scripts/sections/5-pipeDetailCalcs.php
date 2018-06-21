<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;


foreach($pipesArray as $pipeData){  //see 3-pipeDataSummary for $pipesArray
        $TESTPIPE = ($pipeData->pipeNo == 0) ? TRUE : FALSE;
        $shearPressure = goodNumberToString($pipeData->OperatingPressure - $reportData->Well->closingPressureAdjustment);
        $adjShearPressure = goodNumberToString($pipeData->OperatingPressure);  //pressure adjusted for wellbore pressures;
        $shearSealPressure = goodNumberToString(max([$pipeData->OperatingPressure, $reportData->Well->minSealPressure])); //greater of $adjShearPressure and$sealPressure;
        $gradeName = $pipeData->strengthType === "grade" ? $pipeData->grade : "";
        $pipeDiameterStr = goodNumberToString($pipeData->diameter,3,TRUE);
        $pipeWallStr = goodNumberToString($pipeData->wall,3);
	if($TESTPIPE){
		$sectionMainContent->addTitle("Test Pipe: {$gradeName}, {$pipeDiameterStr}\" OD, {$pipeWallStr}\" Wall", 1);
        }else{$sectionMainContent->addTitle("Pipe No. {$pipeData->pipeNo}: {$gradeName}, {$pipeDiameterStr}\" OD, {$pipeWallStr}\" Wall", 1);}
        
	$tablePipeCalculations = $sectionMainContent->addTable('tsPlain');
	$tablePipeCalculations->addRow($rsHeight); 
	$tablePipeCalculations->addCell(Converter::inchtotwip(2.62),$csVbottomGrey)->addText("Definition", 'fsNormal', 'psCenterTable3');
	$tablePipeCalculations->addCell(Converter::inchtotwip(.62),$csVbottomGrey)->addText("Term", 'fsNormal', 'psCenterTable3');
	$tablePipeCalculations->addCell(Converter::inchtotwip(2.58),$csVbottomGrey)->addText("Equation", 'fsNormal', 'psCenterTable3');
	$tablePipeCalculations->addCell(Converter::inchtotwip(.71),$csVbottomGrey)->addText("Result", 'fsNormal', 'psCenterTable3');
	$tablePipeCalculations->addCell(Converter::inchtotwip(.46),$csVbottomGrey)->addText("Units", 'fsNormal', 'psCenterTable3');
	$tablePipeCalculations->addRow($rsHeight);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Evaluation Yield Strength", 'fsNormal', 'psLeftTable3');
        addTermCell($tablePipeCalculations,"S","y");
        $tablePipeCalculations->addCell(null,$csGrey);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->evalStrength), 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addRow($rsHeight);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Outside Diameter of Pipe", 'fsNormal', 'psLeftTable3');
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("OD", 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addCell(null,$csGrey);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeDiameterStr, 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText('in', 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addRow($rsHeight);
        if($pipeData->selectedMethod =="Cameron"){
            $pipeForceKips = goodNumberToString(($pipeData->CamForce)/1000,1);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Pipe weight", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("ppf", 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->ppf,2), 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('ppf', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Shearing Constant", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"C","3");
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->C3,3), 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Calculated Force to Shear at Surface", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"F","CAM");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("C3 x Sy x ppf");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeForceKips, 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('kips', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Pressure Required at Sy", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"P","CAM");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Fcam / Ac x 1000 lb/kip");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($shearPressure, 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
            
        }else if($pipeData->selectedMethod =="West"){
            $pipeForceKips = goodNumberToString(($pipeData->WestForce)/1000,1);
            $pipeAreaStr = goodNumberToString($pipeData->area,3);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Nominal Thickness of Pipe", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Wall", 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeWallStr, 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('in', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Pipe Cross-Sectional Area", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"A","g");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('OD2 - (OD - 2 x Wall)^2 x pi / 4', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeAreaStr, 'fsNormal', 'psCenterTable3');
            addSqInchCell($tablePipeCalculations);
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Calculated Force to Shear at Surface", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"F","YS");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeData->WestDef);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText($pipeForceKips, 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('kips', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Pressure Required at Sy", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"P","WEST");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Fwest / Ac x 1000 lb/kip");
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('xxx', 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
        }
        $tablePipeCalculations->addRow($rsHeight);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Total Calculated Shear Pressure adjusted for operator effects", 'fsNormal', 'psLeftTable3');
        addTermCell($tablePipeCalculations,"P","T");
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("P + Padj");
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->OperatingPressure), 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addRow($rsHeight);
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Operating Pressure to Shear and Seal", 'fsNormal', 'psLeftTable3');
        addTermCell($tablePipeCalculations,"P","OP");
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText("MAX of Pys and Pseal");
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText($shearSealPressure, 'fsNormal', 'psCenterTable3');
        $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
        if($TESTPIPE){
            //actual test pressure
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Actual Shearing Pressure - Recorded in Test Report", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"P","ACT");
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->actualShearPressure), 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
            //adjusted test pressure
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Pact adjusted for Hydrostatic Effects of Wellbore and Subsea Head", 'fsNormal', 'psLeftTable3');
            addTermCell($tablePipeCalculations,"P","ADJ");
            $tablePipeCalculations->addCell(null,$vAlignCell)->addText("Pact + Pi", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText(goodNumberToString($pipeData->actualShearPressure), 'fsNormal', 'psCenterTable3');
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText('psi', 'fsNormal', 'psCenterTable3');
        }else{
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Is the calculated pressure less than or equal to 90% of the max operating pressure of the BOP?", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csSpan2Good);
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Is the calculated pressure less than or equal to the supply pressure?", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csSpan2Good);
            $tablePipeCalculations->addRow($rsHeight);
            $tablePipeCalculations->addCell(null, $vAlignCell)->addText("Is the calculated pressure less than or equal to the calculated pressure of the test pipe?", 'fsNormal', 'psLeftTable3');
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csGrey);
            $tablePipeCalculations->addCell(null,$csSpan2Good);
        }
	$sectionMainContent->addPageBreak();
}

