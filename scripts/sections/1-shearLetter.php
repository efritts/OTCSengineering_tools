<?php
use PhpOffice\PhpWord\Shared\Converter;

$sectionMainContent->addTitle('Shear Calculation Letter', 1);
$textShearLetter = $sectionMainContent->addTextRun('psNormal');
$textShearLetter->addText("OTC Solutions, LLC has performed the shear verification calculations with required supporting documentation. Based on the client input data provided to OTC Solutions the BOP system meets the Code of Federal Regulations requirements per 30 CFR §250.732 (b)");

//Exceptions are provided by the JSON.  Each number corresponds to a specific exception to the report.
$exceptions = $reportData->exceptions;
if(!empty($exceptions)){
    $textShearLetter->addText(" with the following exceptions:");
    foreach($exceptions as $exception){
        switch($exception){
            case 1:
                $note = "The test data supplied does not include pressure sealing for 30 minutes per §250.732 (b)(2)(ii).";
                break;
            case 2:
                $note = "The test data supplied does not include testing under flow and pressure in the wellbore.";
                break;
            default:
                $note = "";
        }
        $sectionMainContent->addListItem($note,0,'fsSize9',null, 'psLeftTight');
    }
}else{
    $textShearLetter->addText(".");
    $textShearLetter->addTextBreak();
}
$textShearLetter2 = $sectionMainContent->addTextRun('psNormal');
$textShearLetter2->addText("Please find the attached shear verification calculations and related documents for ");
$textShearLetter2->addText($reportData->clientFullLegal,'fsNormalBold');
$textShearLetter2->addText(" on the ");
$textShearLetter2->addText($reportData->Rig->name,'fsNormalBold');
$textShearLetter2->addText(" for the ");
$textShearLetter2->addText($reportData->Well->name,'fsNormalBold');
$textShearLetter2->addText(" well.  The calculations were carried out using ");
$shearCalculationMethod = $reportData->calculationMethods;  //1 = "the Distortion Energy Theory; 2 = "the methods described by BSEE TAP 463"; 3 = "the methods described by Cameron EB 702D"
if(!empty($shearCalculationMethod)){
    //if(count($shearCalculationMethod)>1){$conjunction = " and ";}
    //else{$conjunction = "";}
    $x = 1;
    foreach($shearCalculationMethod as $method){
        if($x>1){$textShearLetter2->addText(" and ");}
        switch($method){
            case 1:
                $methodNote = "the Distortion Energy Theory";
                break;
            case 2:
                $methodNote = "the methods described by BSEE TAP 463";
                break;
            case 3:
                $methodNote = "the methods described by Cameron EB 702D";
                break;
            default:
                $methodNote = "our really large brains";
        }
        $textShearLetter2->addText($methodNote);
        $x++;
    }
}
if($reportData->Rig->location == "subsea"){$maspMAWHPlong = "Maximum Allowable Wellhead Pressure"; $maspMAWHP = "MAWHP";}
else{$maspMAWHPlong = "Maximum Anticipated Surface Pressure"; $maspMAWHP = "MASP";}
$textShearLetter2->addText(". Background equations used for the calculations are shown on each table. Based on the client supplied input data and OTC Solutions shear calculations, the most rigid pipe to be used in the well has been verified to require less shear force when compared to actual shear data obtained for the same model BOP shearing equal or more rigid grade pipe. Shear test report provided within. OTC Solutions has used the submitted $maspMAWHPlong ($maspMAWHP) for the calculations and considers this verification valid for any lower $maspMAWHP values. ");
$textShearLetter2->addTextBreak();
$textShearLetter2->addText("This letter is issued with the following disclaimers:");
$sectionMainContent->addListItem("Drill pipe assembly properties are calculated based on uniform OD and wall thickness. No safety factor is applied.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("It is the responsibility of the customer and end user to determine the appropriate performance ratings, acceptable use of the drill pipe evaluated, maintain safe operational practices, and to apply a prudent safety factor for the application.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The calculations assume BOP wellbore pressure at Maximum Anticipated Wellhead Pressure. This may also be referred to as MASP at the wellhead.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("Pipe tension is assumed to be zero at the moment of shearing, which is the worst case.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("Real operator pressures may vary from calculated results and can be affected by additional factors including material hard spots, and fluid flow past the ram blocks.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The drill pipe is assumed to have a yield stress not exceeding applicable Material Test Report (MTR) information, or the maximum value stated in API standards where material certificates are not available. ",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The following provisions apply:",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The vessel BOP equipment is maintained in good order and is operated following the manufacturer’s instructions.",1,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The pipe properties do not exceed the diameter and wall thickness stated in the cited standards and the material properties are within the cited Material Test Report sheet data.",1,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The BOP is fitted with the correct style and size ram blocks listed, unless expressly directed otherwise in writing by the BOP manufacturer or service agent.",1,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("No modifications have been made to the BOP or its control system since the last inspection was concluded. ",1,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The ram blocks have not been used for previous cuts of this or any pipe or object that may have compromised their cutting or sealing capabilities. ",1,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The ram blocks have not been affected by wellbore fluids that have adversely affected their strength or hardness and therefore, their ability to cut the pipe efficiently.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The BOP and its control system have successfully passed the daily and weekly testing and any anomalies found have been immediately rectified. ",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("The control system and stack drawings are verified to be “as fitted” and serial numbers and certificates of all critical items are verified by surveyors and subject to the additional provisions set forth in 30 CFR §250 where applicable.",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addListItem("OTC Solutions makes no representations or warranties express or implied as to the quality, accuracy, usefulness, and completeness of the information and data contained herein and disclosed hereunder (“Data”). OTC Solutions, its affiliates, its officers, directors, and employees shall have no liability whatsoever with respect to the use of the Data by a recipient. ",0,'fsSize9',$predefinedMultilevelStyle, 'psLeftTight');
$sectionMainContent->addTextBreak();

$tableApproval = $sectionMainContent->addTable();
$tableApproval->addRow(Converter::inchToTwip(.21));
$tableApproval->addCell(Converter::inchToTwip(2.18),$vAlignCell)->addText("Professional Engineer Approval:",'fsNormal','psLeftTight');
$tableApproval->addCell(Converter::inchToTwip(3.25),$csBottomBorder);
$tableApproval->addCell(Converter::inchToTwip(.25),$vAlignCell)->addText("Date:",'fsNormal','psLeftTight');
$tableApproval->addCell(Converter::inchToTwip(1.31),$csBottomBorder);