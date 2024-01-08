<?php

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$additionalColumns = [
    'image_classes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:image_classes',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            'items' => [],
            'multiple' => true,
            'minitems' => 0,
            'maxitems' => 999
        ]
    ],
    'element_classes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:element_classes',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            'multiple' => true,
            'minitems' => 0,
            'maxitems' => 999,
            'items' => []
        ]
    ],
    'imagecols_xs' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_xs',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'imagecols_sm' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_sm',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'imagecols_md' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_md',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'imagecols_lg' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_lg',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'imagecols_xl' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_xl',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'imagecols_xxl' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:imagecols_xxl',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => 'WapplerSystems\WsT3bootstrap\UserFunction\ProviderField->getImageColumnNumberItems',
            'items' => [],
            'default' => 1
        ]
    ],
    'background_media' => [
        'exclude' => 1,
        'l10n_mode' => 'mergeIfNotBlank',
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:background_media',
        'config' => [
            'type' => 'file',
            'allowed' => 'common-image-types',
            'maxitems' => 1,
            'overrideChildTca' => [
                'types' => [
                    File::FILETYPE_IMAGE => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                    ],
                    0 => [
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
        ],
    ],
    'effects' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:effects',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            'multiple' => true,
            'minitems' => 0,
            'maxitems' => 999,
            'items' => []
        ]
    ],
    'html_tag' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:html_tag',
        'displayCond' => 'FIELD:CType:=:wst3bootstrap_container',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'default' => 'div',
            'items' => [
                ['div', 'div'],
                ['none', 'none'],
                ['section', 'section'],
            ],
        ]
    ],
    'aos_effect' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'default' => 'none',
            'items' => [
                ['none', 'none'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.fade', '--div--'],
                ['fade-up', 'fade-up'],
                ['fade-down', 'fade-down'],
                ['fade-right', 'fade-right'],
                ['fade-left', 'fade-left'],
                ['fade-up-right', 'fade-up-right'],
                ['fade-up-left', 'fade-up-left'],
                ['fade-down-right', 'fade-down-right'],
                ['fade-down-left', 'fade-down-left'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.flip', '--div--'],
                ['flip-left', 'flip-left'],
                ['flip-right', 'flip-right'],
                ['flip-up', 'flip-up'],
                ['flip-down', 'flip-down'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.slide', '--div--'],
                ['slide-up', 'slide-up'],
                ['slide-down', 'slide-down'],
                ['slide-left', 'slide-left'],
                ['slide-right', 'slide-right'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.zoom', '--div--'],
                ['zoom-in', 'zoom-in'],
                ['zoom-in-up', 'zoom-in-up'],
                ['zoom-in-down', 'zoom-in-down'],
                ['zoom-in-left', 'zoom-in-left'],
                ['zoom-in-right', 'zoom-in-right'],
                ['zoom-out', 'zoom-out'],
                ['zoom-out-up', 'zoom-out-up'],
                ['zoom-out-down', 'zoom-out-down'],
                ['zoom-out-right', 'zoom-out-right'],
                ['zoom-out-left', 'zoom-out-left'],
            ],
        ]
    ],
    'aos_easing' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.easing',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'default' => 'ease-in-out',
            'items' => [
                ['linear', 'linear'],
                ['ease', 'ease'],
                ['ease-in', 'ease-in'],
                ['ease-out', 'ease-out'],
                ['ease-in-out', 'ease-in-out'],
                ['ease-in-back', 'ease-in-back'],
                ['ease-out-back', 'ease-out-back'],
                ['ease-in-out-back', 'ease-in-out-back'],
                ['ease-in-sine', 'ease-in-sine'],
                ['ease-out-sine', 'ease-out-sine'],
                ['ease-in-out-sine', 'ease-in-out-sine'],
                ['ease-in-quad', 'ease-in-quad'],
                ['ease-out-quad', 'ease-out-quad'],
                ['ease-in-out-quad', 'ease-in-out-quad'],
                ['ease-in-cubic', 'ease-in-cubic'],
                ['ease-out-cubic', 'ease-out-cubic'],
                ['ease-in-out-cubic', 'ease-in-out-cubic'],
                ['ease-in-quart', 'ease-in-quart'],
                ['ease-out-quart', 'ease-out-quart'],
                ['ease-in-out-quart', 'ease-in-out-quart'],
            ],
        ]
    ],
    'aos_once' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.once',
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
    'aos_duration' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.duration',
        'config' => [
            'type' => 'input',
            'size' => 5,
            'eval' => 'trim,int',
            'default' => 500,
            'range' => [
                'lower' => 50,
                'upper' => 3000
            ],
            'slider' => [
                'step' => 50,
                'width' => 180,
            ],
        ]
    ],
    'aos_delay' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.delay',
        'config' => [
            'type' => 'input',
            'size' => 20,
            'eval' => 'num',
            'default' => 0,
        ]
    ],
    'aos_offset' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.offset',
        'config' => [
            'type' => 'input',
            'size' => 20,
            'eval' => 'num',
            'default' => 120,
        ]
    ],
    'aos_identifiermark' => [
        'exclude' => true,
        'label' => '',
    ],
    'frame_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectCheckBox',
            'size' => 4,
            'items' => [
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.ruler_before',
                    'value' => 'ruler-before',
                    'group' => 'line',
                ],
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.ruler_after',
                    'value' => 'ruler-after',
                    'group' => 'line',
                ],
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.indent',
                    'value' => 'indent',
                    'group' => 'indent',
                ],
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.indent_left',
                    'value' => 'indent-left',
                    'group' => 'indent',
                ],
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.indent_right',
                    'value' => 'indent-right',
                    'group' => 'indent',
                ],
                [
                    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.none',
                    'value' => 'none'
                ],
            ],
            'itemGroups' => [
                'line' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:frame_class.group.lines',
                'indent' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:frame_class.group.indents',
                'design' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:frame_class.group.design',
            ],
            'default' => ''
        ]
    ],
    'bg_color_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:bg_color',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.none',
                    'value' => ''
                ],
                [
                    'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.label.custom',
                    'value' => '--div--'
                ],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-lightgray', 'bg-lightgray'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-lightblue', 'bg-lightblue'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.label.bootstrap-theme-colors', '--div--'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-primary', 'bg-primary'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-secondary', 'bg-secondary'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-success', 'bg-success'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-info', 'bg-info'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-warning', 'bg-warning'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-danger', 'bg-danger'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-light', 'bg-light'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-dark', 'bg-dark'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.label.bootstrap-palette-colors', '--div--'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-blue', 'bg-blue'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-indigo', 'bg-indigo'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-purple', 'bg-purple'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-pink', 'bg-pink'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-red', 'bg-red'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-orange', 'bg-orange'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-yellow', 'bg-yellow'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-green', 'bg-green'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-teal', 'bg-teal'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-cyan', 'bg-cyan'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-white', 'bg-white'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-gray', 'bg-gray'],
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/config_tsconfig.xlf:bg_color_class.bg-gray-dark', 'bg-gray-dark'],
            ],
            'default' => ''
        ],
    ],
    'padding_top_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:padding_top_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingleWithTypoScriptPlaceholderT3bootstrap',
            'typoscriptPath' => 'content.padding.top',
            'items' => [
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_none', 'none'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_small', 'extra-small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_small', 'small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_medium', 'medium'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_large', 'large'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_large', 'extra-large'],
            ],
        ]
    ],
    'padding_bottom_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:padding_bottom_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingleWithTypoScriptPlaceholderT3bootstrap',
            'typoscriptPath' => 'content.padding.bottom',
            'items' => [
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_none', 'none'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_small', 'extra-small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_small', 'small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_medium', 'medium'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_large', 'large'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_large', 'extra-large'],
            ],
        ]
    ],
    'filelink_sorting_direction' => [
        'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:filelink_sorting_direction',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:filelink_sorting_direction.ascending', 'ascending'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:filelink_sorting_direction.descending', 'descending'],
            ]
        ]
    ],
    'footer' => [
        'l10n_mode' => 'prefixLangTitle',
        'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:footer',
        'config' => [
            'type' => 'input',
            'size' => 50,
            'max' => 255,
        ],
    ],
    'footer_layout' => [
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value',
                    '0'
                ],
            ],
            'default' => 0
        ]
    ],
    'footer_position' => [
        'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:footer_position',
        'exclude' => true,
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value',
                    ''
                ],
                [
                    'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position.I.1',
                    'center'
                ],
                [
                    'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position.I.2',
                    'right'
                ],
                [
                    'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position.I.3',
                    'left'
                ]
            ],
            'default' => ''
        ]
    ],
    'footer_link' => [
        'exclude' => true,
        'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:footer_link',
        'config' => [
            'type' => 'input',
            'renderType' => 'inputLink',
            'size' => 50,
            'max' => 1024,
            'eval' => 'trim',
            'fieldControl' => [
                'linkPopup' => [
                    'options' => [
                        'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:footer_link_formlabel',
                    ],
                ],
            ],
            'softref' => 'typolink'
        ]
    ],
    'icon' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.icon',
        'config' => [
            'type' => 'input',
            'renderType' => 'selectIcon',
        ]
    ],
    'icon_style' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.icon_style',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Standard', ''],
            ],
        ]
    ],
    'icon_color' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.icon_color',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Standard', ''],
            ],
        ]
    ],
    'icon_size' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.icon_size',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Standard', ''],
            ],
        ]
    ],
    'icon_position' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.icon_position',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [

            ],
        ]
    ],
    'form_layout' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.form_layout',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Standard', ''],
            ],
        ]
    ],
    'grid_layout' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.gridLayout',
        'onChange' => 'reload',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Standard', ''],
            ],
        ]
    ],
    'grid_layout_responsive_bg' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.gridLayout_responsive_bg',
        'displayCond' => [
            'OR' => [
                'FIELD:grid_layout:=:50t:50mb',
                'FIELD:grid_layout:=:50mb:50t',
            ],
        ],
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.gridLayout_responsive_bg.none', ''],
            ],
        ]
    ],
    'full_width' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.full_width',
        'displayCond' => 'USER:WapplerSystems\\WsT3bootstrap\\UserFunction\\BackendLayoutMatch->check:pagets__1Column',
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
    'fixed_image' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.fixed_image',
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
    'levels' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:levels',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [1, 1],
                [2, 2],
                [3, 3],
                [4, 4],
                [5, 5],
                [6, 6],
                [7, 7],
            ],
            'default' => 7
        ]
    ],
    'visibility_flags' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:visibility',
        'l10n_mode' => 'exclude',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'default' => '63',
            'items' => [
                ['XS', ''],
                ['SM', ''],
                ['MD', ''],
                ['LG', ''],
                ['XL', ''],
                ['XXL', ''],
            ],
            'cols' => 'inline',
        ],
    ],
    'image_loading_behaviour' => [
        'exclude' => true,
        'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.image_loading_behaviour',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['Auto', 'auto'],
                ['Lazy', 'lazy'],
                ['Eager', 'eager'],
            ],
            'default' => 'auto'
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);


