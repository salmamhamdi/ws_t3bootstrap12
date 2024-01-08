<?php

namespace WapplerSystems\WsT3bootstrap\Frontend\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use WapplerSystems\WsT3bootstrap\Service\PageService;

class AjaxMenu implements MiddlewareInterface
{

    /**
     * @var PageService
     */
    protected $pageService;

    protected $showAccessProtected = false;
    protected $showHiddenInMenu = false;
    protected $classAccessProtected = '';
    protected $classAccessGranted = '';
    protected $titleFields = 'nav_title,title';


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        /** @var Site $site */
        $site = $request->getAttribute('site', null);
        /** @var SiteLanguage $language */
        $language = $request->getAttribute('language', null);
        if ($site instanceof Site && str_starts_with($request->getUri()->getPath(), $language->getBase()->getPath() . 'ajaxmenu')) {

            $queryParams = $request->getQueryParams();
            $pageIds = explode(',',$queryParams['pageIds'] ?? '');

            $this->pageService = GeneralUtility::makeInstance(PageService::class);

            $response = '';
            foreach ($pageIds as $pageId) {
                if ((int)$pageId > 0) {

                    $rootLine = $this->pageService->getRootLine(
                        (int)$pageId,
                        false,
                        $this->showAccessProtected
                    );

                    if (in_array($site->getRootPageId(), array_map(function ($array) {
                        return $array['uid'];
                    }, $rootLine), true)) {
                        $response .= $this->renderMenu((int)$pageId);
                    }
                }
            }
            DebugUtility::debug($response);
            exit();
            return new HtmlResponse($response);
        }

