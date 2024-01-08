<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */


use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;


class BackgroundImageViewHelper extends AbstractViewHelper
{

    use CompileWithRenderStatic;


    /**
     * Constructor
     *
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('selector', 'string', '', true);
        $this->registerArgument('image', 'mixed', '', false);
        $this->registerArgument('src', 'string', '', false);
        $this->registerArgument('treatIdAsReference', 'boolean', '', false, false);
        $this->registerArgument('useBootstrapCropVariants', 'boolean', 'Use Bootstrap compatible CropVariants', false, true);
        $this->registerArgument('cropVariants', 'mixed', '', false, ['xs' => 'default', 'sm' => 'default', 'md' => 'default', 'lg' => 'default', 'xl' => 'default', 'xxl' => 'default', 'full' => 'default']);
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);
        $this->registerArgument('fullscreen', 'bool', 'Render fullscreen image (deprecated)', false, false);
        $this->registerArgument('containerless', 'bool', 'Render image without container', false, false);
        $this->registerArgument('preload', 'bool', 'Adds images to preload section in head', false, false);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {

        $image = $arguments['image'];
        $useBootstrapCropVariants = $arguments['useBootstrapCropVariants'];
        $cropVariants = $arguments['cropVariants'];
        $selector = $arguments['selector'];
        $absolute = $arguments['absolute'];
        $fullscreen = $arguments['fullscreen'] ?? false;
        $containerless = $arguments['containerless'] ?? $fullscreen;
        $preload = $arguments['preload'] ?? false;

        if ($image === null) {
            $src = $arguments['src'];
            $treatIdAsReference = $arguments['treatIdAsReference'];
            $imageService = self::getImageService();
            $image = $imageService->getImage($src, null, $treatIdAsReference);
        }


        $settings = self::getTypoScriptSettings();

        $content = '';

        if ($containerless) {
            $breakpoints = $settings['containerlessBreakpoints'];
            $content .= '/* containerless */' . PHP_EOL;
        } else {
            $breakpoints = $settings['breakpoints'];
        }

        $preloadHrefStack = [];


        foreach ($breakpoints as $breakpoint => $breakpointData) {
            if (!$useBootstrapCropVariants && !isset($cropVariants[$breakpoint])) {
                continue;
            }

            $content .= '@media ' . $breakpointData['mediaquery'] . ' {' . PHP_EOL;
            $content .= $selector . ' {' . PHP_EOL;
            $content .= '/* image width: ' . $breakpointData['imageWidth'] . 'px, cropVariant: ' . ($useBootstrapCropVariants ? $breakpointData['cropVariant'] : ($cropVariants[$breakpoint] ?? 'default')) . ' */' . PHP_EOL;
            $imageUrl = self::processImageUri($image, $breakpointData['imageWidth'], $useBootstrapCropVariants ? $breakpointData['cropVariant'] : ($cropVariants[$breakpoint] ?? 'default'), $absolute);
            if ($preload) {
                $preloadHrefStack[] = [$imageUrl,$breakpointData['imageWidth'].'w'];
            }
            $content .= 'background-image: url(' . $imageUrl . ');' . PHP_EOL;
            $content .= '}' . PHP_EOL;
            $content .= '}' . PHP_EOL;
        }


        if ($preload) {
            $preloadHref = implode(', ', array_map(function ($entry) {
                return implode(' ',$entry);
            }, $preloadHrefStack));

            $GLOBALS['TSFE']->pSetup['preloads.']['bg-'] = $preloadHrefStack[0][0];;
            $GLOBALS['TSFE']->pSetup['preloads.']['bg-.'] = [
                'as' => 'image',
                // don't add as imagesrcset. It won't improve the lighthouse test
                //'imagesrcset' => $preloadHref
            ];
        }

        $name = md5($content);
        GeneralUtility::makeInstance(AssetCollector::class)->addInlineStyleSheet($name,$content,[],['priority' => true]);
    }

    private static function processImageUri($image, $maxWidth, $cropVariant = 'default', $absolute = false)
    {

        try {
            $imageService = self::getImageService();
            $image = $imageService->getImage('', $image, false);

            $processingInstructions = [];
            if ($image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
                $cropVariantCollection = CropVariantCollection::create((string)$cropString);
                $cropArea = $cropVariantCollection->getCropArea($cropVariant);
                $processingInstructions = [
                    'maxWidth' => $maxWidth,
                    'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
                ];
            }

            if (!empty($arguments['fileExtension'])) {
                $processingInstructions['fileExtension'] = $arguments['fileExtension'];
            }

            $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
            return $imageService->getImageUri($processedImage, $absolute);
        } catch (ResourceDoesNotExistException $e) {
            // thrown if file does not exist
            throw new Exception($e->getMessage(), 1509741907, $e);
        } catch (\UnexpectedValueException $e) {
            // thrown if a file has been replaced with a folder
            throw new Exception($e->getMessage(), 1509741908, $e);
        } catch (\RuntimeException $e) {
            // RuntimeException thrown if a file is outside of a storage
            throw new Exception($e->getMessage(), 1509741909, $e);
        } catch (\InvalidArgumentException $e) {
            // thrown if file storage does not exist
            throw new Exception($e->getMessage(), 1509741910, $e);
        }

    }


    /**
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected static function getTypoScriptSettings(): array
    {
        $all = GeneralUtility::makeInstance(ConfigurationManager::class)->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $segments = explode('.', 'plugin.tx_wst3bootstrap.settings');
        $value = $all;
        foreach ($segments as $path) {
            $value = (true === isset($value[$path . '.']) ? $value[$path . '.'] : $value[$path]);
        }
        if (true === \is_array($value)) {
            $value = GeneralUtility::removeDotsFromTS($value);
        }
        return $value;
    }

    /**
     * Return an instance of ImageService using object manager
     *
     * @return ImageService
     */
    protected static function getImageService()
    {
        return GeneralUtility::makeInstance(ImageService::class);
    }


}
