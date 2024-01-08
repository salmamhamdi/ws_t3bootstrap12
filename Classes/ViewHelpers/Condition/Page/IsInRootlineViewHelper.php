<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Condition\Page;


use FluidTYPO3\Vhs\Service\PageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 */
class IsInRootlineViewHelper extends AbstractConditionViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('pageUid', 'integer', 'value to check', false, null);
    }

    /**
     * @param array $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {

        $pageUid = $GLOBALS['TSFE']->id;
        $rootline = static::getPageService()->getRootLine($pageUid);

        foreach ($rootline as $page) {
            if ((int)$page['uid'] === (int)$arguments['pageUid']) return true;
        }
        return false;
    }

    /**
     * @return PageService
     */
    protected static function getPageService(): PageService
    {
        return GeneralUtility::makeInstance(PageService::class);
    }
}
