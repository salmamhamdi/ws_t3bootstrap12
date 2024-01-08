<?php

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 */
class FluidRowProcessor implements DataProcessorInterface
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

        $columnsIn = $processedData['data']['pi_flexform_array']['columns'] ?? [];
        $columnsOut = [];

        foreach ($columnsIn as $column) {
            $columnData = $column['column'] ?? [];
            $columnOut = [];
            if (isset($columnData['class-xs']) && strlen($columnData['class-xs']) >= 4) {
                $columnOut['xs'] = substr($columnData['class-xs'], 4);
            } else {
                $columnOut['xs'] = '';
            }
            if (isset($columnData['class-sm']) && strlen($columnData['class-sm']) >= 7) {
                $columnOut['sm'] = substr($columnData['class-sm'], 7);
            } else {
                $columnOut['sm'] = '';
            }
            if (isset($columnData['class-md']) && strlen($columnData['class-md']) >= 7) {
                $columnOut['md'] = substr($columnData['class-md'], 7);
            } else {
                $columnOut['md'] = '';
            }
            if (isset($columnData['class-lg']) && strlen($columnData['class-lg']) >= 7) {
                $columnOut['lg'] = substr($columnData['class-lg'], 7);
            } else {
                $columnOut['lg'] = '';
            }
            if (isset($columnData['class-xl']) && strlen($columnData['class-xl']) >= 7) {
                $columnOut['xl'] = substr($columnData['class-xl'], 7);
            } else {
                $columnOut['xl'] = '';
            }
            if (isset($columnData['class-xxl']) && strlen($columnData['class-xxl']) >= 8) {
                $columnOut['xxl'] = substr($columnData['class-xxl'], 8);
            } else {
                $columnOut['xxl'] = '';
            }

            $columnOut['alignment'] = $columnData['alignment'] ?? '';
            $columnOut['additionalClass'] = $columnData['additionalClass'] ?? '';

            $columnsOut[] = $columnOut;
        }

        $processedData['data']['processedColumns'] = $columnsOut;


        return $processedData;
    }


}
