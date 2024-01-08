<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Page\Resources;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\Utility\ErrorUtility;
use FluidTYPO3\Vhs\ViewHelpers\Page\Resources\FalViewHelper;
use TYPO3\CMS\Core\Resource\FileReference;

/**
 * Page FAL resource ViewHelper.
 */
class FalFileViewHelper extends FalViewHelper
{


    /**
     * @param FileReference $fileReference
     * @return FileReference
     */
    public function getResource($fileReference)
    {
        return $fileReference;
    }


    /**
     * @return mixed
     */
    public function render()
    {
        $record = $this->arguments['record'];
        $uid = $this->arguments['uid'];

        if (null === $record) {
            if (null === $uid) {
                $record = $this->getActiveRecord();
            } else {
                $record = $this->getRecord($uid);
            }
        }

        if (null === $record) {
            ErrorUtility::throwViewHelperException('No record was found. The "record" or "uid" argument must be specified.', 1384611413);
        }

        // attempt to load resources. If any Exceptions happen, transform them to
        // ViewHelperExceptions which render as an inline text error message.
        try {
            $resources = $this->getResources($record);
        } catch (\Exception $error) {
            // we are doing the pokemon-thing and catching the very top level
            // of Exception because the range of Exceptions that are possibly
            // thrown by the getResources() method in subclasses are not
            // extended from a shared base class like RuntimeException. Thus,
            // we are forced to "catch them all" - but we also output them.
            ErrorUtility::throwViewHelperException($error->getMessage(), $error->getCode());
        }
        return $this->renderChildrenWithVariableOrReturnInput($resources);
    }


}
