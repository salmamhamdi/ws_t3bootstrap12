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

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 */
class ButtonGroupProcessor implements DataProcessorInterface
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

        $flexFormData = $processedData['data']['pi_flexform_array'] ?? [];

        $class = '';

        switch ($flexFormData['type'] ?? '') {
            case 'group':
                $class .= 'btn-group'.((($flexFormData['direction'] ?? '') === 'vertical') ? '-vertical' : '').((($flexFormData['size'] ?? '') !== 'md') ? ' btn-group-'.($flexFormData['size'] ?? '') : '');
                break;
            case 'grid':
                $class .= 'd-grid gap-'.$flexFormData['gap'].' w-100';
                break;
        }

        switch ($flexFormData['alignment'] ?? '') {
            case 'end':
                $class .= ' align-self-end';
                break;
            case 'center':
                $class .= ' align-self-center';
                break;
            case 'block':
                break;
            default:
                $class .= ' align-self-start';
        }

        $breakpoint = '';
        if (($flexFormData['breakpoint'] ?? 'xs') !== 'xs') {
            $breakpoint = $flexFormData['breakpoint'].'-';
        }

        switch ($flexFormData['arrangement'] ?? '') {
            case 'end':
                $class .= ' d-'.$breakpoint.'flex justify-content-'.$breakpoint.'end';
                break;
            case 'center':
                $class .= ' d-'.$breakpoint.'flex justify-content-'.$breakpoint.'center';
                break;
            case 'start':
                $class .= ' d-'.$breakpoint.'flex justify-content-'.$breakpoint.'start';
                break;
            case 'space-between':
                $class .= ' d-'.$breakpoint.'flex justify-content-'.$breakpoint.'between';
                break;
            case 'space-around':
                $class .= ' d-'.$breakpoint.'flex justify-content-'.$breakpoint.'around';
                break;
        }

        $processedData['data']['processedData']['class'] = $class;

        return $processedData;
    }


}
