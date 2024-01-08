<?php

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Resource\AbstractFile;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/layouts.tsconfig',
    'Zusätzliche Layouts für Inhaltselemente'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/icons.tsconfig',
    'Standard Icon-Einstellungen'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/ContentElement/TextMedia/gridLayouts.tsconfig',
    'Grid Layouts für Bild/Text-Elemente'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'ws_t3bootstrap',
    'Configuration/TsConfig/Page/config.tsconfig',
    'T3Bootstrap Standard-Konfigurationen'
);


call_user_func(
    function ($extKey, $table) {


        $GLOBALS['TCA']['pages']['palettes']['hero'] = [
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:pages.palettes.hero',
            'showitem' => 'header_size, --linebreak--,heroimage, --linebreak--, heroimage_big, --linebreak--, heromedia_fullscreen, --linebreak--, header_darken, --linebreak--, jumbotron, heroContainer, --linebreak--, heroslide',
        ];


        $newPagesColumns = [
            'header_size' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:header_size',
                'l10n_mode' => 'mergeIfNotBlank',
                'exclude' => 1,
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:header_size.small', 'small'],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:header_size.big', 'big'],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:header_size.fullscreen', 'fullscreen'],
                    ],
                ]
            ],
            'heroimage' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroimage',
                'exclude' => 1,
                'config' => [
                    'type' => 'file',
                    'maxitems' => 1,
                    'allowed' => 'common-image-types',
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                            'selectedRatio' => 'NaN',
                                            'cropArea' => [
                                                'x' => 0.0,
                                                'y' => 0.0,
                                                'width' => 1.0,
                                                'height' => 1.0,
                                            ],
                                        ],
                                        's360' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s360',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '360:160',
                                                    'value' => 360 / 160
                                                ],
                                            ],
                                        ],
                                        's576' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s576',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '576:160',
                                                    'value' => 576 / 160
                                                ],
                                            ],
                                        ],
                                        's768' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s768',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '768:200',
                                                    'value' => 768 / 200
                                                ],
                                            ],
                                        ],
                                        's992' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s992',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '992:200',
                                                    'value' => 992 / 200
                                                ],
                                            ],
                                        ],
                                        's1200' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1200',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '1200:200',
                                                    'value' => 1200 / 200
                                                ],
                                            ],
                                        ],
                                        's1400' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1400',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '1400:200',
                                                    'value' => 1400 / 200
                                                ],
                                            ],
                                        ],
                                        'full' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.fullscreen',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '2200:200',
                                                    'value' => 2200 / 200
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ]
                ],
            ],
            'heroimage_big' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroimage_big',
                'exclude' => 1,
                'config' => [
                    'type' => 'file',
                    'maxitems' => 6,
                    'allowed' => 'common-media-types',
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                            'selectedRatio' => 'NaN',
                                            'cropArea' => [
                                                'x' => 0.0,
                                                'y' => 0.0,
                                                'width' => 1.0,
                                                'height' => 1.0,
                                            ],
                                        ],
                                        's360' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s360',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '360:530',
                                                    'value' => 360 / 530
                                                ],
                                            ],
                                        ],
                                        's576' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s576',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '576:576',
                                                    'value' => 1
                                                ],
                                            ],
                                        ],
                                        's768' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s768',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '768:470',
                                                    'value' => 768 / 470
                                                ],
                                            ],
                                        ],
                                        's992' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s992',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '992:500',
                                                    'value' => 992 / 500
                                                ],
                                            ],
                                        ],
                                        's1200' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1200',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '1200:466',
                                                    'value' => 1200 / 466
                                                ],
                                            ],
                                        ],
                                        's1400' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1400',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '1400:466',
                                                    'value' => 1400 / 466
                                                ],
                                            ],
                                        ],
                                        'full' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.fullscreen',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '2200:520',
                                                    'value' => 2200 / 520
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                ],
            ],
            'heromedia_fullscreen' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heromedia_fullscreen',
                'exclude' => 1,
                'config' => [
                    'type' => 'file',
                    'maxitems' => 6,
                    'allowed' => 'common-media-types',
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                            'selectedRatio' => 'NaN',
                                            'cropArea' => [
                                                'x' => 0.0,
                                                'y' => 0.0,
                                                'width' => 1.0,
                                                'height' => 1.0,
                                            ],
                                        ],
                                        's576' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s576',
                                            'allowedAspectRatios' => [
                                                'default' => [
                                                    'title' => '9:16',
                                                    'value' => 9 / 16
                                                ],
                                            ],
                                        ],
                                        's768' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s768',
                                            'allowedAspectRatios' => [
                                                'sm' => [
                                                    'title' => '9:16',
                                                    'value' => 9 / 16
                                                ],
                                            ],
                                        ],
                                        's992' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s992',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                        ],
                                        's1200' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1200',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                        ],
                                        's1400' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.s1400',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                        ],
                                        'full' => [
                                            'title' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imageManipulation.fullscreen',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                ],
            ],
            'header_darken' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:header_darken',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'check',
                ]
            ],
            'nav_hide_subtree' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:nav_hide_subtree',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'check',
                ]
            ],
            'heroslide' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'items' => [
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.infinite', -1],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.none', 0],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.1', 1],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.2', 2],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.3', 3],
                        ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:heroslide.4', 4],
                    ],
                    'default' => '-1',
                ]
            ],
            'jumbotron' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xml:jumbotron',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'check',
                ]
            ],
            'heroContainer' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xml:heroContainer',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'check',
                ]
            ],
            'prevent_link' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xml:prevent_link',
                'exclude' => 1,
                'l10n_mode' => 'mergeIfNotBlank',
                'config' => [
                    'type' => 'check',
                ]
            ],
            'preview_image' => [
                'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xml:preview_image',
                'exclude' => 1,
                'config' => [
                    'type' => 'file',
                    'maxitems' => 1,
                    'allowed' => 'common-image-types',
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],

                        ],
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                            'allowedAspectRatios' => [
                                                'NaN' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                                    'value' => 0.0
                                                ],
                                            ],
                                            'selectedRatio' => 'NaN',
                                            'cropArea' => [
                                                'x' => 0.0,
                                                'y' => 0.0,
                                                'width' => 1.0,
                                                'height' => 1.0,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                ],
            ],
        ];

        ExtensionManagementUtility::addTCAcolumns($table, $newPagesColumns);


        ExtensionManagementUtility::addToAllTCAtypes($table, 'prevent_link',
            PageRepository::DOKTYPE_SHORTCUT, 'after:nav_hide');


        ExtensionManagementUtility::addToAllTCAtypes($table,
            '--div--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:pages.tabs.hero,--palette--;;hero,',
            PageRepository::DOKTYPE_DEFAULT.','. PageRepository::DOKTYPE_SHORTCUT);



        ExtensionManagementUtility::addToAllTCAtypes($table, 'nav_hide_subtree',
            PageRepository::DOKTYPE_DEFAULT, 'after:nav_hide');
        ExtensionManagementUtility::addToAllTCAtypes($table, 'nav_hide_subtree',
            PageRepository::DOKTYPE_SHORTCUT, 'after:nav_hide');


        ExtensionManagementUtility::addToAllTCAtypes($table, 'preview_image',
            PageRepository::DOKTYPE_DEFAULT, 'after:media');
        ExtensionManagementUtility::addToAllTCAtypes($table, 'preview_image',
            PageRepository::DOKTYPE_SHORTCUT, 'after:media');


        $icons = ['category', 'comment', 'storage', 'people'];
        foreach ($icons as $icon) {

            // Override news icon
            $GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
                0 => ucfirst($icon),
                1 => $icon,
                2 => 'contains-' . $icon
            ];

        }


    },
    'ws_t3bootstrap',
    'pages'
);

