<?php
require_once $ptr.'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;

$BOP = $reportData->BOP;
//Well Data
$tableWellData = $sectionMainContent->addTable('tsPlain');
$tableWellData->addRow($rsHeight);
$tableWellData->addCell(Converter::inchtotwip(7),['gridSpan' => 2, 'valign' => 'center', 'bgColor' => '3A3B3D'])->addText("Well Data", 'fsNormal', 'psLeftTable3');
$tableWellData->addRow($rsHeight);
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Well Name", 'fsNormal', 'psLeftTable3');
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText($reportData->Well->name, 'fsNormal', 'psLeftTable3');
$tableWellData->addRow($rsHeight);
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Well Location", 'fsNormal', 'psLeftTable3');
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText($reportData->Well->location, 'fsNormal', 'psLeftTable3');
$tableWellData->addRow($rsHeight);
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Water Depth, Hsw (ft)", 'fsNormal', 'psLeftTable3');
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($reportData->Well->waterDepth), 'fsNormal', 'psLeftTable3');
$tableWellData->addRow($rsHeight);
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Maximum Anticipated Surface Pressure, MASP (psi)", 'fsNormal', 'psLeftTable3');
$tableWellData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($reportData->Well->pressure), 'fsNormal', 'psLeftTable3');
$sectionMainContent->addTextBreak(1,'fsNormal');
//Shear Ram
$tableShearRamData = $sectionMainContent->addTable('tsPlain');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(7),['gridSpan' => 2, 'valign' => 'center', 'bgColor' => '3A3B3D'])->addText("Shear Ram", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("BOP OEM", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText($BOP->OEM, 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Ram BOP Model", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText($BOP->model, 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Supply Pressure, Ps (psi)", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($BOP->supplyPressure), 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Operator Rated Pressure, Ps (psi)", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($BOP->operatorRatedPressure), 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Shear Operator Closing Area, Ac (in2)", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($BOP->closingArea,2), 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Shear Operator Closing Ratio, Cr", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($BOP->closingRatio,2), 'fsNormal', 'psLeftTable3');
$tableShearRamData->addRow($rsHeight);
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText("Minimum Operating Pressure for Low Pressure Seal, MOPFLPS (psi)", 'fsNormal', 'psLeftTable3');
$tableShearRamData->addCell(Converter::inchtotwip(3.5),$vAlignCell)->addText(goodNumberToString($BOP->MOPFLPS), 'fsNormal', 'psLeftTable3');