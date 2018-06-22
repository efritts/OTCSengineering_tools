<?php
require_once $ptr.'vendor/autoload.php';
use PhpOffice\PhpWord\Shared\Converter;

$sectionMainContent->addTitle('References', 1);

$references = $reportData->references;
foreach($references as $reference){
    $textrunReference = $sectionMainContent->addTextRun('psLeftTight');
    $textrunReference->addText($reference->Title,'fsNormalItalics');
    if(isset($reference->Author)){$textrunReference->addText("; ".$reference->Author,'fsNormal');}
    if(isset($reference->Edition)){$textrunReference->addText("; ".$reference->Edition." Edition",'fsNormal');}
    if(isset($reference->Edition, $reference->Date)){$textrunReference->addText(", ",'fsNormal');}
    if(isset($reference->Date)&& !isset($reference->Edition)){$textrunReference->addText("; ",'fsNormal');}
    if(isset($reference->Date)){$textrunReference->addText($reference->Date,'fsNormal');}
    if(isset($reference->ReaffirmedDate)){$textrunReference->addText("; Reaffirmed, ".$reference->ReaffirmedDate,'fsNormal');}
    $sectionMainContent->addTextBreak(1,null,'psLeftTight');
}
