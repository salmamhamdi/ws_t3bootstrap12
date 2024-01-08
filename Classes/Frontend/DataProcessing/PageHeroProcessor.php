<?php

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

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

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Resource\FileCollector;

/**
 *
 */
class PageHeroProcessor implements DataProcessorInterface
{
    /**
     * Process data of a record to resolve File objects to the view
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $page = $this->getActiveRecord();

        $processedData['hero'] = [
            'useJumbotron' => (bool)$page['jumbotron'],
            'useContainer' => (bool)$page['heroContainer'],
            'slideLevels' => (int)$page['heroslide'],
            'useBigImage' => ($page['header_size'] === 'big'),  // backward compatibility
            'size' => $page['header_size'],
            'useDarkImage' => (bool)$page['header_darken'],
        ];

        $processedData['hero']['media'] = match ($page['header_size']) {
            'fullscreen' => $this->getSlideRecords($cObj, $page['uid'], 'heromedia_fullscreen', (int)$page['heroslide']),
            'big' => $this->getSlideRecords($cObj, $page['uid'], 'heroimage_big', (int)$page['heroslide']),
            default => $this->getSlideRecords($cObj, $page['uid'], 'heroimage', (int)$page['heroslide']),
        };

        // backward compatibility
        $processedData['hero']['images'] = $processedData['hero']['media'];

        /* videos */
        /** @var FileCollector $fileCollector */
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromRelation('pages', 'video', $page);
        $videos = $fileCollector->getFiles();

        if (count($videos) > 0) {
            $processedData['hero']['video'] = $videos[0];

            /** @var FileReference $video */
            $video = $videos[0];
            $fileCollector2 = GeneralUtility::makeInstance(FileCollector::class);
            $fileCollector2->addFilesFromRelation('sys_file_reference', 'poster', $video->getProperties());
            $posterFiles = $fileCollector2->getFiles();
            if (count($posterFiles) === 1) {
                $processedData['hero']['videoPoster'] = $posterFiles[0];
            }
        }

        return $processedData;
    }


    /**
     * Get records, optionally sliding up the page rootline
     *
     * @param ContentObjectRenderer $cObj
     * @param int $pageUid
     * @param $fieldName
     * @param int $limit
     * @return FileReference[]|null
     */
    protected function getSlideRecords(ContentObjectRenderer $cObj, $pageUid, $fieldName, int $limit = 0)
    {

        if ($limit <= 0) {
            $images = $this->getFileReference($cObj, $fieldName);
            if (count($images) > 0) {
                return $images;
            }
        }
        $rootLine = GeneralUtility::makeInstance(RootlineUtility::class,$pageUid)->get();
        if ($limit >= 0) {
            $rootLine = array_slice($rootLine, 0, $limit + 1);
        }

        foreach ($rootLine as $page) {
            $images = $this->getFileReference($cObj, $fieldName, $page);
            if (count($images) > 0) {
                return $images;
            }
        }
        return null;
    }


    /**
     * @param ContentObjectRenderer $cObj
     * @param $fieldName
     * @param array|null $page
     * @return array
     */
    private function getFileReference(ContentObjectRenderer $cObj, $fieldName, array $page = null)
    {

        /** @var FileCollector $fileCollector */
        $fileCollector = GeneralUtility::makeInstance(FileCollector::class);
        $fileCollector->addFilesFromRelation('pages', $fieldName, $page ?? $cObj->data);

        return $fileCollector->getFiles();
    }

    /**
     * AbstractRecordResource usually uses the current cObj as reference,
     * but the page is needed here
     *
     * @return array
     */
    public function getActiveRecord()
    {
        return $GLOBALS['TSFE']->page;
    }

}
