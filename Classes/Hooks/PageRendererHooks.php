<?php
namespace WapplerSystems\WsT3bootstrap\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use WapplerSystems\WsT3bootstrap\Page\PageRenderer;

class PageRendererHooks
{

    public static function postRenderHook($params, PageRenderer $pageRenderer) {

        if (\is_array($GLOBALS['TSFE']->pSetup['preloads.'] ?? null)) {
            foreach ($GLOBALS['TSFE']->pSetup['preloads.'] as $key => $subconf) {

                if (\is_string($GLOBALS['TSFE']->pSetup['preloads.'][$key]) && trim($GLOBALS['TSFE']->pSetup['preloads.'][$key]) !== '') {

                    $href = self::getStreamlinedFileName($GLOBALS['TSFE']->pSetup['preloads.'][$key]);
                    $as = $GLOBALS['TSFE']->pSetup['preloads.'][$key . '.']['as'] ?? '';
                    $type = $GLOBALS['TSFE']->pSetup['preloads.'][$key . '.']['type'] ?? '';
                    $crossorigin = $GLOBALS['TSFE']->pSetup['preloads.'][$key . '.']['crossorigin'] ?? false;
                    $imagesrcset = $GLOBALS['TSFE']->pSetup['preloads.'][$key . '.']['imagesrcset'] ?? false;
                    $imagesizes = $GLOBALS['TSFE']->pSetup['preloads.'][$key . '.']['imagesizes'] ?? false;

                    $pageRenderer->addPeloads('<link rel="preload" href="'.$href.'" as="'.$as.'"'.($type !== '' ? ' type="'.$type.'"': '').($crossorigin ? ' crossorigin': '').($imagesrcset ? ' imagesrcset="'.$imagesrcset.'"': '').($imagesizes ? ' imagesizes="'.$imagesizes.'"': '').'/>');

                }
            }
        }

    }


    /**
     *
     * @param string $file the filename to process
     * @return string
     * @internal
     */
    protected static function getStreamlinedFileName($file)
    {
        if (strpos($file, 'EXT:') === 0) {
            $file = GeneralUtility::getFileAbsFileName($file);
            // as the path is now absolute, make it "relative" to the current script to stay compatible
            $file = PathUtility::getRelativePathTo($file) ?? '';
            $file = rtrim($file, '/');
        } else {
            $file = GeneralUtility::resolveBackPath($file);
        }
        return $file;
    }


    public static function changeTemplateFile($params, PageRenderer $pageRenderer) {
        $pageRenderer->setTemplateFile('EXT:ws_t3bootstrap/Resources/Private/Templates/PageRenderer.html');
    }

}
