<?php
declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use WapplerSystems\WsT3bootstrap\Utility\ContentElementSettings;

/**
 *
 * 10 = WapplerSystems\WsT3bootstrap\Frontend\DataProcessing\FrameClassesProcessor
 *
 */
class FrameClassesProcessor implements DataProcessorInterface
{

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $classes = [];
        $effects = [];
        $flexbox = [];      // Image orient overhaul via flexbox

        $classes[] = 'frame';

        /** @var FrontendTypoScript $typoScript */
        $typoScript = $cObj->getRequest()->getAttribute('frontend.typoscript');

        if (count($processedData['backgroundMedia'] ?? []) > 0) {
            $classes[] = 'b-bg-overlay';
        }
        if ($processedData['data']['fixed_image'] ?? false) {
            $classes[] = 'g-fixed-bg';
        }
        if ($processedData['data']['full_width'] ?? false) {
            $classes[] = 'g-inner-container';
        }

        $classes[] = 'frame-type-' . ($processedData['data']['CType'] ?? '');
        $classes[] = 'frame-layout-' . ($processedData['data']['layout'] ?? '');

        /* effect */
        if (isset($processedData['data']['effects'])) {

            /* cards */
            if ($processedData['data']['CType'] === 'card') {
                if (strpos($processedData['data']['effects'], 'flip') !== false) {
                    $classes[] = 'flip-card';
                    $effects['flip'] = true;
                }
                if (strpos($processedData['data']['effects'], 'hover') !== false) {
                    $classes[] = 'hover-card';
                    $effects['hover'] = true;
                }
            }
            if (strpos($processedData['data']['effects'], 'parallaxBg') !== false) {
                $classes[] = 'parallaxBg';
                $effects['parallaxBg'] = true;
            }
        }

        if (isset($processedData['data']['imageorient'])) {

            $flexJustify = "";
            $captionJustify = "";
            $flexJustifyVertical = "";
            switch ((int)$processedData['data']['imageorient']) {
                case 28:
                case 2:
                    $flexJustify = "justify-content-start";
                    $captionJustify = "text-start";
                    break;
                case 29:
                    $flexJustifyVertical = "justify-content-center";
                case 0:
                    $flexJustify = "justify-content-center";
                    $captionJustify = "text-center";
                    break;
                case 30:
                case 1:
                    $flexJustify = "justify-content-end";
                    $captionJustify = "text-end";
                    break;
            }

            $flexbox['classes'] = "d-flex " . $flexJustify;
            $flexbox['verticalClasses'] = $flexJustifyVertical;
            $flexbox['captionJustify'] = $captionJustify;
        }

        $settings = $contentObjectConfiguration['settings.']['wst3bootstrap.'];

        if (($processedData['data']['space_before_class'] ?? '') !== '') {
            if ($processedData['data']['space_before_class'] !== 'none') {
                $classes[] = 'frame-space-before-' . $processedData['data']['space_before_class'];
            }
        } elseif (isset($processedData['data']['CType'])) {
            $defaultValue = ContentElementSettings::getSettingByTypeAndPath($processedData['data']['CType'], 'content.margin.top', $typoScript->getSetupTree());
            if ($defaultValue !== null) {
                $classes[] = 'frame-space-before-' . $defaultValue;
            }
        }

        if (($processedData['data']['space_after_class'] ?? '') !== '') {
            if ($processedData['data']['space_after_class'] !== 'none') {
                $classes[] = 'frame-space-after-' . $processedData['data']['space_after_class'];
            }
        } elseif (isset($processedData['data']['CType'])) {
            $defaultValue = ContentElementSettings::getSettingByTypeAndPath($processedData['data']['CType'], 'content.margin.bottom', $typoScript->getSetupTree());
            if ($defaultValue !== null) {
                $classes[] = 'frame-space-after-' . $defaultValue;
            }
        }


        if (($processedData['data']['padding_top_class'] ?? '') !== '') {
            if ($processedData['data']['padding_top_class'] !== 'none') {
                $classes[] = 'frame-padding-top-' . $processedData['data']['padding_top_class'];
            }
        } elseif (isset($processedData['data']['CType'])) {
            $defaultValue = ContentElementSettings::getSettingByTypeAndPath($processedData['data']['CType'], 'content.padding.top', $typoScript->getSetupTree());
            if ($defaultValue !== null) {
                $classes[] = 'frame-padding-top-' . $defaultValue;
            }
        }

        if (($processedData['data']['padding_bottom_class'] ?? '') !== '') {
            if ($processedData['data']['padding_bottom_class'] !== 'none') {
                $classes[] = 'frame-padding-bottom-' . $processedData['data']['padding_bottom_class'];
            }
        } elseif (isset($processedData['data']['CType'])) {
            $defaultValue = ContentElementSettings::getSettingByTypeAndPath($processedData['data']['CType'], 'content.padding.bottom', $typoScript->getSetupTree());
            if ($defaultValue !== null) {
                $classes[] = 'frame-padding-bottom-' . $defaultValue;
            }
        }

        if (isset($processedData['data']['element_classes'])) {
            $classes = array_merge($classes, explode(',', $processedData['data']['element_classes']));
        }

        if (isset($processedData['data']['frame_class'])) {
            $classes = array_merge($classes, array_map(function ($className) {
                return 'frame-' . $className;
            }, explode(',', $processedData['data']['frame_class'])));
            $classes = array_merge($classes, explode(',', $processedData['data']['frame_class']));
        }

        if (isset($processedData['data']['bg_color_class'])) {
            $classes = array_merge($classes, explode(',', $processedData['data']['bg_color_class']));
        }


        /* icon */
        if (is_array($processedData['icon']) && $processedData['icon']['available'] === true) {

            $classes[] = $processedData['icon']['frame_classes'];

        }

        /* visibility */
        $visibility = $processedData['data']['visibility_flags'] ?? 0;
        $activeCheckboxes = [];
        for ($i = 0; $i < 10; $i++) {
            if (($visibility >> $i) & 1) {
                $activeCheckboxes[] = $i + 1;
            }
        }
        $visibilityFlagsMapping = [
            1 => '',
            2 => 'sm-',
            3 => 'md-',
            4 => 'lg-',
            5 => 'xl-',
            6 => 'xxl-',
        ];
        foreach ($visibilityFlagsMapping as $key => $visibilityFlag) {
            if (in_array($key,$activeCheckboxes)) {
                $classes[] = 'd-'.$visibilityFlag.'flex';
            } else {
                $classes[] = 'd-'.$visibilityFlag.'none';
            }

        }


        $processedData['frame_classes'] = implode(' ', $classes);
        $processedData['effects'] = $effects;
        $processedData['data']['flexbox'] = $flexbox;

        return $processedData;
    }
}