        return $handler->handle($request);
    }


    private function renderMenu(int $parentId) {

        $tag = new TagBuilder('ul');
        $tag->addAttribute('data-id',$parentId);

        $pages = $this->pageService->getMenu(
             $parentId
        );
        $menu = $this->parseMenu($pages);
        $tagContent = $this->autoRender($menu);
        $tag->setContent($tagContent);

        return $tag->render();
    }


    /**
     * @param array $menu
     * @param integer $level
     * @return string
     */
    protected function autoRender(array $menu, $level = 1)
    {
        $tagName = $this->arguments['tagNameChildren'];
        $this->tag->setTagName($this->getWrappingTagName());
        $html = [];
        $levels = (integer) $this->arguments['levels'];
        $showCurrent = (boolean) $this->arguments['showCurrent'];
        $expandAll = (boolean) $this->arguments['expandAll'];
        $itemsRendered = 0;
        $numberOfItems = count($menu);
        foreach ($menu as $page) {
            if ($page['current'] && !$showCurrent) {
                continue;
            }
            $class = (trim($page['class']) !== '') ? ' class="' . trim($page['class']) . '"' : '';
            $elementId = ($this->arguments['substElementUid']) ? ' id="elem_' . $page['uid'] . '"' : '';
            if (!$this->isNonWrappingMode()) {
                $html[] = '<' . $tagName . $elementId . $class . '>';
            }
            $html[] = $this->renderItemLink($page);
            if (($page['active'] || $expandAll) && $page['hasSubPages'] && $level < $levels) {
                $subPages = $this->getMenu($page['uid']);
                $subMenu = $this->parseMenu($subPages);
                if (0 < count($subMenu)) {
                    $renderedSubMenu = $this->autoRender($subMenu, $level + 1);
                    $parentTagId = $this->tag->getAttribute('id');
                    if (!empty($parentTagId)) {
                        $this->tag->addAttribute('id', $parentTagId . '-lvl-' . $level);
                    }
                    $this->tag->setTagName($this->getWrappingTagName());
                    $this->tag->setContent($renderedSubMenu);
                    $this->tag->addAttribute(
                        'class',
                        (!empty($this->arguments['class']) ? $this->arguments['class'] . ' lvl-' : 'lvl-') . $level
                    );
                    $html[] = $this->tag->render();
                    $this->tag->addAttribute('class', $this->arguments['class']);
                    if (!empty($parentTagId)) {
                        $this->tag->addAttribute('id', $parentTagId);
                    }
                }
            }
            if (false === $this->isNonWrappingMode()) {
                $html[] = '</' . $tagName . '>';
            }
            $itemsRendered++;
            if (true === isset($this->arguments['divider']) && $itemsRendered < $numberOfItems) {
                $divider = $this->arguments['divider'];
                if (!$this->isNonWrappingMode()) {
                    $html[] = '<' . $tagName . '>' . $divider . '</' . $tagName . '>';
                } else {
                    $html[] = $divider;
                }
            }
        }

        return implode(LF, $html);
    }


    /**
     * @param array $page
     * @return string
     */
    protected function renderItemLink(array $page)
    {
        $isSpacer = ($page['doktype'] === $this->pageService->readPageRepositoryConstant('DOKTYPE_SPACER'));
        $isCurrent = (boolean) $page['current'];
        $isActive = (boolean) $page['active'];
        $linkCurrent = (boolean) $this->arguments['linkCurrent'];
        $linkActive = (boolean) $this->arguments['linkActive'];
        $includeAnchorTitle = (boolean) $this->arguments['includeAnchorTitle'];
        $target = (!empty($page['target'])) ? ' target="' . $page['target'] . '"' : '';
        $class = (trim($page['class']) !== '') ? ' class="' . trim($page['class']) . '"' : '';
        if ($isSpacer || ($isCurrent && !$linkCurrent) || ($isActive && !$linkActive)) {
            $html = htmlspecialchars($page['linktext']);
        } elseif ($includeAnchorTitle) {
            $html = sprintf(
                '<a href="%s" title="%s"%s%s>%s</a>',
                $page['link'],
                htmlspecialchars($page['title']),
                $class,
                $target,
                htmlspecialchars($page['linktext'])
            );
        } else {
            $html = sprintf(
                '<a href="%s"%s%s>%s</a>',
                $page['link'],
                $class,
                $target,
                htmlspecialchars($page['linktext'])
            );
        }

        return $html;
    }

    /**
     * @param array $pages
     * @return array
     */
    public function parseMenu(array $pages)
    {
        $count = 0;
        $total = count($pages);
        $processedPages = [];
        foreach ($pages as $index => $page) {
            $count++;
            $class = [];
            $originalPageUid = $page['uid'];
            $showAccessProtected = (boolean) $this->showAccessProtected;
            if ($showAccessProtected) {
                $pages[$index]['accessProtected'] = $this->pageService->isAccessProtected($page);
                if (true === $pages[$index]['accessProtected']) {
                    $class[] = $this->classAccessProtected;
                }
                $pages[$index]['accessGranted'] = $this->pageService->isAccessGranted($page);
                if (true === $pages[$index]['accessGranted'] && true === $this->pageService->isAccessProtected($page)) {
                    $class[] = $this->classAccessGranted;
                }
            }
            $targetPage = $this->pageService->getShortcutTargetPage($page);
            if ($targetPage !== null) {
                if ($this->pageService->shouldUseShortcutTarget($this->arguments)) {
                    $pages[$index] = $targetPage;
                }
                if ($this->pageService->shouldUseShortcutUid($this->arguments)) {
                    $pages[$index]['uid'] = $targetPage['uid'];
                }
            }
            $pages[$index]['class'] = implode(' ', $class);
            $pages[$index]['linktext'] = $this->getItemTitle($pages[$index]);
            $forceAbsoluteUrl = $this->arguments['forceAbsoluteUrl'];
            $pages[$index]['link'] = $this->pageService->getItemLink($pages[$index], $forceAbsoluteUrl);
            $processedPages[$index] = $pages[$index];
        }

        return $processedPages;
    }


    /**
     * @param null|integer $pageUid
     * @param integer $entryLevel
     * @return array
     */
    public function getMenu($pageUid = null, $entryLevel = 0)
    {
        $pageUid = $this->determineParentPageUid($pageUid, $entryLevel);
        if ($pageUid === null) {
            return [];
        }
        $showHiddenInMenu = (boolean) $this->showHiddenInMenu;
        $showAccessProtected = (boolean) $this->arguments['showAccessProtected'];
        $includeSpacers = (boolean) $this->arguments['includeSpacers'];
        $excludePages = $this->processPagesArgument($this->arguments['excludePages']);

        return $this->pageService->getMenu(
            $pageUid,
            $excludePages,
            $showHiddenInMenu,
            $includeSpacers,
            $showAccessProtected
        );
    }


    /**
     * @param null|integer $pageUid
     * @param integer $entryLevel
     * @return null|integer
     */
    protected function determineParentPageUid($pageUid = null, $entryLevel = 0)
    {
        $rootLineData = $this->pageService->getRootLine();
        if (null === $pageUid) {
            if (null !== $entryLevel) {
                if ($entryLevel < 0) {
                    $entryLevel = count($rootLineData) - 1 + $entryLevel;
                }
                $pageUid = $rootLineData[$entryLevel]['uid'];
            } else {
                $pageUid = $GLOBALS['TSFE']->id;
            }
        }

        return $pageUid;
    }


    /**
     * Returns array of page UIDs from provided pages
     *
     * @param mixed $pages
     * @return array
     */
    public function processPagesArgument($pages = null)
    {
        if (null === $pages) {
            $pages = $this->arguments['pages'];
        }
        if (true === $pages instanceof \Traversable) {
            $pages = iterator_to_array($pages);
        } elseif (true === is_string($pages)) {
            $pages = GeneralUtility::trimExplode(',', $pages, true);
        } elseif (true === is_int($pages)) {
            $pages = (array) $pages;
        }
        if (false === is_array($pages)) {
            return [];
        }

        return $pages;
    }


    /**
     * @param array $page
     * @return string
     */
    protected function getItemTitle(array $page)
    {
        $titleFieldList = GeneralUtility::trimExplode(',', $this->arguments['titleFields']);
        foreach ($titleFieldList as $titleFieldName) {
            if (false === empty($page[$titleFieldName])) {
                return $page[$titleFieldName];
            }
        }

        return $page['title'];
    }

}