$GLOBALS['TCA']['tt_content']['palettes'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['palettes'], [
        'footers' => [
            'label' => 'LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.footers',
            'showitem' => '
                    footer;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:footer_formlabel,
                    --linebreak--,
                    footer_layout;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:footer_layout_formlabel,
                    footer_position;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:footer_position_formlabel,
                    --linebreak--,
                    footer_link;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:footer_link_formlabel,
                    --linebreak--,
                    subfooter;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:subfooter_formlabel
                ',
        ],
    ]
);

unset($GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items']);
unset($GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items']);

ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['tt_content']['columns'], [
    'space_before_class' => [
        'config' => [
            'renderType' => 'selectSingleWithTypoScriptPlaceholderT3bootstrap',
            'typoscriptPath' => 'content.margin.top',
            'items' => [
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_none', 'none'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_small', 'extra-small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_small', 'small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_medium', 'medium'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_large', 'large'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_large', 'extra-large'],
            ],
        ]
    ],
    'space_after_class' => [
        'config' => [
            'renderType' => 'selectSingleWithTypoScriptPlaceholderT3bootstrap',
            'typoscriptPath' => 'content.margin.bottom',
            'items' => [
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_none', 'none'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_small', 'extra-small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_small', 'small'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_medium', 'medium'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_large', 'large'],
                ['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_extra_large', 'extra-large'],
            ],
        ]
    ]
]);

