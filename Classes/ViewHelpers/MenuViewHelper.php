<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Menu\AbstractMenuViewHelper;
use TYPO3\CMS\Frontend\Page\PageRepository;


/**
 * ### Page: Menu ViewHelper
 *
 * ViewHelper for rendering TYPO3 menus in Fluid
 *
 * Supports both automatic, tag-based rendering (which
 * defaults to `ul > li` with options to set both the
 * parent and child tag names. When using manual rendering
 * a range of support CSS classes are available along
 * with each page record.
 */
class MenuViewHelper extends AbstractMenuViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(
            'pageUid',
            'integer',
            'Optional parent page UID to use as top level of menu. If left out will be detected from ' .
            'rootLine using $entryLevel'
        );
        $this->registerArgument(
            'useHtmlspecialchars',
            'boolean',
            'Use htmlspecialchars in link text',
            false,
            true
        );
    }


    /**
     * @param array $page
     * @return string
     */
    protected function renderItemLink(array $page)
    {
        $isSpacer = ($page['doktype'] === PageRepository::DOKTYPE_SPACER);
        $isCurrent = (boolean) $page['current'];
        $isActive = (boolean) $page['active'];
        $linkCurrent = (boolean) $this->arguments['linkCurrent'];
        $linkActive = (boolean) $this->arguments['linkActive'];
        $includeAnchorTitle = (boolean) $this->arguments['includeAnchorTitle'];
        $target = (!empty($page['target'])) ? ' target="' . $page['target'] . '"' : '';
        $class = (trim($page['class']) !== '') ? ' class="' . trim($page['class']) . '"' : '';
        if ($isSpacer || ($isCurrent && !$linkCurrent) || ($isActive && !$linkActive)) {
            $html = $this->arguments['useHtmlspecialchars'] ? htmlspecialchars($page['linktext']) : $page['linktext'];
        } elseif ($includeAnchorTitle) {
            $html = sprintf(
                '<a href="%s" title="%s"%s%s>%s</a>',
                $page['link'],
                $this->arguments['useHtmlspecialchars'] ? htmlspecialchars($page['title']) : $page['title'],
                $class,
                $target,
                $this->arguments['useHtmlspecialchars'] ? htmlspecialchars($page['linktext']) : $page['linktext']
            );
        } else {
            $html = sprintf(
                '<a href="%s"%s%s>%s</a>',
                $page['link'],
                $class,
                $target,
                $this->arguments['useHtmlspecialchars'] ? htmlspecialchars($page['linktext']) : $page['linktext']
            );
        }

        return $html;
    }
}
