<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Bootstrap;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * ViewHelper to access data of the current page record.
 */
class ColumnConverterViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var ConfigurationManagerInterface
     */
    protected static $configurationManager;


    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'columns',
            'integer',
            '',
        );
        $this->registerArgument(
            'round',
            'string',
            '',
            false,
            'up'
        );
        $this->registerArgument(
            'fallback',
            'integer',
            'Use this value if column is 0/null',
            false,
            1
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array                     $arguments,
        \Closure                  $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    )
    {
        $columns = $renderChildrenClosure();
        if ((int)$columns === 0) {
            $columns = $arguments['fallback'];
        }

        $path = 'plugin.tx_wst3bootstrap.settings';

        $all = static::getConfigurationManager()->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $segments = explode('.', $path);
        $value = $all;
        foreach ($segments as $path) {
            $value = (true === isset($value[$path . '.']) ? $value[$path . '.'] : $value[$path]);
        }
        if (true === is_array($value)) {
            $value = GeneralUtility::removeDotsFromTS($value);
        }

        $gridColumns = (double)($value['gridColumns'] ?? 12);

        return (int)($gridColumns / $columns);
    }


    /**
     * Returns instance of the configuration manager
     *
     * @return ConfigurationManagerInterface
     */
    protected static function getConfigurationManager()
    {
        if (null !== static::$configurationManager) {
            return static::$configurationManager;
        }
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        static::$configurationManager = $configurationManager;
        return $configurationManager;
    }


}
