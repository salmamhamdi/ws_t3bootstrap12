<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Menu;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Exception;
use FluidTYPO3\Vhs\ViewHelpers\Menu\AbstractMenuViewHelper;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use WapplerSystems\WsT3bootstrap\Frontend\Category\Collection\CategoryCollection;

/**
 * A view helper which returns pages related by categories to the current or another page
 *
 * = Example =
 *
 * <code title="Pages with categories 1 and 2 assigned">
 * <ws:menu.relatedPages as="relatedPages">
 *   <f:for each="{relatedPages}" as="relatedPage">
 *     {relatedPage.title}
 *   </f:for>
 * </ws:menu.relatedPages>
 * </code>
 *
 */
class RelatedPagesViewHelper extends AbstractMenuViewHelper
{

    /**
     * @var \WapplerSystems\WsT3bootstrap\Domain\Repository\PageRepository
     * @inject
     */
    protected $pageRepository;


    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('as', 'string', 'Name of the template variable that will contain resolved pages', true);
        $this->registerArgument('pageUid', 'integer', 'If specified, this UID will be used to fetch page data instead of using the current page.', FALSE, 0);
    }

    /**
     * @return mixed
     */
    public function render()
    {

        $as = (string)$this->arguments['as'];
        $result = [];

        // Get page via pageUid argument or current id
        $pageUid = (int)$this->arguments['pageUid'];
        if (0 === $pageUid) {
            $pageUid = $GLOBALS['TSFE']->id;
        }

        $page = $this->pageRepository->findByUid($pageUid);


        if ($page) {

            $categories = $page->getCategories();
            /** @var Category $category */
            foreach ($categories as $category) {

                try {
                    $collection = CategoryCollection::load(
                        $category->getUid(),
                        true,
                        'pages',
                        'categories'
                    );
                    if ($collection->count() > 0) {
                        foreach ($collection as $record) {
                            $result[$record['uid']] = $record;
                        }
                    }
                } catch (\RuntimeException $e) {
                    throw new Exception($e->getMessage());
                }

            }
        }

        return $this->renderChildrenWithVariables(array(
            $as => $result
        ));
    }

}
