<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Format;


class RemoveLinebreaksViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{


    public function render()
    {
        $content = $this->renderChildren();
        return str_replace("\n", " ", $content);
    }
}
