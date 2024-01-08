<?php

/***************
 * Add Content Element
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!is_array($GLOBALS['TCA']['tt_content']['types']['counter_bar'] ?? null)) {
    $GLOBALS['TCA']['tt_content']['types']['counter_bar'] = [];
}

/***************
 * Add content element PageTSConfig
 */
ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/ContentElement/Element/Counterbar.tsconfig',
    'Counterbar'
);

/***************
 * Add content element to selector list
 */
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:counterbar',
        'counter_bar',
        'counter-bar'
    ],
    //Question: textteaser?
    'textteaser',
    'after'
);

/***************
 * Assign Icon
 */
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['counterbar'] = 'counter-bar';

/***************
 * Configure element type
 */
$GLOBALS['TCA']['tt_content']['types']['counter_bar'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['counter_bar'],
    [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                pi_flexform,
                tx_wst3bootstrap_counterbar_item,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                assets,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    ]
);

/***************
 * Register fields
 */
$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'tx_wst3bootstrap_counterbar_item' => [
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:counterbar_item',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wst3bootstrap_counterbar_item',
                'foreign_field' => 'tt_content',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => false,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                        'dragdrop' => true,
                        'new' => true,
                        'sort' => false,
                    ]
                ],
                'behaviour' => [
                    'mode' => 'select',
                ]
            ]
        ]
    ]
);



/***************
 * Add flexForms for content element configuration
 */
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:ws_t3bootstrap/Configuration/FlexForms/Counterbar.xml',
    'counter_bar'
);
