<?php
namespace WapplerSystems\WsT3bootstrap\Page;


class PageRenderer extends \TYPO3\CMS\Core\Page\PageRenderer {

    protected $preloads = [];


    public function addPeloads($data)
    {
        if (!in_array($data, $this->preloads)) {
            $this->preloads[] = $data;
        }
    }

    /**
     * Fills the marker array with the given strings and trims each value
     *
     * @param string $jsLibs
     * @param string $jsFiles
     * @param string $jsFooterFiles
     * @param string $cssLibs
     * @param string $cssFiles
     * @param string $jsInline
     * @param string $cssInline
     * @param string $jsFooterInline
     * @param string $jsFooterLibs
     * @param string $metaTags
     * @return array Marker array
     */
    protected function getPreparedMarkerArray($jsLibs, $jsFiles, $jsFooterFiles, $cssLibs, $cssFiles, $jsInline, $cssInline, $jsFooterInline, $jsFooterLibs, $metaTags)
    {
        $markerArray = parent::getPreparedMarkerArray($jsLibs, $jsFiles, $jsFooterFiles, $cssLibs, $cssFiles, $jsInline, $cssInline, $jsFooterInline, $jsFooterLibs, $metaTags);
        $markerArray['PRELOADS'] = $this->preloads ? implode(LF, $this->preloads) : '';
        return $markerArray;
    }

}
