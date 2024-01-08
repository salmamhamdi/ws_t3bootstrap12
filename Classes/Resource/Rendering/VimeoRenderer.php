<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace WapplerSystems\WsT3bootstrap\Resource\Rendering;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Vimeo renderer class
 */
class VimeoRenderer implements FileRendererInterface
{
    /**
     * @var OnlineMediaHelperInterface
     */
    protected $onlineMediaHelper;

    /**
     * Returns the priority of the renderer
     * This way it is possible to define/overrule a renderer
     * for a specific file type/context.
     * For example create a video renderer for a certain storage/driver type.
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file)
    {
        return ($file->getMimeType() === 'video/vimeo' || $file->getExtension() === 'vimeo') && $this->getOnlineMediaHelper($file) !== false;
    }

    /**
     * Get online media helper
     *
     * @param FileInterface $file
     * @return bool|OnlineMediaHelperInterface
     */
    protected function getOnlineMediaHelper(FileInterface $file)
    {
        if ($this->onlineMediaHelper === null) {
            $orgFile = $file;
            if ($orgFile instanceof FileReference) {
                $orgFile = $orgFile->getOriginalFile();
            }
            if ($orgFile instanceof File) {
                $this->onlineMediaHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($orgFile);
            } else {
                $this->onlineMediaHelper = false;
            }
        }
        return $this->onlineMediaHelper;
    }

    /**
     * Render for given File(Reference) html output
     *
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false)
    {
        $options = $this->collectOptions($options, $file);
        $src = $this->createVimeoUrl($options, $file);
        $attributes = $this->collectIframeAttributes($width, $height, $options);

        if (($options['opt_in'] ?? false) === '1') {

            $optInTextBox = '<div class="opt-in-textbox"><p>' . LocalizationUtility::translate('optin_text_vimeo', 'WsT3bootstrap', [$options['policyUrl']]) . '<br><a class="btn btn-outline-white opt-in-btn">' . LocalizationUtility::translate('optin_button_label_vimeo', 'WsT3bootstrap') . '</a></p></div>';

            // prepare poster inline CSS
            if (isset($options['posterUrl'])) {
                $posterCSS = ' style="background-image: url(\'' . $options['posterUrl'] . '\');"';
            } else {
                $posterCSS = '';
            }

            return sprintf(
                '<div class="opt-in-frame-wrapper" data-t3b-optin="vimeo"><iframe data-src="%s"%s></iframe><div class="opt-in-overlay-wrapper opt-in-overlay-vimeo"%s>%s</div></div>',
                htmlspecialchars($src, ENT_QUOTES | ENT_HTML5),
                empty($attributes) ? '' : ' ' . $this->implodeAttributes($attributes),
                $posterCSS,
                $optInTextBox
            );
        }

        return sprintf(
            '<iframe src="%s"%s></iframe>',
            htmlspecialchars($src, ENT_QUOTES | ENT_HTML5),
            empty($attributes) ? '' : ' ' . $this->implodeAttributes($attributes)
        );
    }

    /**
     * @param array $options
     * @param FileInterface $file
     * @return array
     */
    protected function collectOptions(array $options, FileInterface $file)
    {
        if ($file instanceof FileReference) {
            // Check for an autoplay option at the file reference itself, if not overridden yet.
            if (!isset($options['autoplay'])) {
                $autoplay = $file->getProperty('autoplay');
                if ($autoplay !== null) {
                    $options['autoplay'] = $autoplay;
                }
            }
            // Check for an loop option at the file reference itself, if not overridden yet.
            if (!isset($options['loop'])) {
                $loop = $file->getProperty('loop');
                if ($loop !== null) {
                    $options['loop'] = $loop;
                }
            }
            // Check for an muted option at the file reference itself, if not overridden yet.
            if (!isset($options['muted'])) {
                $muted = $file->getProperty('muted');
                if ($muted !== null) {
                    $options['muted'] = $muted;
                }
            }

            if (!isset($options['poster'])) {

                if (is_array($file->getProperty('poster')) && count($file->getProperty('poster')) > 0 && $file->getProperty('poster')[0] instanceof FileReference) {
                    /** @var FileRepository $fileRepository */
                    $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
                    /** @var FileReference $fileReference */
                    $fileReference = $file->getProperty('poster')[0];

                    if ($fileReference->getOriginalFile()) {
                        /** @var FileReference $poster */
                        $posterFile = $fileReference->getOriginalFile();
                        $imageService = GeneralUtility::makeInstance(ImageService::class);
                        $processingInstructions = [
                            'maxWidth' => 1024,
                        ];
                        $processedImage = $imageService->applyProcessingInstructions($posterFile, $processingInstructions);
                        $options['posterUrl'] = $processedImage->getPublicUrl();
                    }

                }
            }

            // get ts configuration for privacy policy and opt-in related stuff.
            $tsArray = GeneralUtility::makeInstance(ConfigurationManagerInterface::class)
                ->getConfiguration(
                    ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
                );

            $options['opt_in'] = $tsArray['plugin.']['tx_wst3bootstrap.']['settings.']['privacyPolicies.']['optinVideo'] ?? '';

            // get privacy policy url
            $options['policyUrl'] = $tsArray['plugin.']['tx_wst3bootstrap.']['settings.']['privacyPolicies.']['vimeo.']['defaultLang'] ?? '';
        }

        if (!isset($options['allow'])) {
            $options['allow'] = 'fullscreen';
            if (!empty($options['autoplay'])) {
                $options['allow'] = 'autoplay; fullscreen';
            }
        }
        return $options;
    }

