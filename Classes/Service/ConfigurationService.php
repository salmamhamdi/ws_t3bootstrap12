<?php
namespace WapplerSystems\WsT3bootstrap\Service;


use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class ConfigurationService
 */
class ConfigurationService implements SingletonInterface {

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;


    /**
     * @param ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns the plugin.tx_extsignature.settings array.
     * Accepts any input extension name type.
     *
     * @param string $extensionName
     * @return array
     */
    public function getSettingsForExtensionName($extensionName)
    {
        $signature = str_replace('_', '', $extensionName);
        return (array) $this->getTypoScriptByPath('plugin.tx_' . $signature . '.settings');
    }


    /**
    * Gets the value/array from global TypoScript by
    * dotted path expression.
    *
    * @param string $path
    * @return array
    */
    public function getTypoScriptByPath($path)
    {
        $all = (array) $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $value = &$all;
        foreach (explode('.', $path) as $segment) {
            $value = ($value[$segment . '.'] ?? $value[$segment] ?? null);
            if ($value === null) {
                break;
            }
        }
        if (is_array($value)) {
            return GeneralUtility::removeDotsFromTS($value);
        }
        return $value;
    }

}
