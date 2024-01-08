<?php
namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Media\Image;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;


/**
 * Returns the width of the provided image file in pixels
 *
 * @author Sven Wappler
 * @package ws_t3bootstrap
 * @subpackage ViewHelpers\Media\Image
 */
class MetaViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {


    /**
     * @var \TYPO3\CMS\Extbase\Service\ImageService
     */
    protected $imageService;

    /**
     * @param \TYPO3\CMS\Extbase\Service\ImageService $imageService
     */
    public function injectImageService(\TYPO3\CMS\Extbase\Service\ImageService $imageService)
    {
        $this->imageService = $imageService;
    }


    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('src', 'string', 'a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record');
        $this->registerArgument('image', 'object', 'a FAL object');
        $this->registerArgument('field', 'string', 'field name');
    }


    public function render()
    {
        if ((is_null($this->arguments['src']) && is_null($this->arguments['image'])) || (!is_null($this->arguments['src']) && !is_null($this->arguments['image']))) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('You must either specify a string src or a File object.',
                1382284106);
        }

        try {
            $image = $this->imageService->getImage($this->arguments['src'] ?? '', $this->arguments['image'],
                $this->arguments['treatIdAsReference']);

            if (\strlen($this->arguments['field']) > 0) {
                return $image->getProperty($this->arguments['field']);
            }

            return [
                'copyright' => $image->getProperty('copyright'),
            ];


        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
        }


        return [];
    }
}
