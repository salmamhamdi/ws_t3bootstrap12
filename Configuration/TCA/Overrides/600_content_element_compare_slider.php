<?php

/***************
 * Add Content Element
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!is_array($GLOBALS['TCA']['tt_content']['types']['compare_slider'] ?? null)) {
    $GLOBALS['TCA']['tt_content']['types']['compare_slider'] = [];
}

/***************
 * Add content element PageTSConfig
 */
ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/ContentElement/Element/CompareSlider.tsconfig',
    'Compare Slider'
);

/***************
 * Add content element to selector list
 */
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:compare_slider',
        'compare_slider',
        'content-compare-slider'
    ],
    'textmedia',
    'after'
);

/***************
 * Assign Icon
 */
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['compare_slider'] = 'content-compare-slider';

/***************
 * Configure element type
 */
$GLOBALS['TCA']['tt_content']['types']['compare_slider'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['compare_slider'],
    [
        'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;headers,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,
                    image,
                    --palette--;;mediaAdjustments,
                    --palette--;;gallerySettings,
                    --palette--;;imagelinks,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                    --palette--;;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            '
    ]
);

