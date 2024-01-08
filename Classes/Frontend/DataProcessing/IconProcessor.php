<?php
declare(strict_types = 1);

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 *
 * 10 = WapplerSystems\WsT3bootstrap\Frontend\DataProcessing\IconProcessor
 *
 */
class IconProcessor implements DataProcessorInterface
{


    /**
     * The content object renderer
     *
     * @var ContentObjectRenderer
     */
    protected ?ContentObjectRenderer $cObj = null;

    /**
     * The processor configuration
     *
     * @var array
     */
    protected $processorConfiguration;

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $this->cObj = $cObj;
        $this->processorConfiguration = $processorConfiguration;

        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $as = (($processorConfiguration['as'] ?? '') !== '') ? $processorConfiguration['as'] : 'icon';

        $iconConfig = [
            'available' => false,
        ];

        $fieldName = $cObj->stdWrapValue('fieldName', $processorConfiguration, 'icon');
        if (str_contains($fieldName, '.')) {
            $fieldnameArray = explode('.',$fieldName);
            $icon = $processedData[$fieldnameArray[0]][$fieldnameArray[1]] ?? '';
        } else {
            $icon = $processedData['data'][$fieldName] ?? '';
        }

        if ($icon !== null && $icon !== '') {

            $parts = explode(';',$icon);
            if (count($parts) >= 2) {
                if (($parts[0] !== '') && file_exists(GeneralUtility::getFileAbsFileName($parts[0]))) {
                    $iconConfig['svg'] = file_get_contents(GeneralUtility::getFileAbsFileName($parts[0]));
                }
                if ($parts[1] !== '') {
                    $iconConfig['class'] = $parts[1];
                }
            } else {
                $iconConfig['text'] = $icon;
            }

            $iconConfig['available'] = true;

            if ((int)$this->getConfigurationValue('withAppearance') === 1) {

                $iconConfig['style'] = 'default';
                $iconConfig['inverted'] = false;
                if ($processedData['data']['icon_style'] !== null && $processedData['data']['icon_style'] !== '') {
                    if (str_contains($processedData['data']['icon_style'], 'bg-')) {
                        $iconConfig['inverted'] = true;
                    }

                    $iconConfig['style'] = $processedData['data']['icon_style'];
                }

                $iconConfig['size'] = $processedData['data']['icon_size'];
                $iconConfig['color'] = $processedData['data']['icon_color'];


                $iconConfig['codePosition'] = match ((int)($processedData['data']['icon_position'] ?? 0)) {
                    20, 21, 40, 30, 31 => 'frame',
                    default => 'headline',
                };

                $frame_class = [];
                switch ((int)($processedData['data']['icon_position'] ?? 0)) {
                    case 0:
                    case 11:
                        $frame_class[] = 'ce-icon-headline';
                        break;
                    case 20:
                        $frame_class[] = 'ce-icon-inframe';
                        $frame_class[] = 'ce-icon-block-left';
                        break;
                    case 21:
                        $frame_class[] = 'ce-icon-inframe';
                        $frame_class[] = 'ce-icon-block-right';
                        break;
                    case 40:
                        $frame_class[] = 'ce-icon-inframe';
                        $frame_class[] = 'ce-icon-block-above-center';
                        break;
                    case 30:
                        $frame_class[] = 'ce-icon-inframe';
                        $frame_class[] = 'ce-icon-block-above-left';
                        break;
                    case 31:
                        $frame_class[] = 'ce-icon-inframe';
                        $frame_class[] = 'ce-icon-block-above-right';
                        break;
                }
                $iconConfig['frame_classes'] = implode(' ',$frame_class);

            }

        }


        if (str_contains($as, '.')) {
            $asArray = explode('.',$as);
            if (count($asArray) === 3) {
                $processedData[$asArray[0]][$asArray[1]][$asArray[2]] = $iconConfig;
            }
            if (count($asArray) === 2) {
                $processedData[$asArray[0]][$asArray[1]] = $iconConfig;
            }
            if (count($asArray) === 1) {
                $processedData[$asArray[0]] = $iconConfig;
            }
        } else {
            $processedData[$as] = $iconConfig;
        }

        return $processedData;
    }

    /**
     * Get configuration value from processorConfiguration
     *
     * @param string $key
     * @return string
     */
    protected function getConfigurationValue($key)
    {
        return $this->cObj->stdWrapValue($key, $this->processorConfiguration, $this->menuDefaults[$key] ?? '');
    }
}
