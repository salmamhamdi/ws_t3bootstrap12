<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Page;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\Traits\TemplateVariableViewHelperTrait;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to access data of the current page record.
 */
class InfoViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    use TemplateVariableViewHelperTrait;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerAsArgument();
        $this->registerArgument(
            'pageUid',
            'integer',
            'If specified, this UID will be used to fetch page data instead of using the current page.',
            false,
            0
        );
        $this->registerArgument(
            'field',
            'string',
            'If specified, only this field will be returned/assigned instead of the complete page record.'
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {

        $pageUid = (integer) $arguments['pageUid'];
        if (0 === $pageUid) {
            $pageUid = $GLOBALS['TSFE']->id;
        }
        $page = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages',$pageUid);
        $field = $arguments['field'];
        $content = null;
        if (true === empty($field)) {
            $content = $page;
        } elseif (true === is_array($page) && true === isset($page[$field])) {
            $content = $page[$field];
        }

        return static::renderChildrenWithVariableOrReturnInputStatic(
            $content,
            $arguments['as'],
            $renderingContext,
            $renderChildrenClosure
        );
    }
}
