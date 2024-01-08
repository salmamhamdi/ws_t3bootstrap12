<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Media\Image;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */


/**
 * Returns the width of the provided image file in pixels
 *
 * @author Sven Wappler
 * @package ws_t3bootstrap
 * @subpackage ViewHelpers\Media\Image
 */
class WidthCalculatorViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{


    public function initializeArguments()
    {
        $this->registerArgument('maxWidth', 'int', '', true);
        $this->registerArgument('scale', 'int', '', true);
        $this->registerArgument('hd', 'boolean', '', false);
    }


    /**
     * @return float
     */
    public function render()
    {

        $scale = $this->arguments['scale'];
        if ($scale === 0 || $scale === NULL) $scale = 1;

        return ceil($this->arguments['maxWidth'] * $scale * ($this->arguments['hd'] ? 2 : 1)) - 30;
    }

}
