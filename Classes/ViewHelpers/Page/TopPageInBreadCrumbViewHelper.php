<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Page;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\ViewHelpers\Menu\AbstractMenuViewHelper;

/**
 * ViewHelper to make a breadcrumb link set from a pageUid, automatic or manual.
 */
class TopPageInBreadCrumbViewHelper extends AbstractMenuViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->overrideArgument(
            'as',
            'string',
            'If used, stores the menu pages as an array in a variable named after this value and renders the tag ' .
            'content. If the tag content is empty automatic rendering is triggered.',
            false,
            'breadcrumb'
        );
    }

    /**
     * @return array
     */
    public function render()
    {
        $pageUid = ($this->arguments['pageUid'] ?? -1) > 0 ? $this->arguments['pageUid'] : $GLOBALS['TSFE']->id;
        $entryLevel = $this->arguments['entryLevel'];
        $rawRootLineData = $this->pageService->getRootLine($pageUid);
        $rawRootLineData = array_reverse($rawRootLineData);
        $rawRootLineData = array_slice($rawRootLineData, $entryLevel, $entryLevel);

        return $rawRootLineData[0]['uid'];
    }
}
