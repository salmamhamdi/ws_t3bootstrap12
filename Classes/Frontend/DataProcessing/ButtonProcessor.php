<?php

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

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

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 */
class ButtonProcessor implements DataProcessorInterface
{
    /**
     * Process data of a record to resolve File objects to the view
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $fieldName = $cObj->stdWrapValue('fieldName', $processorConfiguration);
        if (empty($fieldName)) {
            $fieldName = 'flexformValues';
        }
        $flexFormData = $processedData[$fieldName];

        $titleType = $flexFormData['titleType'] ?? '';
        $popoverContent = $flexFormData['popoverContent'] ?? '';

        $additionalAttributes = [];


        $additionalAttributes['data-bs-toggle'] = ($titleType !== 'title') ? $titleType : '';
        if ($popoverContent !== '') {
            $additionalAttributes['data-bs-content'] = $popoverContent;
        }
        if ($additionalAttributes['data-bs-toggle'] !== '') {
            $additionalAttributes['data-bs-placement'] = $flexFormData['tooltipPosition'] ?? '';
        }
        $additionalAttributes['title'] = $flexFormData['title'] ?? '';
        $additionalAttributes['id'] = 'c'.($processedData['data']['_LOCALIZED_UID'] ?? $processedData['data']['uid']);

        $processedData['data']['processedData']['additionalAttributes'] = $additionalAttributes;

        return $processedData;
    }


}
