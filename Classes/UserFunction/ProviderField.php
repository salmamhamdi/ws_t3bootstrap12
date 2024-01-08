<?php

namespace WapplerSystems\WsT3bootstrap\UserFunction;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use WapplerSystems\WsT3bootstrap\Service\ConfigurationService;


class ProviderField
{


    /**
     * @var ConfigurationService
     */
    protected $configurationService;


    /**
     * @param ConfigurationService $configurationService
     * @return void
     */
    public function injectConfigurationService(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var ConfigurationService $configurationService */
        $configurationService = GeneralUtility::makeInstance(ConfigurationService::class);
        $this->injectConfigurationService($configurationService);
    }


    public function getImageColumnNumberItems($config)
    {
        $settings = $this->configurationService->getSettingsForExtensionName('ws_t3bootstrap');

        $gridColumns = isset($settings['gridColumns']) ? $settings['gridColumns'] : 12;

        $optionList = [];
        for ($i = $gridColumns; $i > 0; $i--) {
            if ($gridColumns % $i == 0) {
                $optionList[] = [(string)$i, $i];
            }
        }

        $config['items'] = array_merge($config['items'], $optionList);
        return $config;
    }


}
