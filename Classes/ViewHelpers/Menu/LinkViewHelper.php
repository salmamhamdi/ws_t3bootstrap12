<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Menu;


use FluidTYPO3\Vhs\Traits\TagViewHelperTrait;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;


class LinkViewHelper extends AbstractTagBasedViewHelper
{

    use TagViewHelperTrait;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('href', 'string', 'href', true);
        $this->registerArgument('target', 'string', 'target', false, '');
        $this->registerArgument('data-bs-toggle', 'string', 'data-bs-toggle', false, '');
        $this->registerArgument('data-bs-target', 'string', 'data-bs-target', false, '');
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->arguments['class'] = trim((string) $this->arguments['class']);
        $this->arguments['class'] = str_replace(',', ' ', $this->arguments['class']);
        $this->arguments['target'] = trim((string) $this->arguments['target']);
        if ($this->arguments['target'] !== '') {
            $this->tag->addAttribute('target',$this->arguments['target']);
        }
        $this->arguments['href'] = trim((string) $this->arguments['href']);
        $this->tag->addAttribute('href',$this->arguments['href']);

        $this->arguments['data-bs-toggle'] = trim((string) $this->arguments['data-bs-toggle']);
        if ($this->arguments['data-bs-toggle'] !== '') {
            $this->tag->addAttribute('data-bs-toggle',$this->arguments['data-bs-toggle']);
        }

        $this->arguments['data-bs-target'] = trim((string) $this->arguments['data-bs-target']);
        if ($this->arguments['data-bs-target'] !== '') {
            $this->tag->addAttribute('data-bs-target',$this->arguments['data-bs-target']);
        }

        $content = $this->renderChildren();
        return $this->renderTag('a', $content);
    }
}
