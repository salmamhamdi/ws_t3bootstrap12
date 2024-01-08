<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$additionalColumns = [

    'extended_design' => [
        'label' => 'Erweitertes Design',
        'onChange' => 'reload',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['keines', ''],
            ],
        ],
    ],
    'text_visible' => [
        'displayCond' => 'FIELD:extended_design:REQ:true',
        'l10n_mode' => '',
        'label' => 'Sichtbarer Text',
        'config' => [
            'type' => 'text',
            'cols' => '80',
            'rows' => '3',
            'enableRichtext' => true,
            'softref' => 'typolink_tag,email[subst],url',
        ]
    ],
    'text_hover' => [
        'displayCond' => 'FIELD:extended_design:REQ:true',
        'l10n_mode' => '',
        'label' => 'Hover-Text',
        'config' => [
            'type' => 'text',
            'cols' => '80',
            'rows' => '3',
            'enableRichtext' => true,
            'softref' => 'typolink_tag,email[subst],url',
        ]
    ],

];


ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $additionalColumns);

ExtensionManagementUtility::addFieldsToPalette('sys_file_reference', 'imageoverlayPalette', '--linebreak--,extended_design,--linebreak--,text_visible,text_hover');
