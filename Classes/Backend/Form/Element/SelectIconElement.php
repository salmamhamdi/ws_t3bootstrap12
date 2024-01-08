<?php
namespace WapplerSystems\WsT3bootstrap\Backend\Form\Element;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use WapplerSystems\WsT3bootstrap\Controller\IconSuggestAjaxController;
use WapplerSystems\WsT3bootstrap\Service\TypoScriptService;

/**
 */
class SelectIconElement extends AbstractFormElement
{
    /**
     * Default field information enabled for this element.
     *
     * @var array
     */
    protected $defaultFieldInformation = [
        'tcaDescription' => [
            'renderType' => 'tcaDescription',
        ],
    ];

    /**
     * Default field wizards enabled for this element.
     *
     * @var array
     */
    protected $defaultFieldWizard = [
        'localizationStateSelector' => [
            'renderType' => 'localizationStateSelector',
        ],
        'otherLanguageContent' => [
            'renderType' => 'otherLanguageContent',
            'after' => [
                'localizationStateSelector'
            ],
        ],
        'defaultLanguageDifferences' => [
            'renderType' => 'defaultLanguageDifferences',
            'after' => [
                'otherLanguageContent',
            ],
        ],
    ];

    /**
     * This will render a single-line input form field, possibly with various control/validation features
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render(): array
    {
        $languageService = $this->getLanguageService();

        $typoscript = TypoScriptService::getTypoScript($this->data['parentPageRow']['uid'],0,$this->data['rootline'],$this->data['site']);

        $table = $this->data['tableName'];
        $fieldName = $this->data['fieldName'];
        $row = $this->data['databaseRow'];
        $parameterArray = $this->data['parameterArray'];
        $config = $parameterArray['fieldConf']['config'];
        $elementName = $parameterArray['itemFormElName'];
        $resultArray = $this->initializeResultArray();


        $fieldTSConfig = $parameterArray['fieldTSConfig'] ?? [];

        $itemValue = $parameterArray['itemFormElValue'];
        $evalList = GeneralUtility::trimExplode(',', $config['eval'] ?? '', true);
        $size = MathUtility::forceIntegerInRange($config['size'] ?? $this->defaultInputWidth, $this->minimumInputWidth, $this->maxInputWidth);
        $width = $this->formMaxWidth($size);
        $nullControlNameEscaped = htmlspecialchars('control[active][' . $table . '][' . $row['uid'] . '][' . $fieldName . ']');

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldInformationResult, false);

        $thisfieldId = 't3js-form-field-icon-id' . StringUtility::getUniqueId('formengine-input-');

        $collections = [];
        if (isset($fieldTSConfig['addIconCollection.'])) {

            $addItemsArray = $fieldTSConfig['addIconCollection.'];
            $collections = GeneralUtility::removeDotsFromTS($fieldTSConfig['addIconCollection.']);
            foreach ($collections as $name => $collection) {
                $collections[$name]['label'] = $addItemsArray[$name];
            }
        }
        if (isset($this->data['pageTsConfig']['ws_t3bootstrap.']['iconCollections.']) && is_array($this->data['pageTsConfig']['ws_t3bootstrap.']['iconCollections.'])) {
            foreach ($this->data['pageTsConfig']['ws_t3bootstrap.']['iconCollections.'] as $collectionName => $collectionConfig) {
                $collections[$collectionName] = [
                    'label' => ($collectionConfig['name'] ?? 'no name'),
                    'prefix' => $collectionConfig['prefix'],
                    'dir' => $collectionConfig['dir'],
                ];
            }
        }
        $signature = GeneralUtility::hmac(
            json_encode($collections),
            IconSuggestAjaxController::class
        );

        $value = explode(';',$itemValue);
        $iconFile = $value[0] ?? '';
        if ($iconFile !== '') {
            $iconFile = GeneralUtility::getFileAbsFileName($iconFile);
        }

        $suggestMinimumCharacters = 2;

        $attributes = [
            'id' => $thisfieldId,
            'class' => implode(' ', [
                'form-control',
                't3-form-suggest',
                't3js-clearable',
                'hasDefaultValue',
            ]),
            'name' => htmlspecialchars($elementName)."[placeholder]",
            'data-signature' => htmlspecialchars($signature),
            'data-fieldname' => htmlspecialchars($fieldName),
            'data-fieldtype' => htmlspecialchars($config['type']),
            'data-field' => htmlspecialchars($elementName),
            'data-minchars' => htmlspecialchars((string)$suggestMinimumCharacters),
            'value' => $value[1] ?? null,
            'placeholder' => $languageService->sL('LLL:EXT:ws_t3bootstrap/Resources/Private/Language/Backend.xlf:placeholder.searchForIcon'),
            'data-collections' => json_encode($collections),
            'data-icon-class' => $value[1] ?? null
        ];



        $fieldControlResult = $this->renderFieldControl();
        $fieldControlHtml = $fieldControlResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldControlResult, false);

        $html = [];
        $html[] = '<div class="form-wizards-wrap">';
        $html[] =   '<div class="form-wizards-items-top">';
        $html[] =  '<div class="autocomplete t3-form-suggest-container">';
        $html[] =           '<div class="input-group">';
        $html[] =               '<span class="input-group-addon">';
        if ($iconFile !== '') {
            $html[] = file_get_contents($iconFile);
        }
        $html[] =               '</span>';

        $html[] =               '<input type="search"' . GeneralUtility::implodeAttributes($attributes, true) . ' />';
        $html[] =           '</div>';

        $html[] =          '<input type="hidden" name="' . $parameterArray['itemFormElName'] . '" value="' . htmlspecialchars($itemValue) . '" />';


        $html[] =      '</div>';
        $html[] =  '</div>';
        $html[] = '</div>';
        $fullElement = implode(LF, $html);


        $optionsForModule = [
            'minchars' => 2,
            'signature' => $signature,
        ];

        $resultArray['html'] = '<div class="formengine-field-item t3js-formengine-field-item">' . $fieldInformationHtml . $fullElement . '</div>';


        $resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create(
            '@wapplersystems/ws_t3bootstrap/form-engine/element/select-icon-element.js'
        )->instance($thisfieldId, $optionsForModule);

        return $resultArray;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }


}
