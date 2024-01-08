<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers;


use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 *
 */
class SetImageLoadingBehaviourViewHelper extends AbstractViewHelper
{

    /**
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var bool
     */
    protected $escapeOutput = false;


    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('mode', 'string', '', false, 'auto');
    }


    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {


        $loading = $GLOBALS['TSFE']->register['imageLoadingBehaviour'] ?? 'auto';

        $loadingNew = $this->arguments['mode'];

        $GLOBALS['TSFE']->register['imageLoadingBehaviour'] = $loadingNew;

        if ($this->templateVariableContainer->exists('imageLoadingBehaviour')) {
            $this->templateVariableContainer->remove('imageLoadingBehaviour');
        }
        $this->templateVariableContainer->add('imageLoadingBehaviour', $loadingNew);

        $output = $this->renderChildren();

        $GLOBALS['TSFE']->register['imageLoadingBehaviour'] = $loading;

        return $output;
    }

}
