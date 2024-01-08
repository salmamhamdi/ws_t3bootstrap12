<?php

namespace WapplerSystems\WsT3bootstrap\EventListener;

use TYPO3\CMS\Backend\Controller\Event\BeforeFormEnginePageInitializedEvent;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AddRequireJsModule
{
    public function __invoke(BeforeFormEnginePageInitializedEvent $event): void
    {
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/WsT3bootstrap/Backend/AosBackendModule');
    }
}
