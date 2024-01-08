<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Tag;


use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * <v:tag name="{f:if(condition: '{v:variable.register.get(name: \'containerless\')} && !{data.full_width}',then:'div',else:'')}" class="container">
 */
class ContainerViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'div';

    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('class', 'string', 'class', false, 'container');
        $this->registerArgument('fullwidth', 'boolean', '', false, false);
    }


    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        if (false === ($GLOBALS['TSFE'] ?? null) instanceof TypoScriptFrontendController) {
            return null;
        }
        $containerless = false;
        if (isset($GLOBALS['TSFE']->register['containerless'])) {
            $containerless = $GLOBALS['TSFE']->register['containerless'];
        }
        if ($containerless && !$this->arguments['fullwidth']) {
            $GLOBALS['TSFE']->register['containerless'] = false;

            $this->tag->forceClosingTag(true);
            $this->tag->addAttribute('class', $this->arguments['class']);
            $this->tag->setContent($this->renderChildren());

            $GLOBALS['TSFE']->register['containerless'] = true;
            return $this->tag->render();
        }
        return $this->renderChildren();
    }


}
