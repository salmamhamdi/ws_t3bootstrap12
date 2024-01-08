<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers;


use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;


class ColumnViewHelper extends ImageScaleViewHelper
{

    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('class', 'string', 'Additional classes', false);
        $this->registerArgument('id', 'string', 'ID', false);
        $this->registerArgument('tag', 'string', '', false);
        $this->registerArgument('role', 'string', '', false);
        $this->registerArgument('display', 'string', '', false, 'block');

        $this->registerArgument('xs-order', 'int', '', false);
        $this->registerArgument('sm-order', 'int', '', false);
        $this->registerArgument('md-order', 'int', '', false);
        $this->registerArgument('lg-order', 'int', '', false);
        $this->registerArgument('xl-order', 'int', '', false);
        $this->registerArgument('xxl-order', 'int', '', false);

    }


    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {

        if ($this->arguments['tag'] === '') return parent::render();

        $strClass = '';
        $strClass .= ($this->xs === 0) ? ' d-none' : ' d-' . $this->arguments['display'] . ' col-' . $this->xs;
        $strClass .= ($this->sm === 0) ? ' d-sm-none' : ' d-sm-' . $this->arguments['display'] . ' col-sm-' . $this->sm;
        $strClass .= ($this->md === 0) ? ' d-md-none' : ' d-md-' . $this->arguments['display'] . ' col-md-' . $this->md;
        $strClass .= ($this->lg === 0) ? ' d-lg-none' : ' d-lg-' . $this->arguments['display'] . ' col-lg-' . $this->lg;
        $strClass .= ($this->xl === 0) ? ' d-xl-none' : ' d-xl-' . $this->arguments['display'] . ' col-xl-' . $this->xl;
        $strClass .= ($this->xxl === 0) ? ' d-xxl-none' : ' d-xxl-' . $this->arguments['display'] . ' col-xxl-' . $this->xxl;
        $strClass .= ' ' . $this->arguments['class'];


        if ($this->arguments['xs-order'] !== null) {
            $strClass .= ' order-' . $this->arguments['xs-order'];
        }
        if ($this->arguments['sm-order'] !== null) {
            $strClass .= ' order-sm-' . $this->arguments['sm-order'];
        }
        if ($this->arguments['md-order'] !== null) {
            $strClass .= ' order-md-' . $this->arguments['md-order'];
        }
        if ($this->arguments['lg-order'] !== null) {
            $strClass .= ' order-lg-' . $this->arguments['lg-order'];
        }
        if ($this->arguments['xl-order'] !== null) {
            $strClass .= ' order-xl-' . $this->arguments['xl-order'];
        }
        if ($this->arguments['xxl-order'] !== null) {
            $strClass .= ' order-xxl-' . $this->arguments['xxl-order'];
        }

        $output = parent::render();

        $tagBuilder = new TagBuilder($this->arguments['tag'] ?? 'div', $output);
        $tagBuilder->addAttribute('class', $strClass);
        if ($this->arguments['role'] !== null) {
            $tagBuilder->addAttribute('role', $this->arguments['role']);
        }
        if ($this->arguments['id'] !== null) {
            $tagBuilder->addAttribute('id', $this->arguments['id']);
        }
        $output = $tagBuilder->render();

        return $output;
    }


}
