<?php

namespace WapplerSystems\WsT3bootstrap\ViewHelpers\Link;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * A ViewHelper for creating a link for an image or video popup.
 *
 *
 */
class ClickEnlargeViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize ViewHelper arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('media', FileInterface::class, 'The original media file', true);
        $this->registerArgument(
            'configuration',
            'mixed',
            'String, \TYPO3\CMS\Core\Resource\File or \TYPO3\CMS\Core\Resource\FileReference with link configuration',
            true
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        /** @var FileReference $media */
        $media = $arguments['media'];
        if ($media instanceof FileInterface) {
            self::getContentObjectRenderer()->setCurrentFile($media);
        }

        $objDataBackup = null;
        if ($renderingContext->getVariableProvider()->exists('data')) {
            $objDataBackup = self::getContentObjectRenderer()->data;
            self::getContentObjectRenderer()->data = $renderingContext->getVariableProvider()->get('data');
        }
        $configuration = self::getTypoScriptService()->convertPlainArrayToTypoScriptArray($arguments['configuration']);
        $content = $renderChildrenClosure();
        $configuration['enable'] = true;

        if ($media->getProperty('type') === '4') {
            $params = [
                'parameter' => $media->getPublicUrl(),
                'ATagParams.' => $configuration['linkParams.']['ATagParams.'],
            ];
            $result = self::getContentObjectRenderer()->typoLink($content, $params);
        } else {
            $result = self::getContentObjectRenderer()->imageLinkWrap($content, $media, $configuration);
        }


        if ($objDataBackup) {
            self::getContentObjectRenderer()->data = $objDataBackup;
        }
        return $result;
    }

    /**
     * @return ContentObjectRenderer
     */
    protected static function getContentObjectRenderer()
    {
        return $GLOBALS['TSFE']->cObj;
    }

    /**
     * @return TypoScriptService
     */
    protected static function getTypoScriptService(): TypoScriptService
    {
        return GeneralUtility::makeInstance(TypoScriptService::class);
    }
}
