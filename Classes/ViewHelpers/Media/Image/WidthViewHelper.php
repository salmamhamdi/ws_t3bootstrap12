<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Media\Image;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\ViewHelpers\Media\Image\AbstractImageViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

/**
 * Returns the width of the provided image file in pixels
 *
 * @author Sven Wappler
 * @package ws_t3bootstrap
 * @subpackage ViewHelpers\Media\Image
 */
class WidthViewHelper extends AbstractImageViewHelper
{

    /**
     * @return int
     */
    public function render()
    {
        $this->preprocessImage();

        if (filter_var($this->imageInfo[3], FILTER_VALIDATE_URL) !== false) {
            $file = $this->imageInfo[3];
        } else {
            $file = GeneralUtility::getFileAbsFileName(urldecode($this->imageInfo[3]));
            if (FALSE === file_exists($file) || TRUE === is_dir($file)) {
                throw new Exception('Cannot determine info for "' . $file . '". File does not exist or is a directory.', 1357066532);
            }
        }
        $imageSize = getimagesize($file);
        return $imageSize[0];
    }

}