    /**
     * @param array $options
     * @param FileInterface $file
     * @return string
     */
    protected function createVimeoUrl(array $options, FileInterface $file)
    {
        $videoId = $this->getVideoIdFromFile($file);
        $urlParams = [];
        if (!empty($options['autoplay'])) {
            $urlParams[] = 'autoplay=1';
        }
        if (!empty($options['loop'])) {
            $urlParams[] = 'loop=1';
        }
        if (!empty($options['muted'])) {
            $urlParams[] = 'muted=1';
        }
        if (isset($options['api']) && (int)$options['api'] === 1) {
            $urlParams[] = 'api=1';
        }
        if (!empty($options['no-cookie'])) {
            $urlParams[] = 'dnt=1';
        }
        $urlParams[] = 'title=' . (int)!empty($options['showinfo']);
        $urlParams[] = 'byline=' . (int)!empty($options['showinfo']);
        $urlParams[] = 'portrait=0';

        return sprintf('https://player.vimeo.com/video/%s?%s', $videoId, implode('&', $urlParams));
    }

    /**
     * @param FileInterface $file
     * @return string
     */
    protected function getVideoIdFromFile(FileInterface $file)
    {
        if ($file instanceof FileReference) {
            $orgFile = $file->getOriginalFile();
        } else {
            $orgFile = $file;
        }

        return $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
    }

    /**
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @return array pairs of key/value; not yet html-escaped
     */
    protected function collectIframeAttributes($width, $height, array $options)
    {
        $attributes = [];
        $attributes['allowfullscreen'] = true;

        if (isset($options['additionalAttributes']) && is_array($options['additionalAttributes'])) {
            $attributes = array_merge($attributes, $options['additionalAttributes']);
        }
        if (isset($options['data']) && is_array($options['data'])) {
            array_walk($options['data'], function (&$value, $key) use (&$attributes) {
                $attributes['data-' . $key] = $value;
            });
        }
        if ((int)$width > 0) {
            $attributes['width'] = (int)$width;
        }
        if ((int)$height > 0) {
            $attributes['height'] = (int)$height;
        }
        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE']) && (isset($GLOBALS['TSFE']->config['config']['doctype']) && $GLOBALS['TSFE']->config['config']['doctype'] !== 'html5')) {
            $attributes['frameborder'] = 0;
        }
        foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'allow'] as $key) {
            if (!empty($options[$key])) {
                $attributes[$key] = $options[$key];
            }
        }

        return $attributes;
    }

    /**
     * @param array $attributes
     * @return string
     * @internal
     */
    protected function implodeAttributes(array $attributes): string
    {
        $attributeList = [];
        foreach ($attributes as $name => $value) {
            $name = preg_replace('/[^\p{L}0-9_.-]/u', '', $name);
            if ($value === true) {
                $attributeList[] = $name;
            } else {
                $attributeList[] = $name . '="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            }
        }
        return implode(' ', $attributeList);
    }
}
