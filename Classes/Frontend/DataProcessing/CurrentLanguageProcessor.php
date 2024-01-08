<?php

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;


use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 *
 *
 */
class CurrentLanguageProcessor implements DataProcessorInterface
{

    protected ?TypoScriptFrontendController $tsfe;

    public function __construct(TypoScriptFrontendController $tsfe = null)
    {
        $this->tsfe = $tsfe ?? $GLOBALS['TSFE'] ?? null;
    }

    /**
     * Process data of a record to resolve File objects to the view
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) : array
    {

        /** @var LanguageAspect $languageAspect */
        $languageAspect = $GLOBALS['TSFE']->getContext()->getAspect('language');

        $site = $this->getCurrentSite();

        /** @var SiteLanguage $language */
        foreach ($site->getLanguages() as $language) {
            if ($language->getLanguageId() === $languageAspect->getId()) {
                $processedData['siteLanguage'] = $language;
                break;
            }
        }

        return $processedData;
    }



    /**
     * Returns the currently configured "site" if a site is configured (= resolved) in the current request.
     *
     * @return Site|null
     */
    protected function getCurrentSite(): ?Site
    {
        if ($this->tsfe instanceof TypoScriptFrontendController) {
            return $this->tsfe->getSite();
        }
        return null;
    }

}
