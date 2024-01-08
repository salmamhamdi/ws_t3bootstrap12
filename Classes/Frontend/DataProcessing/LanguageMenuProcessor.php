<?php
namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;


use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 *
 *
 */
class LanguageMenuProcessor extends \TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor
{

    /**
     * @var array
     */
    protected $menuDefaults = [
        'as' => 'languagemenu',
        'hideCurrent' => '1',
        'hideUnavailable' => '1',
    ];

    /**
     * Allowed configuration keys for menu generation, other keys
     * will throw an exception to prevent configuration errors.
     *
     * @var array
     */
    protected $allowedConfigurationKeys = [
        'if',
        'if.',
        'languages',
        'languages.',
        'as',
        'addQueryString',
        'addQueryString.',
        'hideCurrent',
        'hideUnavailable',
    ];

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        $this->cObj = $cObj;
        $this->processorConfiguration = $processorConfiguration;

        // Get Configuration
        $this->menuTargetVariableName = $this->getConfigurationValue('as');

        $hideCurrent = (boolean)$this->getConfigurationValue('hideCurrent');
        $hideUnavailable = (boolean)$this->getConfigurationValue('hideUnavailable');

        // Validate and Build Configuration
        $this->validateAndBuildConfiguration();

        // Process Configuration
        $menuContentObject = $this->contentObjectFactory->getContentObject('HMENU', $cObj->getRequest(), $cObj);
        $renderedMenu = $menuContentObject?->render($this->menuConfig);
        if (!$renderedMenu) {
            return $processedData;
        }

        // Process menu
        $menu = json_decode($renderedMenu, true);
        $menu = array_filter($menu, function ($item) use ($hideCurrent, $hideUnavailable) {
            if ($hideCurrent && $item['active'] === 1) {
                return false;
            }
            if ($hideUnavailable && $item['available'] === 0) {
                return false;
            }
            return true;
        });
        $processedMenu = [];
        if (is_iterable($menu)) {
            foreach ($menu as $key => $language) {
                $processedMenu[$key] = $language;
            }
        }

        // Return processed data
        $processedData[$this->menuTargetVariableName] = $processedMenu;
        return $processedData;
    }

    /**
     * @var array
     */
    protected $menuLevelConfig = [
        'doNotLinkIt' => '1',
        'wrapItemAndSub' => '{|}, |*| {|}, |*| {|}',
        'stdWrap.' => [
            'cObject' => 'COA',
            'cObject.' => [
                '1' => 'LOAD_REGISTER',
                '1.' => [
                    'languageId.' => [
                        'cObject' => 'TEXT',
                        'cObject.' => [
                            'value.' => [
                                'data' => 'register:languages_HMENU'
                            ],
                            'listNum.' => [
                                'stdWrap.' => [
                                    'data' => 'register:count_HMENU_MENUOBJ',
                                    'wrap' => '|-1'
                                ],
                                'splitChar' => ','
                            ]
                        ]
                    ]
                ],
                '10' => 'TEXT',
                '10.' => [
                    'stdWrap.' => [
                        'data' => 'register:languageId'
                    ],
                    'wrap' => '"languageId":|'
                ],
                '11' => 'USER',
                '11.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'locale',
                    'stdWrap.' => [
                        'wrap' => ',"locale":|'
                    ]
                ],
                '20' => 'USER',
                '20.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'title',
                    'stdWrap.' => [
                        'wrap' => ',"title":|'
                    ]
                ],
                '21' => 'USER',
                '21.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'navigationTitle',
                    'stdWrap.' => [
                        'wrap' => ',"navigationTitle":|'
                    ]
                ],
                '22' => 'USER',
                '22.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'twoLetterIsoCode',
                    'stdWrap.' => [
                        'wrap' => ',"twoLetterIsoCode":|'
                    ]
                ],
                '23' => 'USER',
                '23.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'hreflang',
                    'stdWrap.' => [
                        'wrap' => ',"hreflang":|'
                    ]
                ],
                '24' => 'USER',
                '24.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'direction',
                    'stdWrap.' => [
                        'wrap' => ',"direction":|'
                    ]
                ],
                '30' => 'USER',
                '30.' => [
                    'userFunc' => 'TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor->getFieldAsJson',
                    'language.' => [
                        'data' => 'register:languageId'
                    ],
                    'field' => 'flag',
                    'stdWrap.' => [
                        'substring' => '7,0',
                        'wrap' => ',"flag":"|',
                    ]
                ],
                '90' => 'TEXT',
                '90.' => [
                    'value' => self::LINK_PLACEHOLDER,
                    'wrap' => ',"link":|',
                ],
                '91' => 'TEXT',
                '91.' => [
                    'value' => '0',
                    'wrap' => ',"active":|'
                ],
                '92' => 'TEXT',
                '92.' => [
                    'value' => '0',
                    'wrap' => ',"current":|'
                ],
                '93' => 'TEXT',
                '93.' => [
                    'value' => '1',
                    'wrap' => ',"available":|'
                ],
                '99' => 'RESTORE_REGISTER'
            ]
        ]
    ];



}
