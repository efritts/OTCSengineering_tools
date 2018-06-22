<?php
use PhpOffice\PhpWord\Shared\Converter;
//
//Styles
//

//Fonts
$phpWord->addFontStyle('fsNormal',[
        'name' => 'Arial',
        'size' => 10
]);
$phpWord->addFontStyle('fsNormalBold',[
        'basedOn' => 'fsNormal',
        'bold' => TRUE
]);
$phpWord->addFontStyle('fsNormalItalics',[
        'basedOn' => 'fsNormal',
        'italic' => TRUE
]);
$phpWord->addFontStyle('fsNormalSub',[
        'basedOn' => 'fsNormal',
        'subScript' => TRUE
]);
$phpWord->addFontStyle('fsNormalSup',[
        'basedOn' => 'fsNormal',
        'superScript' => TRUE
]);
$phpWord->addFontStyle('fsTitle',[
        'name' => 'Arial',
        'size' => 20,
        'bold' => TRUE
]);
    $phpWord->addFontStyle('fsSize8', [
            'basedOn' => 'fsNormal',
            'size' => 8,
    ]);
    $phpWord->addFontStyle('fsSize9', [
            'basedOn' => 'fsNormal',
            'size' => 9,
    ]);
$phpWord->addFontStyle('fsSmallBold',[
    'basedOn' => 'fsSize9',
    'bold' => true
    ]);
$phpWord->addFontStyle('fsHeader', ['name' => 'Arial', 'size' => 8]);

$phpWord->addFontStyle('fsSubTitle', [
    'name' => 'Arial',
    'size' => 16,
    'bold' => true,
    'spaceAfter' => 0,
    'spaceBefore' => 0,
    'color' => '3A3B3D'
]);
$fsH1config = ['name' => 'Arial',
    'size' => 14,
    'bold' => true,
    'color' => '8B2027'
    ];
$phpWord->addFontStyle('fsH1', $fsH1config);

//Paragraphs
$phpWord->addParagraphStyle('psNormal', ['alignment' => 'both']);
$psCenteredConfig = ['alignment' => 'center'];
$phpWord->addParagraphStyle('psCentered',$psCenteredConfig );
$phpWord->addParagraphStyle('psCenterTight', [
    'alignment' => 'center',
    'spaceAfter' => 0,
    'spaceBefore' => 0]
);
$phpWord->addParagraphStyle('psLeftTight', [
    'alignment' => 'left',
    'spaceAfter' => 0,
    'spaceBefore' => 0]
);
$phpWord->addParagraphStyle('psRightTight', [
    'alignment' => 'right',
    'spaceAfter' => 0,
    'spaceBefore' => 0]
);
$phpWord->addParagraphStyle('psLeftTable3', [
    'alignment' => 'left',
    'spaceAfter' => 3,
    'spaceBefore' => 3]
);
$phpWord->addParagraphStyle('psCenterTable3', [
    'alignment' => 'center',
    'spaceAfter' => 3,
    'spaceBefore' => 3]
);
$styleParaTitle = [
        'alignment' => 'right',
        'spaceAfter' => 2,
        'spaceBefore' => 2
];
$styleParaSubTitle = [
        'alignment' => 'right',
        'spaceAfter' => 0,
        'spaceBefore' => 0
];
$styleParaRevs = [
        'basedOn' => 'psNormal',
        'alignment' => 'left'
];

//Sections
$styleSectionDefault = [
    'paperSize' => 'Letter',
    'marginTop' => Converter::inchToTwip(1.15),
    'marginLeft' => Converter::inchToTwip(.75),
    'marginRight' => Converter::inchToTwip(.75),
    'marginBottom' => Converter::inchToTwip(.5),
    'headerHeight' => Converter::inchToTwip(.3),
    'footerHeight' => Converter::inchToTwip(.3)
];
$styleSectionTitle = [
    'paperSize' => 'Letter',
    'marginTop' => Converter::inchToTwip(.5),
    'marginLeft' => Converter::inchToTwip(.5),
    'marginRight' => Converter::inchToTwip(.5),
    'marginBottom' => Converter::inchToTwip(.3),
    'headerHeight' => Converter::inchToTwip(.3),
    'footerHeight' => Converter::inchToTwip(.4),
    'borderSize' => 40,
    'borderColor' => '9E3234'
];
/*Merging styles
    $styleSectionTitle = array_merge($styleSectionDefault, [
        'borderSize' => 40,
        'borderColor' => '9E3234'
        ]
    );
 * 
 */

//tables
$phpWord->addTableStyle('tsPlain', [
        'borderColor' => '000000',
        'borderSize' => 1,
        'cellMarginTop' => 0,
        'cellMarginBottom' => 0,
        'cellMarginLeft' => Converter::inchToTwip(0.08),
        'cellMarginRight' => Converter::inchToTwip(0.08)]
);
$phpWord->addTableStyle('tsPlainNoBorder', [
    'basedOn' => 'tsPlain',    
    'borderColor' => 'ffffff']
);
$phpWord->addTableStyle('tsFooter', ['cellMargin' => 0]);

//Row Style
$rsHeight =Converter::inchToTwip(.3);

//Cell Styles
$vAlignCell = ['valign' => 'center'];
$csBottomBorder = ['borderBottomColor'=>'000000', 'borderBottomSize' => 1];
$csVbottomGrey = ['valign' => 'bottom', 'bgColor' => '3A3B3D'];
$csGrey = ['bgColor' => 'BFBFBF'];
$csSpanRow = ['gridSpan' => 5, 'valign' => 'center', 'bgColor' => 'BFBFBF'];
$csSpan2Good = ['gridSpan' => 2, 'valign' => 'center', 'bgColor' => 'c5e0b3'];

//Titles
$phpWord->addTitleStyle(1,$fsH1config, $psCenteredConfig);

//Numbering
$predefinedMultilevelStyle = ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER];
$phpWord->addNumberingStyle('newList', [
	'type' => 'singleLevel',
	'levels' => [
		['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360]
	]
]
);