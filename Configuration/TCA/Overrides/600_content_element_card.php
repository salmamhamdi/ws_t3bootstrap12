<?php

/***************
 * Add Content Element
 */
if (!is_array($GLOBALS['TCA']['tt_content']['types']['card'] ?? null)) {
    $GLOBALS['TCA']['tt_content']['types']['card'] = [];
}

/***************
 * Add content element PageTSConfig
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/ContentElement/Element/Card.tsconfig',
    'Card'
);

/***************
 * Add content element to selector list
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:card',
        'card',
        'content-card'
    ],
    'textmedia',
    'after'
);

/***************
 * Assign Icon
 */
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['card'] = 'content-card';

/***************
 * Configure element type
 */
$GLOBALS['TCA']['tt_content']['types']['card'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['card'],
    [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                --palette--;;headers-card,
                tx_wst3bootstrap_card_elements,
                image_overlay,effects,tx_wst3bootstrap_card_elements_backside,
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
        ',
    ]
);

$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'tx_wst3bootstrap_card_elements' => [
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:card_elements',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wst3bootstrap_card_element',
                'foreign_field' => 'tt_content',
                'foreign_match_fields' => [
                    'field' => 'frontside'
                ],
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
        ],
        'image_overlay' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:card.image_overlay',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'use_link_overlay' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:use_link_overlay',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'tx_wst3bootstrap_card_elements_backside' => [
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:card.card_elements_backside',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wst3bootstrap_card_element',
                'foreign_field' => 'tt_content',
                'foreign_match_fields' => [
                    'field' => 'backside'
                ],
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
            ],
            /*
            'displayCond' => [
                'AND' => [
                    'FIELD:effects:=:flip',
                ]
            ]*/
        ],
    ]
);

$GLOBALS['TCA']['tt_content']['palettes'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['palettes'],
    [
        'headers-card' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers',
            'showitem' => '
                header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
                --linebreak--,
                header_layout;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout_formlabel,
                header_position;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position_formlabel,
                date;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:date_formlabel,
                --linebreak--,
                header_link;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel,
                use_link_overlay;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:use_link_overlay,
                --linebreak--,
                subheader;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:subheader_formlabel
            ',
        ]
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.footers;footers,', 'card', 'after:header');
