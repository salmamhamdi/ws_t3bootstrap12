<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Picture;


use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 *
 */
class SourceViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'source';


    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {

        parent::initializeArguments();

        $this->registerArgument('scale', 'float', '', true, 1);
        $this->registerArgument('media', 'string', '', false);


        $this->registerArgument('src', 'string', 'a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead');
        $this->registerArgument('treatIdAsReference', 'bool', 'given src argument is a sys_file_reference record');
        $this->registerArgument('image', 'object', 'a FAL object');
        $this->registerArgument('crop', 'string|bool', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
        $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');

        $this->registerArgument('width', 'string', 'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('height', 'string', 'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum height of the image');
        $this->registerArgument('maxWidth', 'int', 'maximum width of the image');
        $this->registerArgument('maxHeight', 'int', 'maximum height of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
        $this->registerArgument('storeRatioAs', 'string', 'Store ratio as variable', false);
        $this->registerArgument('ratioToCSS', 'bool', 'Store ratio as css', false);
        $this->registerArgument('id', 'string', 'The id. Necessary for css', false);

    }

    public function setArguments(array $arguments)
    {
        parent::setArguments($arguments);

        if ($this->arguments['scale'] === 0 || $this->arguments['scale'] === NULL) $this->arguments['scale'] = 1;

        $this->arguments['maxWidth'] = ceil($this->arguments['maxWidth'] * $this->arguments['scale']);
    }


    /**
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        if (($this->arguments['src'] === null && $this->arguments['image'] === null) || ($this->arguments['src'] !== null && $this->arguments['image'] !== null)) {
            throw new Exception('You must either specify a string src or a File object.', 1382284106);
        }

        $imageService = GeneralUtility::makeInstance(ImageService::class);

        try {
            $image = $imageService->getImage($this->arguments['src'] ?? '', $this->arguments['image'], $this->arguments['treatIdAsReference'] ?? false);
            $cropString = $this->arguments['crop'];
            if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }
            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $this->arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);

            $processingInstructions = [
                'width' => $this->arguments['width'],
                'height' => $this->arguments['height'],
                'minWidth' => $this->arguments['minWidth'],
                'minHeight' => $this->arguments['minHeight'],
                'maxWidth' => $this->arguments['maxWidth'],
                'maxHeight' => $this->arguments['maxHeight'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
            $imageUri = $imageService->getImageUri($processedImage, $this->arguments['absolute']);

            if ($processedImage->getProperty('height') > 0) {
                $ratio = round($processedImage->getProperty('width') / $processedImage->getProperty('height'),2);
            } else {
                $ratio = 1;
            }

            if ($this->arguments['ratioToCSS'] ?? false) {
                if (($this->arguments['id'] ?? '') === '') {
                    throw new Exception('no id given', 1660865459);
                }

                $content = '@media '.$this->arguments['media'].'{';
                $content .= '#'.$this->arguments['id'].'{';
                $content .= 'aspect-ratio:'.$ratio.'}}';
                $name = md5($content);
                GeneralUtility::makeInstance(AssetCollector::class)->addInlineStyleSheet($name,$content);
            }

            if ($this->arguments['storeRatioAs'] !== null && $this->arguments['storeRatioAs'] !== '') {
                $GLOBALS['TSFE']->register[$this->arguments['storeRatioAs']] = $ratio;
            }

            if ($this->arguments['maxWidth'] * 2 < $image->getProperty('width')) {

                $processingInstructions = [
                    'width' => $this->arguments['width'],
                    'height' => $this->arguments['height'],
                    'minWidth' => $this->arguments['minWidth'],
                    'minHeight' => $this->arguments['minHeight'],
                    'maxWidth' => $this->arguments['maxWidth'] * 2,
                    'maxHeight' => $this->arguments['maxHeight'],
                    'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
                ];
                $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
                $imageUriHD = $imageService->getImageUri($processedImage, $this->arguments['absolute']);

                $this->tag->addAttribute('srcset', $imageUri . ', '.$imageUriHD.' 2x');
            } else {
                $this->tag->addAttribute('srcset', $imageUri);
            }

            $this->tag->addAttribute('media', $this->arguments['media'] );

        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new Exception($e->getMessage(), 1509741911, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new Exception($e->getMessage(), 1509741912, $e);
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
            throw new Exception($e->getMessage(), 1509741913, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new Exception($e->getMessage(), 1509741914, $e);
        }

        $output = '<!-- scale: ' . $this->arguments['scale'] . ', maxWidth: ' . $this->arguments['maxWidth'] . 'px, maxWidthHD: ' . ($this->arguments['maxWidth'] * 2) . 'px -->';
        $output .= $this->tag->render();

        return $output;
    }


}
