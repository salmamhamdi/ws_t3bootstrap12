<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Page;



use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 */
class TypeViewHelper extends AbstractViewHelper
{


    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        return $GLOBALS['TSFE']->type;
    }


}
