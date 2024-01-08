<?php
declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 * 10 = WapplerSystems\WsT3bootstrap\Frontend\DataProcessing\GridLayoutProcessor
 *
 */
class GridLayoutProcessor implements DataProcessorInterface
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
        $key = $processedData['data']['grid_layout'];
        $bgOverlay = $processedData['data']['grid_layout_responsive_bg'];

        if ($key === '') {
            $key = $contentObjectConfiguration['settings.']['wst3bootstrap.']['defaultGridLayout'] ?? '';
        }

        if ($bgOverlay === '') {
            $bgOverlay = 'no-responsive-bg';
        }
        $processedData['bgImageOverlay'] = $bgOverlay;

        if ($key !== '') {

            $gridLayouts = $contentObjectConfiguration['settings.']['wst3bootstrap.']['gridLayouts.'] ?? [];
            $gridLayouts = GeneralUtility::removeDotsFromTS($gridLayouts);

            if (isset($gridLayouts[$key])) {
                $floating = false;
                $layout = $gridLayouts[$key];

                $key2 = $key;
                if (substr($key,-2) === '-f') {
                    $floating = true;
                    $key2 = substr($key, 0,-2);
                }
                $columns = explode(':', $key2);

                $cols = [];
                foreach ($columns as $colIndex => $column) {
                    $col = [];

                    if (isset($layout['breakPoints'])) {
                        foreach ($layout['breakPoints'] as $bpName => $bpConfig) {
                            $col[$bpName] = [];

                            if (isset($bpConfig['columns'])) {
                                $bpColumns = explode(',', $bpConfig['columns']);
                                if (isset($bpColumns[$colIndex])) {
                                    $col[$bpName]['cols'] = (int)$bpColumns[$colIndex];
                                }
                            }
                            if (isset($bpConfig['orders'])) {
                                $bpOrders = explode(',', $bpConfig['orders']);
                                if (isset($bpOrders[$colIndex])) {
                                    $col[$bpName]['order'] = (int)$bpOrders[$colIndex];
                                }
                            }
                        }
                    }
                    $cols[] = $col;
                }
                $counter = 0;

                if (substr_count($key, 'm') > 0) {
                    $cols[$counter]['isSingleMedia'] = true;
                    $cols[$counter]['files'] = array_shift($processedData['files']);
                    if (substr_count($key, 'b') > 0) {
                        $cols[$counter]['asBackground'] = true;
                    }
                    $counter++;
                }
                if (substr_count($key, 'm') > 1) {
                    $cols[$counter]['isSingleMedia'] = true;
                    $cols[$counter]['files'] = array_shift($processedData['files']);
                    $counter++;
                }
                if (strpos($key, 'g') !== false) {
                    $cols[$counter]['isGallery'] = true;
                    $cols[$counter]['files'] = $processedData['files'];
                    $counter++;
                }
                if (strpos($key, 't') !== false) {
                    $cols[$counter]['isText'] = true;
                }

                $processedData['gridLayoutColumns'] = $cols;
                $processedData['grid_layout'] = $layout;
                $processedData['floating'] = $floating;
            }
            $processedData['layoutKey'] = str_replace(':','-',$key);
        }

        return $processedData;
    }
}
