<?php
namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;


use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;


class AjaxMenuParameterProcessor implements DataProcessorInterface
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
        $processedData['pageIds'] = [];
        if (($_GET['pageIds'] ?? '') !== '') {
            $processedData['pageIds'] = explode(',',$_GET['pageIds'] ?? '');
        }
        return $processedData;
    }


}
