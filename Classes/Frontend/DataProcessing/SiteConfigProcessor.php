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

use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * This data processor can be used for processing data for record which contain
 * relations to sys_file records (e.g. sys_file_reference records) or for fetching
 * files directly from UIDs or from folders or collections.
 *
 *
 * Example TypoScript configuration:
 *
 * 10 = WapplerSystems\WsT3bootstrap\Frontend\DataProcessing\SiteConfigProcessor
 * 10 {
 *   as = siteConfig
 * }
 *
 * whereas "myfiles" can further be used as a variable {myfiles} inside a Fluid template for iteration.
 */
class SiteConfigProcessor implements DataProcessorInterface
{

    /** @var SiteFinder */
    protected $siteFinder;

    /**
     * Property for accessing TypoScriptFrontendController centrally
     *
     * @var TypoScriptFrontendController
     */
    protected $frontendController;


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
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $this->frontendController = $GLOBALS['TSFE'];

        $siteConfig = [];

        try {
            $site = $this->siteFinder->getSiteByPageId((int)$this->frontendController->id);
            $siteConfig = $site->getConfiguration();
        } catch (SiteNotFoundException $e) {

        }

        /** @var LanguageAspect $languageAspect */
        $languageAspect = $this->frontendController->getContext()->getAspect('language');
        $siteConfig['sys_language_uid'] = $languageAspect->getId();

        // set the files into a variable, default "files"
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'siteConfig');
        $processedData[$targetVariableName] = $siteConfig;

        return $processedData;
    }



    /**
     * @return boolean
     */
    protected function isDefaultLanguage()
    {
        return $this->getCurrentLanguageUid() === 0;
    }

    /**
     * @return integer
     */
    protected function getCurrentLanguageUid()
    {
        return (integer) $GLOBALS['TSFE']->sys_language_uid;
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