/* palettes */
$GLOBALS['TCA']['tt_content']['palettes']['imagecols'] = ['showitem' => 'imagecols_xs,imagecols_sm,imagecols_md,imagecols_lg,imagecols_xl,imagecols_xxl'];
$GLOBALS['TCA']['tt_content']['palettes']['icon'] = ['showitem' => 'icon_position,icon,icon_style,--linebreak--,icon_color,icon_size'];
$GLOBALS['TCA']['tt_content']['palettes']['aos'] = ['showitem' => 'aos_effect,aos_easing,aos_once,--linebreak--,aos_duration,aos_delay,aos_offset,--linebreak--,aos_identifiermark', 'isHiddenPalette' => true];
$GLOBALS['TCA']['tt_content']['palettes']['responsive'] = ['showitem' => 'visibility_flags,effects,--linebreak--,full_width,element_classes'];
$GLOBALS['TCA']['tt_content']['palettes']['background'] = ['showitem' => 'fixed_image,bg_color_class,--linebreak--,background_media'];


ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.responsive;responsive', '', 'after:layout');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.background;background', '', 'after:layout');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tt_content.palette.imageColumns;imagecols', 'textmedia,textpic,image', 'after:imageorient');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.icon;icon', '', 'after:header');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:animate-on-scroll.palette;aos', 'textmedia,textpic,image,header,text,wst3bootstrap_buttongroup,card,wst3bootstrap_card,wst3bootstrap_cards,wst3bootstrap_alert,wst3bootstrap_container,wst3bootstrap_fluidrow', 'after:layout');
//ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:palette.responsive;responsive', '', '');


ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'image_classes', 'textmedia,textpic,image', 'after:layout');


ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'padding_top_class,padding_bottom_class', 'after:space_after_class');



$GLOBALS['TCA']['tt_content']['palettes']['frames']['showitem'] = str_replace('space_before_class;', '--linebreak--,space_before_class;', $GLOBALS['TCA']['tt_content']['palettes']['frames']['showitem']);


/* all */
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:tab.seo,image_loading_behaviour', '');

/* uploads */
ExtensionManagementUtility::addFieldsToPalette('tt_content', 'uploads', 'filelink_sorting_direction', 'after:filelink_sorting');

/* form */
ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'form_layout', 'form_formframework', 'after:layout');

/* textmedia,textpic */
ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'grid_layout', 'textmedia,textpic', 'after:imageorient');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'grid_layout_responsive_bg', 'textmedia,textpic', 'after:grid_layout');

/* menu_sitemap_pages */
ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'levels', 'menu_sitemap_pages', 'after:pages');

/* wst3bootstrap_container */

ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'html_tag', 'after:layout');
