<?php
declare(strict_types = 1);

namespace WapplerSystems\WsT3bootstrap\Frontend\Menu\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\MenuProcessor;

class CurrentMenuLevelProcessor extends MenuProcessor {


    protected array $rootline = [];

    public function validateConfiguration()
    {
        $this->allowedConfigurationKeys = array_merge($this->allowedConfigurationKeys, [
            'rootlineVariable',
        ]);

        parent::validateConfiguration();
    }


    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $this->cObj = $cObj;
        $this->processorConfiguration = $processorConfiguration;

        $rootlineVariable = $this->getConfigurationValue('rootlineVariable');
        if ($rootlineVariable !== '') {
            $rootline = $processedData[$rootlineVariable];
            $last = end($rootline);
            if ($last !== false) {
                $this->menuConfig['special.'] = [
                    'value' => $last['data']['pid'],
                ];
            }
        }
        return parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
    }

}
