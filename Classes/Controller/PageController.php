<?php

namespace WapplerSystems\WsT3bootstrap\Controller;


use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Page Controller
 *
 * @route off
 */
class PageController extends \FluidTYPO3\Flux\Controller\PageController
{


    public function initializeViewVariables(\TYPO3Fluid\Fluid\View\ViewInterface $view): void
    {

        $config = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        if (\is_array($config['variables'])) {
            $this->view->assignMultiple($config['variables']);
        }
        parent::initializeViewVariables($view);
    }

}
