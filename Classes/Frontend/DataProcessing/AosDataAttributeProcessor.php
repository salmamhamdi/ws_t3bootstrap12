<?php
declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 * 10 = WapplerSystems\WsT3bootstrap\Frontend\DataProcessing\AosDataAttributeProcessor
 *
 */
class AosDataAttributeProcessor implements DataProcessorInterface
{

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $dataAttributes = [];

        /* Effect */
        if (isset($processedData['data']['aos_effect']) && ($processedData['data']['aos_effect'] === 'none' || empty($processedData['data']['aos_effect']))) {
            return $processedData;
        }
        $dataAttributes['aos'] = $processedData['data']['aos_effect'] ?? '';

        /* Easing */
        if (!empty($processedData['data']['aos_easing'])) {
            $dataAttributes['aos-easing'] = $processedData['data']['aos_easing'];
        } else {
            $dataAttributes['aos-easing'] = 'ease-in-out';
        }

        /* Once */
        $dataAttributes['aos-once'] = ((int)($processedData['data']['aos_once'] ?? 0) === 1) ? 'true' : 'false';

        /* Duration */
        /* Depending on the AOS CSS there are only stepsizes by 50 in duration and only values between 0 and 3000 allowed */
        if (!empty($processedData['data']['aos_duration'])) {

            $dataAttributes['aos-duration'] = $processedData['data']['aos_duration'];

            if ($dataAttributes['aos-duration'] % 50 !== 0) {
                $dataAttributes['aos-duration'] -= $dataAttributes['aos-duration'] % 50;
            }

            if ($dataAttributes['aos-duration'] < 0) {
                $dataAttributes['aos-duration'] = 0;
            } else if ($dataAttributes['aos-duration'] > 3000) {
                $dataAttributes['aos-duration'] = 3000;
            }

        }

        /* Delay */
        if (!empty($processedData['data']['aos_delay'])) {
            $dataAttributes['aos-delay'] = $processedData['data']['aos_delay'];
        }

        /* Offset */
        if (!empty($processedData['data']['aos_offset'])) {
            $dataAttributes['aos-offset'] = $processedData['data']['aos_offset'];
        }

        if (!isset($processedData['data_attributes'])) {
            $processedData['data_attributes'] = [];
        }
        if (count($dataAttributes) > 0) {
            $processedData['loadAos'] = true;
        }

        $processedData['data_attributes'] = array_merge($processedData['data_attributes'],$dataAttributes);
        $processedData['aos_data_attributes'] = $dataAttributes; // backward compatibility
        return $processedData;
    }
}
